<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PickupQrcode extends Model
{
    protected $fillable = ['order_id', 'token', 'expires_at', 'is_used'];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_used' => 'boolean',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function getIsExpiredAttribute()
    {
        return $this->expires_at->isPast();
    }
}
