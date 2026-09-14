<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
    'customer_name',
    'customer_phone',
    'customer_address',
    'delivery_city',
    'delivery_price',
    'items',
    'total',
    'status',
    'is_delivered',
];

    protected $casts = [
        'items' => 'array',
        'is_delivered' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            do {
                $code = 'MB-' . random_int(1000, 9999);
            } while (self::where('order_code', $code)->exists());

            $order->order_code = $code;
        });
    }
}