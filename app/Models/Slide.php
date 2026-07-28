<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slide extends Model
{
    protected $fillable = [
    'title', 'subtitle', 'button_text', 'button_link',
    'image', 'video', 'type', 'style',
    'promo_amount', 'promo_sub', 'product_image',
    'is_active', 'order'
];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}