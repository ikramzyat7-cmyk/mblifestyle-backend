<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'image',
        'subcategories',
        'order',
        'is_visible',
        'hidden_subcategories',
    ];
    
    protected $casts = [
        'subcategories' => 'array',
        'hidden_subcategories' => 'array',
        'is_visible' => 'boolean',
    ];
}