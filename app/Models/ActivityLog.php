<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'activity',
        'module',
        'model_id',
        'ip_address'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function log($activity, $module = null, $modelId = null)
    {
        return static::create([
            'user_id' => auth()->id(),
            'activity' => $activity,
            'module' => $module,
            'model_id' => $modelId,
            'ip_address' => request()->ip()
        ]);
    }
}
