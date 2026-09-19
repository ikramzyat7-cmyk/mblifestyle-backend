<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['name', 'product', 'product_id', 'rating', 'comment', 'status', 'image'];
}