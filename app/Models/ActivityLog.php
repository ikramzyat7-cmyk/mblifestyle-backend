<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'action',
        'model',
        'model_id',
        'description',
        'changes',
        'admin_email',
    ];

    protected $casts = [
        'changes' => 'array',
    ];

    public static function record($action, $description, $model = null, $modelId = null, $changes = null)
    {
        static::create([
            'action' => $action,
            'model' => $model,
            'model_id' => $modelId,
            'description' => $description,
            'changes' => $changes,
            'admin_email' => auth()->user()?->email ?? 'système',
        ]);
    }
}