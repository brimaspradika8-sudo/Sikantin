<?php

namespace App\Services;

use Midtrans\Snap;
use Midtrans\Config;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$clientKey = config('services.midtrans.client_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
        
        // Validate credentials
        if (empty(Config::$serverKey) || strpos(Config::$serverKey, 'xxx') !== false) {
            \Log::warning('Midtrans server key is not configured properly');
        }
        if (empty(Config::$clientKey) || strpos(Config::$clientKey, 'xxx') !== false) {
            \Log::warning('Midtrans client key is not configured properly');
        }
    }

    public function createTransaction($order)
    {
        try {
            $transactionDetails = [
                'order_id' => $order->order_number,
                'gross_amount' => $order->total_amount,
            ];

            $customerDetails = [
                'first_name' => optional($order->user)->name ?? '',
                'email' => optional($order->user)->email ?? '',
                'phone' => optional($order->user)->phone ?? '',
            ];

            $items = [];
            foreach ($order->items as $item) {
                if (! $item->product) {
                    // skip items with missing product data
                    continue;
                }

                $items[] = [
                    'id' => 'PROD-'.($item->product->id ?? '0'),
                    'price' => (int) ($item->price ?? $item->product->price ?? 0),
                    'quantity' => $item->quantity,
                    'name' => $item->product->name ?? 'Produk',
                ];
            }

            if ($order->service_fee > 0) {
                $items[] = [
                    'id' => 'SERVICE-FEE',
                    'price' => (int) $order->service_fee,
                    'quantity' => 1,
                    'name' => 'Biaya layanan',
                ];
            }

            if ($order->discount_amount > 0) {
                $items[] = [
                    'id' => 'DISCOUNT',
                    'price' => -1 * (int) $order->discount_amount,
                    'quantity' => 1,
                    'name' => 'Diskon promo',
                ];
            }

            $payload = [
                'transaction_details' => $transactionDetails,
                'customer_details' => $customerDetails,
                'item_details' => $items,
                'enabled_payments' => [
                    'gopay',
                    'shopeepay',
                    'qris',
                    'bank_transfer',
                    'bca_va',
                    'bni_va',
                    'bri_va',
                    'echannel',
                    'credit_card',
                ],
                'callbacks' => [
                    'finish' => route('user.orders.show', $order),
                ],
            ];

            \Log::info('Midtrans payload', $payload);

            $snapToken = Snap::getSnapToken($payload);

            if (!$snapToken) {
                throw new \Exception('Midtrans returned empty snap token');
            }

            \Log::info('Midtrans snap token created', ['order' => $order->order_number]);

            return $snapToken;
        } catch (\Exception $e) {
            \Log::error('Midtrans createTransaction error: ' . $e->getMessage(), [
                'order' => $order->order_number,
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function handleNotification($notification)
    {
        $orderId = $notification->order_id;
        $transactionStatus = $notification->transaction_status;
        $paymentType = $notification->payment_type;
        $fraudStatus = $notification->fraud_status ?? null;

        if ($transactionStatus == 'capture') {
            if ($fraudStatus == 'challenge') {
                return ['status' => 'challenge'];
            } elseif ($fraudStatus == 'accept') {
                return ['status' => 'success'];
            }
        } elseif ($transactionStatus == 'settlement') {
            return ['status' => 'success'];
        } elseif ($transactionStatus == 'pending') {
            return ['status' => 'pending'];
        } elseif ($transactionStatus == 'deny') {
            return ['status' => 'failed'];
        } elseif ($transactionStatus == 'expire') {
            return ['status' => 'expired'];
        } elseif ($transactionStatus == 'cancel') {
            return ['status' => 'cancelled'];
        }

        return ['status' => 'unknown'];
    }

    public function isValidSignature($notification): bool
    {
        $serverKey = config('services.midtrans.server_key');

        if (! isset($notification->order_id, $notification->status_code, $notification->gross_amount, $notification->signature_key)) {
            return false;
        }

        $signature = hash(
            'sha512',
            $notification->order_id.$notification->status_code.$notification->gross_amount.$serverKey
        );

        return hash_equals($signature, $notification->signature_key);
    }
}
