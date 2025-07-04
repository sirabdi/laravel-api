<?php

namespace App\Models;

use App\Models\ProductCategory;
use App\Models\Review;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'price',
        'image',
        'status',
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
}
