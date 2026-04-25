<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'category_id',
        'base_price',
        'price',
        'discount',
        'stock',
        'details',
        'image'
    ];

    public function ProductCategory()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
}
