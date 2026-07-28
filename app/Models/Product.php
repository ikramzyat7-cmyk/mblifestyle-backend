<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'brand',
        'category',
        'subcategory',
        'description',
        'price',
        'discount',
        'stock', 
        'images',
        'colors',
        'sizes',
        'display_order',
        'stock',
        'is_featured',
        'specs',
    ];

    protected $casts = [
        'images' => 'array',
        'colors' => 'array',
        'sizes' => 'array',
        'specs' => 'array',
    ];
}