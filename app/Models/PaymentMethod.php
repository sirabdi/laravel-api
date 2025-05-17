<?php

namespace App\Models;

use App\Models\Order;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = [
        'name',
        'method_code',
        'status',
    ];

    public function order()
    {
        return $this->hasMany(Order::class);
    }
}
