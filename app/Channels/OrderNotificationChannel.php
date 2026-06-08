<?php

namespace App\Channels;

use App\Models\OrderNotification;
use Illuminate\Notifications\Notification;

class OrderNotificationChannel
{
    public function send($notifiable, Notification $notification)
    {
        $data = $notification->toDatabase($notifiable);

        // ensure non-null values for DB columns that are NOT NULL
        $title = $data['title'] ?? ($data['order_number'] ?? 'Order notification');
        $message = $data['message'] ?? (isset($data['status']) ? "Order #" . ($data['order_number'] ?? $data['order_id'] ?? '') . " status: " . $data['status'] : 'You have a new order notification');

        OrderNotification::create([
            'user_id' => $notifiable->id,
            'order_id' => $data['order_id'] ?? null,
            'type' => get_class($notification),
            'title' => $title,
            'message' => $message,
            'icon' => $data['icon'] ?? null,
            'color' => $data['color'] ?? null,
            'is_read' => false,
        ]);
    }
}
