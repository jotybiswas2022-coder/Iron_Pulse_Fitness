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
        'pack_price_after_discount',
        'delivery_charge',
        'tax',
        'total_price',
        'status',
        'approved_at',
        'payment_method', 
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function orderdetails()
    {
        return $this->hasMany(OrderDetail::class, 'order_id', 'id');
    }
}
