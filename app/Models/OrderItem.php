<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    // support both legacy `product_*` and `menu_item_*` fields used across the app
    protected $fillable = [
        'order_id',
        'product_id',
        'menu_item_id',
        'quantity',
        'price',
        'unit_price',
        'subtotal',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // some controllers and eager loads expect `menuItem` relationship
    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class, 'menu_item_id');
    }
}
