<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PopupSetting extends Model
{
    protected $fillable = [
        'is_active',
        'product_id',
        'product_ids',
        'title',
        'subtitle',
    ];
    
    protected $casts = [
        'product_ids' => 'array',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}