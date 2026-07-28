<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
    'customer_name',
    'customer_phone', 
    'customer_address',
    'items',
    'total',
    'status',
    'is_delivered',
];

protected $casts = [
    'items' => 'array',
    'is_delivered' => 'boolean',
];
}