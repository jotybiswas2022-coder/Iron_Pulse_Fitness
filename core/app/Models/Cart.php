<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'user_id',
        'pack_id',
        'quantity',
    ]; 

    public function pack()
    {
        return $this->belongsTo(Pack::class, 'pack_id', 'id');
    }
}
