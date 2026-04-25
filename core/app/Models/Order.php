<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'firstname',
        'lastname',
        'email',
        'phone',
        'address',
        'product_price_after_discount',
        'delivery_charge',
        'tax',
        'total_price',
        'status',
        'payment_method', 
    ];

    public function orderdetails()
    {
        return $this->hasMany(OrderDetail::class, 'order_id', 'id');
    }
}