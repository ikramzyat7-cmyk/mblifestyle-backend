<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class DeliveryCity extends Model
{
    protected $fillable = ['name', 'price', 'is_active', 'order'];
}
