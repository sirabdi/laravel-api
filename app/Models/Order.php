<?php

namespace App\Models;

use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'shipping_method_id',
        'payment_method_id',
        'email',
        'shipping_price',
        'tax',
        'grand_total',
        'qty',
        'order_status',
        'payment_status',
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
