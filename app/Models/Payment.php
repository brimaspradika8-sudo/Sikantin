<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'amount',
        'payment_status',
        'payment_channel',
        'transaction_id',
        'snap_token',
        'invoice_number',
        'payment_proof',
        'bank_name',
        'account_number',
        'account_holder',
        'raw_response',
        'paid_at',
        'verified_at',
        'verified_by',
    ];

    protected $casts = [
        'raw_response' => 'array',
        'paid_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function histories()
    {
        return $this->hasMany(PaymentStatusHistory::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function statusLabel(): string
    {
        return [
            'pending' => 'Menunggu Pembayaran',
            'waiting_verification' => 'Menunggu Verifikasi',
            'success' => 'Pembayaran Berhasil',
            'failed' => 'Pembayaran Gagal',
            'cancelled' => 'Pembayaran Dibatalkan',
            'expired' => 'Pembayaran Kadaluarsa',
        ][$this->payment_status] ?? ucfirst(str_replace('_', ' ', $this->payment_status));
    }
}
