<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PaymentStatusHistory;
use App\Services\MidtransService;
use Illuminate\Http\Request;

class MidtransNotificationController extends Controller
{
    public function handle(Request $request)
    {
        $notification = json_decode($request->getContent());
        $midtrans = new MidtransService();

        if (! $notification || ! $midtrans->isValidSignature($notification)) {
            return response()->json(['status' => 'invalid_signature'], 403);
        }

        $orderId = $notification->order_id;
        $order = Order::where('order_number', $orderId)->first();

        if (!$order) {
            return response()->json(['status' => 'order_not_found'], 404);
        }

        $payment = $order->payment;

        if (!$payment) {
            return response()->json(['status' => 'payment_not_found'], 404);
        }

        $result = $midtrans->handleNotification($notification);
        $previousStatus = $payment->payment_status;

        switch ($result['status']) {
            case 'success':
                $payment->update([
                    'payment_status' => 'success',
                    'payment_channel' => $notification->payment_type ?? $payment->payment_channel,
                    'transaction_id' => $notification->transaction_id ?? null,
                    'raw_response' => (array) $notification,
                    'paid_at' => now(),
                ]);
                $order->update(['status' => 'paid']);
                break;
            case 'pending':
                $payment->update([
                    'payment_status' => 'pending',
                    'payment_channel' => $notification->payment_type ?? $payment->payment_channel,
                    'transaction_id' => $notification->transaction_id ?? null,
                    'raw_response' => (array) $notification,
                ]);
                $order->update(['status' => 'pending_payment']);
                break;
            case 'failed':
            case 'cancelled':
                $payment->update([
                    'payment_status' => 'failed',
                    'payment_channel' => $notification->payment_type ?? $payment->payment_channel,
                    'transaction_id' => $notification->transaction_id ?? null,
                    'raw_response' => (array) $notification,
                ]);
                $order->update(['status' => 'cancelled']);
                break;
            case 'expired':
                $payment->update([
                    'payment_status' => 'failed',
                    'payment_channel' => $notification->payment_type ?? $payment->payment_channel,
                    'transaction_id' => $notification->transaction_id ?? null,
                    'raw_response' => (array) $notification,
                ]);
                $order->update(['status' => 'cancelled']);
                break;
            case 'challenge':
                $payment->update([
                    'payment_status' => 'pending',
                    'raw_response' => (array) $notification,
                ]);
                break;
        }

        PaymentStatusHistory::create([
            'payment_id' => $payment->id,
            'from_status' => $previousStatus,
            'to_status' => $result['status'],
            'note' => 'Status diperbarui otomatis dari webhook Midtrans.',
        ]);

        return response()->json(['status' => 'ok']);
    }
}
