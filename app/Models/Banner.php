<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'title', 'subtitle', 'badge_text',
        'link', 'image', 'position', 'is_active'
    ];

    protected $casts = ['is_active' => 'boolean'];
}