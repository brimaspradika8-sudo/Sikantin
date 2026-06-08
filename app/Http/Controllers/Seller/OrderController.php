<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PaymentStatusHistory;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['items.product', 'user', 'payment', 'qrcode'])
            ->where('seller_id', $request->user()->id);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        $orders = $query->latest()->get();

        return view('seller.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
     abort_unless($order->seller_id === auth()->user()->id, 403);

        $order->load(['items.product', 'user', 'payment.histories.actor', 'qrcode']);

        return view('seller.orders.show', compact('order'));
    }

    public function approvePayment(Request $request, Order $order)
    {
        abort_unless($order->seller_id === auth()->id(), 403);

        if (! $order->payment || $order->payment->payment_status !== 'pending') {
            return back()->with('warning', 'Pembayaran tidak dapat disetujui pada status saat ini.');
        }

        $previousStatus = $order->payment->payment_status;

        $order->payment->update([
            'payment_status' => 'success',
            'verified_at' => now(),
            'verified_by' => $request->user()->id,
            'paid_at' => now(),
        ]);
        $order->update(['status' => 'processing']);

        PaymentStatusHistory::create([
            'payment_id' => $order->payment->id,
            'actor_id' => $request->user()->id,
            'from_status' => $previousStatus,
            'to_status' => 'success',
            'note' => 'Pembayaran offline diverifikasi oleh penjual.',
        ]);

        return back()->with('success', 'Pembayaran offline berhasil disetujui.');
    }

    public function updateStatus(Request $request, Order $order)
    {
        abort_unless($order->seller_id === auth()->id(), 403);

        $request->validate([
            'status' => 'required|in:processing,ready,completed,cancelled',
        ]);

        $order->update(['status' => $request->status]);

        return back()->with('success', 'Status pesanan berhasil diperbarui.');
    }
}
