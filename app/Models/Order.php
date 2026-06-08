<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'seller_id',
        'vendor_id',
        'order_number',
        'total_amount',
        'discount_amount',
        'tax_amount',
        'service_fee',
        'status',
        'payment_method',
        'estimated_ready_at',
        'pickup_window_at',
        'customer_note',
    ];

    protected $casts = [
        'estimated_ready_at' => 'datetime',
        'pickup_window_at' => 'datetime',
    ];

    public static function statusLabels(): array
    {
        return [
            'pending_payment' => 'Menunggu Pembayaran',
            'paid' => 'Pembayaran Berhasil',
            'waiting_verification' => 'Menunggu Verifikasi',
            'processing' => 'Sedang Diproses',
            'ready' => 'Siap Diambil',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
        ];
    }

    public static function statusClasses(): array
    {
        return [
            'pending_payment' => 'bg-yellow-100 text-yellow-800',
            'paid' => 'bg-blue-100 text-blue-800',
            'waiting_verification' => 'bg-orange-100 text-orange-800',
            'processing' => 'bg-indigo-100 text-indigo-800',
            'ready' => 'bg-sky-100 text-sky-800',
            'completed' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800',
        ];
    }

    public function statusLabel(): string
    {
        return static::statusLabels()[$this->status] ?? ucfirst(str_replace('_', ' ', $this->status));
    }

    public function statusClass(): string
    {
        return static::statusClasses()[$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function qrcode()
    {
        return $this->hasOne(PickupQrcode::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}
