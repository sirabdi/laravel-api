<?php

namespace App\Models;

use App\Models\ProductCategory;
use App\Models\ProductGallery;
use App\Models\Review;
use App\Models\Cart;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'price',
        'image',
        'status',
        'total_qty',
        'description',
        'category_id',
    ];

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function review()
    {
        return $this->hasMany(Review::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function ProductGallery()
    {
        return $this->hasMany(ProductGallery::class);
    }
}
