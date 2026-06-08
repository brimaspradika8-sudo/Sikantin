<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Notification;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);

        $payment = $order->payment;
        $bankAccounts = $order->seller->bankAccounts()->where('is_active', true)->get();

        return view('payments.show', compact('order', 'payment', 'bankAccounts'));
    }

    public function uploadProof(Request $request, Order $order)
    {
        $this->authorize('view', $order);

        if ($order->payment->payment_method !== 'bank_transfer') {
            return back()->withError('Metode pembayaran tidak sesuai');
        }

        $validated = $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'bank_account_id' => 'required|exists:bank_accounts,id',
            'notes' => 'nullable|string|max:500',
        ]);

        $bankAccount = $order->seller->bankAccounts()
            ->findOrFail($validated['bank_account_id']);

        $path = $request->file('payment_proof')->store('payment-proofs', 'public');

        $order->payment->update([
            'payment_proof' => $path,
            'bank_name' => $bankAccount->bank_name,
            'account_number' => $bankAccount->account_number,
            'account_holder' => $bankAccount->account_holder,
            'payment_status' => 'waiting_verification',
        ]);

        Notification::create([
            'user_id' => $order->seller_id,
            'order_id' => $order->id,
            'type' => 'payment_awaiting_verification',
            'title' => 'Bukti Transfer Diterima',
            'message' => "Pesanan #{$order->order_number} menunggu verifikasi pembayaran",
            'icon' => 'hourglass',
            'color' => 'warning',
        ]);

        return back()->with('success', 'Bukti pembayaran berhasil diunggah. Menunggu verifikasi penjual.');
    }

    public function verify(Request $request, Order $order)
    {
        $this->authorize('update', $order);

        if ($order->payment->payment_method !== 'bank_transfer') {
            return back()->withError('Metode pembayaran tidak sesuai');
        }

        $validated = $request->validate([
            'action' => 'required|in:approve,reject',
            'notes' => 'nullable|string|max:500',
        ]);

        if ($validated['action'] === 'approve') {
            $order->payment->update([
                'payment_status' => 'success',
                'verified_by' => auth()->id(),
                'verified_at' => now(),
            ]);

            $message = 'Pembayaran berhasil diverifikasi';
            $type = 'payment_verified';
            $title = 'Pembayaran Disetujui';
        } else {
            $order->payment->update([
                'payment_status' => 'failed',
                'verified_by' => auth()->id(),
                'verified_at' => now(),
            ]);

            $message = 'Pembayaran ditolak. Silakan upload ulang bukti pembayaran.';
            $type = 'payment_failed';
            $title = 'Pembayaran Ditolak';
        }

        Notification::create([
            'user_id' => $order->user_id,
            'order_id' => $order->id,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'icon' => $validated['action'] === 'approve' ? 'check-circle' : 'x-circle',
            'color' => $validated['action'] === 'approve' ? 'success' : 'danger',
        ]);

        return back()->with('success', 'Verifikasi pembayaran berhasil diperbarui');
    }

    public function bankTransferInfo(Order $order)
    {
        $this->authorize('view', $order);

        $bankAccounts = $order->seller->bankAccounts()
            ->where('is_primary', true)
            ->where('is_active', true)
            ->get();

        return response()->json([
            'bank_accounts' => $bankAccounts,
            'total_amount' => $order->total_amount,
        ]);
    }
}
