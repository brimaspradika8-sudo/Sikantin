<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\MenuItem;
use App\Models\Notification;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $orders = auth()->user()->orders()
            ->with(['items.menuItem', 'payment', 'seller'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);

        // reuse the user-facing order detail view to avoid duplicate templates
        return view('user.order-detail', compact('order'));
    }

    public function create()
    {
        $menuItems = MenuItem::where('is_available', true)
            ->with('category', 'seller')
            ->get()
            ->groupBy('seller_id');

        return view('orders.create', compact('menuItems'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'seller_id' => 'required|exists:users,id',
            'items' => 'required|array|min:1',
            'items.*.menu_item_id' => 'required|exists:menu_items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|in:cash_on_pickup,bank_transfer',
            'customer_note' => 'nullable|string|max:500',
            'estimated_ready_at' => 'nullable|date_format:Y-m-d H:i',
        ]);

        try {
            return DB::transaction(function () use ($validated) {
                $seller = \App\Models\User::findOrFail($validated['seller_id']);

                $order = Order::create([
                    'user_id' => auth()->id(),
                    'seller_id' => $validated['seller_id'],
                    'order_number' => $this->generateOrderNumber(),
                    'total_amount' => 0, // will be calculated
                    'status' => 'pending_payment',
                    'payment_method' => $validated['payment_method'],
                    'customer_note' => $validated['customer_note'] ?? null,
                    'estimated_ready_at' => $validated['estimated_ready_at'] ?? null,
                ]);

                $totalAmount = 0;

                foreach ($validated['items'] as $item) {
                    $menuItem = MenuItem::findOrFail($item['menu_item_id']);

                    if ($menuItem->seller_id !== $validated['seller_id']) {
                        throw new \Exception('Menu item tidak sesuai dengan penjual');
                    }

                    $itemTotal = $menuItem->price * $item['quantity'];
                    $totalAmount += $itemTotal;

                    OrderItem::create([
                        'order_id' => $order->id,
                        'menu_item_id' => $item['menu_item_id'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $menuItem->price,
                        'subtotal' => $itemTotal,
                    ]);
                }

                $order->update(['total_amount' => $totalAmount]);

                Payment::create([
                    'order_id' => $order->id,
                    'amount' => $totalAmount,
                    'payment_status' => 'pending',
                    'payment_method' => $validated['payment_method'],
                ]);

                Notification::create([
                    'user_id' => auth()->id(),
                    'order_id' => $order->id,
                    'type' => 'order_created',
                    'title' => 'Pesanan Dibuat',
                    'message' => "Pesanan #{$order->order_number} berhasil dibuat",
                    'icon' => 'check-circle',
                    'color' => 'success',
                ]);

                Notification::create([
                    'user_id' => $validated['seller_id'],
                    'order_id' => $order->id,
                    'type' => 'new_order',
                    'title' => 'Pesanan Baru',
                    'message' => "Ada pesanan baru dari " . auth()->user()->name,
                    'icon' => 'shopping-bag',
                    'color' => 'info',
                ]);

                return $order;
            });
        } catch (\Exception $e) {
            return back()->withError('Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function trackStatus(Order $order)
    {
        $this->authorize('view', $order);

        return view('orders.track', compact('order'));
    }

    private function generateOrderNumber(): string
    {
        $date = now()->format('Ymd');
        $random = Str::upper(Str::random(4));
        return "ORD-{$date}-{$random}";
    }
}
