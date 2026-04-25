<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $fillable = [
        'order_id',
        'pack_id',
        'pack_name',
        'pack_price',
        'status'
    ];


    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function Pack()
    {
        return $this->belongsTo(\App\Models\Pack::class);
    }
}

