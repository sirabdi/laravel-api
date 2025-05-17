<?php

namespace App\Models;

use App\Models\Order;

use Illuminate\Database\Eloquent\Model;

class ShippingMethod extends Model
{
    protected $fillable = [
        'name',
        'method_code',
        'shipping_price',
        'status',
    ];

    public function order()
    {
        return $this->hasMany(Order::class);
    }
}
