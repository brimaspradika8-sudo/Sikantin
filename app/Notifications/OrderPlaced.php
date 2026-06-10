<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderPlaced extends Notification implements ShouldQueue
{
    use Queueable;

    protected $order;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Pesanan Baru: ' . $this->order->order_number)
            ->greeting('Halo ' . $notifiable->name . ',')
            ->line('Anda menerima pesanan baru di Sikantin!')
            ->line('Nomor Pesanan: ' . $this->order->order_number)
            ->line('Pelanggan: ' . $this->order->user->name)
            ->line('Total: Rp ' . number_format($this->order->total_amount, 0, ',', '.'))
            ->line('Metode Pembayaran: ' . ucfirst(str_replace('_', ' ', $this->order->payment_method)))
            ->action('Lihat Pesanan', route('seller.orders.show', $this->order->id))
            ->line('Terima kasih telah menggunakan Sikantin!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'customer_name' => $this->order->user->name,
            'total_amount' => $this->order->total_amount,
            'payment_method' => $this->order->payment_method,
            'message' => 'Pesanan baru diterima: ' . $this->order->order_number,
        ];
    }
}
