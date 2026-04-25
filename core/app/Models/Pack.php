<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pack extends Model
{
    protected $fillable = [
        'name',
        'category_id',
        'type',
        'total_cost',
        'pack_price',
        'discount',
        'details',
        'image'
    ];

    public function PackCategory()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
}
