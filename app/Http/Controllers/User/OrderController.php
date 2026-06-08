<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->user()->orders()->with(['items.product', 'seller', 'payment', 'qrcode']);

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        $orders = $query->latest()->get();

        return view('user.order-history', compact('orders'));
    }

    public function show(Order $order)
    {
        abort_unless($order->user_id === auth()->id(), 403);

        $order->load(['items.product', 'seller', 'payment', 'qrcode']);

        return view('user.order-detail', compact('order'));
    }

    public function destroy(Order $order)
    {
        abort_unless($order->user_id === auth()->id(), 403);

        $order->delete();

        return redirect()->route('user.orders.index')
            ->with('success', 'Pesanan berhasil dihapus dari riwayat.');
    }
}
