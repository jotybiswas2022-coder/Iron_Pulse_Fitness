<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'currency',
        'language',
        'delivery_charge',
        'tax_percentage',
    ];
}
