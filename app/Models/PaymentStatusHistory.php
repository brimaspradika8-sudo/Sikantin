<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentStatusHistory extends Model
{
    protected $fillable = [
        'payment_id',
        'actor_id',
        'from_status',
        'to_status',
        'note',
    ];

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function actor()
    {
        return $this->belongsTo(User::class, 'actor_id');
    }
}
