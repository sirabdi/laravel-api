<?php

namespace App\Models;

use App\Models\Order;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    protected $fillable = [
        'user_id',
        'address_line_one',
        'address_line_two',
        'city',
        'state',
        'zip',
        'country',
    ];

    public function order()
    {
        return $this->hasMany(Order::class);
    }
}
