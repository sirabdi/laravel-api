<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Product;

class ProductGallery extends Model
{
    protected $fillable = [
        'product_id',
        'image',
    ];

    public function Product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
