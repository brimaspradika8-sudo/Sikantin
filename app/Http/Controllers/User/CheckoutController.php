<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\PaymentStatusHistory;
use App\Models\PickupQrcode;
use App\Models\User;
use App\Models\Vendor;
use App\Notifications\OrderPlaced;
use App\Services\MidtransService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $cart = Cart::firstOrCreate(['user_id' => $request->user()->id]);
        $items = $cart->items()->with('product')->get();
        $subtotal = $items->sum(fn ($item) => $item->quantity * ($item->product->price ?? 0));
        $total = $subtotal;
        $clientKey = config('services.midtrans.client_key');

        return view('user.checkout', compact('items', 'subtotal', 'total', 'clientKey'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|in:midtrans,manual_transfer,cash_pickup,pay_at_canteen',
            'customer_note' => 'nullable|string|max:500',
            'promo_code' => 'nullable|string|max:40',
        ]);

        $cart = Cart::firstOrCreate(['user_id' => $request->user()->id]);
        $items = $cart->items()->with('product')->get();

        if ($items->isEmpty()) {
            return redirect()->route('user.cart')->with('warning', 'Keranjang masih kosong.');
        }

        $grouped = $items->groupBy(fn ($item) => $item->product->user_id);
        $createdOrders = [];

        foreach ($grouped as $sellerId => $sellerItems) {
            foreach ($sellerItems as $item) {
                if (! $item->product) {
                    return back()->with('warning', 'Produk '.(optional($item->product)->name ?? 'Produk tidak tersedia').' tidak ditemukan.');
                }
            }

            $subtotal = $sellerItems->sum(fn ($item) => $item->quantity * ($item->product->price ?? 0));
            $discount = $this->calculateDiscount($request->promo_code, $subtotal);
            $tax = 0;
            $serviceFee = $request->payment_method === 'midtrans' ? 1000 : 0;
            $total = max(0, $subtotal - $discount + $tax + $serviceFee);
            $vendor = Vendor::where('user_id', $sellerId)->first();

            $order = Order::create([
                'user_id' => $request->user()->id,
                'seller_id' => $sellerId,
                'vendor_id' => $vendor?->id,
                'order_number' => strtoupper('ORD-'.Str::random(8)),
                'total_amount' => $total,
                'discount_amount' => $discount,
                'tax_amount' => $tax,
                'service_fee' => $serviceFee,
                'status' => 'pending_payment',
                'payment_method' => $request->payment_method,
                'estimated_ready_at' => Carbon::now()->addMinutes(20),
                'pickup_window_at' => Carbon::now()->addMinutes(30),
                'customer_note' => $request->customer_note,
            ]);

            foreach ($sellerItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product->id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ]);
            }

            $payment = Payment::create([
                'order_id' => $order->id,
                'amount' => $total,
                'payment_status' => 'pending',
                'payment_channel' => $request->payment_method,
                'invoice_number' => 'INV-'.now()->format('Ymd').'-'.strtoupper(Str::random(6)),
                ...$this->offlineAccountPayload($request->payment_method),
            ]);

            PaymentStatusHistory::create([
                'payment_id' => $payment->id,
                'actor_id' => $request->user()->id,
                'to_status' => 'pending',
                'note' => 'Pesanan dibuat dan menunggu pembayaran.',
            ]);

            PickupQrcode::create([
                'order_id' => $order->id,
                'token' => Str::uuid()->toString(),
                'expires_at' => Carbon::now()->addMinutes(30),
            ]);

            $createdOrders[] = $order;

            $sellerUser = User::find($sellerId);
            if ($sellerUser) {
                $sellerUser->notify(new OrderPlaced($order));
            }
        }

        $cart->items()->delete();

        $firstOrder = $createdOrders[0] ?? null;

        if (! $firstOrder) {
            return redirect()->route('user.cart')->with('error', 'Gagal membuat pesanan.');
        }

        $snapToken = null;

        if ($request->payment_method === 'midtrans') {
            try {
                $midtrans = new MidtransService();
                $snapToken = $midtrans->createTransaction($firstOrder->load('items.product', 'user'));

                if (! $snapToken) {
                    return redirect()->route('user.cart')
                        ->with('error', 'Gagal membuat token pembayaran. Silakan coba lagi.');
                }

                $firstOrder->payment?->update(['snap_token' => $snapToken]);
            } catch (\Exception $e) {
                \Log::error('Midtrans error: '.$e->getMessage());

                return redirect()->route('user.cart')
                    ->with('error', 'Gagal membuat token pembayaran: '.$e->getMessage());
            }
        }

        session([
            'snap_token' => $snapToken,
            'payment_total' => $firstOrder->total_amount,
            'first_order_id' => $firstOrder->id,
        ]);

        return redirect()->route('user.payment');
    }

    public function payment(Request $request)
    {
        if (! session('first_order_id')) {
            return redirect()->route('user.cart')
                ->with('warning', 'Sesi pembayaran tidak ditemukan. Silakan checkout ulang.');
        }

        $firstOrder = Order::with(['items.product', 'user', 'seller', 'vendor', 'payment'])
            ->find(session('first_order_id'));

        if (! $firstOrder) {
            return redirect()->route('user.cart')
                ->with('warning', 'Pesanan tidak ditemukan. Silakan checkout ulang.');
        }

        abort_unless($firstOrder->user_id === $request->user()->id, 403);

        $snapToken = session('snap_token') ?: $firstOrder->payment?->snap_token;
        $items = $firstOrder->items;
        $total = $firstOrder->total_amount;
        $clientKey = config('services.midtrans.client_key');

        return view('user.payment', compact('snapToken', 'firstOrder', 'items', 'total', 'clientKey'));
    }

    public function clearPaymentSession(Request $request)
    {
        session()->forget(['snap_token', 'payment_total', 'first_order_id']);

        return response()->json(['status' => 'cleared']);
    }

    public function uploadProof(Request $request, Order $order)
    {
        abort_unless($order->user_id === $request->user()->id, 403);

        $request->validate([
            'payment_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $payment = $order->payment;

        if (! $payment || $order->payment_method !== 'manual_transfer') {
            return back()->with('warning', 'Upload bukti hanya tersedia untuk transfer bank manual.');
        }

        $path = $request->file('payment_proof')->store('payment-proofs', 'public');
        $previousStatus = $payment->payment_status;

        $payment->update([
            'payment_proof' => $path,
            'payment_status' => 'pending',
        ]);

        PaymentStatusHistory::create([
            'payment_id' => $payment->id,
            'actor_id' => $request->user()->id,
            'from_status' => $previousStatus,
            'to_status' => 'waiting_verification',
            'note' => 'Bukti transfer diunggah dan menunggu verifikasi penjual.',
        ]);

        return back()->with('success', 'Bukti transfer berhasil diunggah. Pembayaran menunggu verifikasi.');
    }

    public function invoice(Request $request, Order $order)
    {
        abort_unless($order->user_id === $request->user()->id, 403);

        $order->load(['items.product', 'user', 'seller', 'vendor', 'payment']);

        return view('user.invoice', compact('order'));
    }

    public function downloadInvoice(Request $request, Order $order)
    {
        abort_unless($order->user_id === $request->user()->id, 403);

        $order->load(['items.product', 'user', 'seller', 'vendor', 'payment']);
        $html = view('user.invoice', compact('order'))->render();
        $filename = ($order->payment?->invoice_number ?? $order->order_number).'.html';

        return response($html)
            ->header('Content-Type', 'text/html')
            ->header('Content-Disposition', 'attachment; filename="'.$filename.'"');
    }

    private function calculateDiscount(?string $promoCode, float $subtotal): float
    {
        if (! $promoCode) {
            return 0;
        }

        return strtoupper($promoCode) === 'SIKANTIN10'
            ? min($subtotal * 0.1, 10000)
            : 0;
    }

    private function offlineAccountPayload(string $method): array
    {
        if ($method !== 'manual_transfer') {
            return [];
        }

        return [
            'bank_name' => config('services.manual_payment.bank_name', 'BCA'),
            'account_number' => config('services.manual_payment.account_number', '1234567890'),
            'account_holder' => config('services.manual_payment.account_holder', 'SIKANTIN Online'),
        ];
    }
}
