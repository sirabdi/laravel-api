<?php

namespace App\Models;

use App\Models\OrderItem;
use App\Models\UserAddress;
use App\Models\PaymentMethod;
use App\Models\ShippingMethod;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'shipping_method_id',
        'payment_method_id',
        'payment_method_id',
        'user_address_id',
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

    public function userAddress()
    {
        return $this->belongsTo(UserAddress::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function shippingMethod()
    {
        return $this->belongsTo(ShippingMethod::class);
    }
}
