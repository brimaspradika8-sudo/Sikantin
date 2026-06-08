<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PaymentStatusHistory;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'seller', 'payment']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        $orders = $query->latest()->paginate(15)->withQueryString();
        $stats = [
            'total' => Order::count(),
            'paid' => Order::where('status', 'paid')->count(),
            'pending' => Order::where('status', 'pending_payment')->count(),
            'revenue' => Order::whereIn('status', ['paid', 'processing', 'ready', 'completed'])->sum('total_amount'),
        ];

        return view('admin.transactions.index', compact('orders', 'stats'));
    }

    public function approvePayment(Request $request, Order $order)
    {
        $payment = $order->payment;

        if (! $payment || $payment->payment_status !== 'pending') {
            return back()->with('warning', 'Pembayaran tidak dapat diverifikasi.');
        }

        $previousStatus = $payment->payment_status;

        $payment->update([
            'payment_status' => 'success',
            'verified_at' => now(),
            'verified_by' => $request->user()->id,
            'paid_at' => now(),
        ]);
        $order->update(['status' => 'processing']);

        PaymentStatusHistory::create([
            'payment_id' => $payment->id,
            'actor_id' => $request->user()->id,
            'from_status' => $previousStatus,
            'to_status' => 'success',
            'note' => 'Pembayaran diverifikasi oleh admin.',
        ]);

        return back()->with('success', 'Pembayaran berhasil diverifikasi.');
    }

    public function rejectPayment(Request $request, Order $order)
    {
        $request->validate(['reason' => 'nullable|string|max:255']);

        $payment = $order->payment;

        if (! $payment || $payment->payment_status !== 'pending') {
            return back()->with('warning', 'Pembayaran tidak dapat ditolak.');
        }

        $previousStatus = $payment->payment_status;

        $payment->update(['payment_status' => 'failed']);
        $order->update(['status' => 'pending_payment']);

        PaymentStatusHistory::create([
            'payment_id' => $payment->id,
            'actor_id' => $request->user()->id,
            'from_status' => $previousStatus,
            'to_status' => 'failed',
            'note' => $request->reason ?: 'Bukti pembayaran ditolak oleh admin.',
        ]);

        return back()->with('success', 'Bukti pembayaran ditolak.');
    }
}
