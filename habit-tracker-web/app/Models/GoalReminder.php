<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoalReminder extends Model
{
    protected $table = 'goal_reminders';

    // Brak updated_at w tabeli
    public $timestamps = false;

    protected $fillable = [
        'goal_id',
        'reminder_time',
        'message',
        'is_active',
        
    ];

    protected $casts = [
        'reminder_time' => 'datetime',
        'is_active'     => 'boolean',
    ];

    // N:1 — przypomnienie należy do konkretnego celu
    public function goal()
    {
        return $this->belongsTo(Goal::class, 'goal_id', 'id');
    }

    //  Szybkie filtry do wykorzystania później
    public function scopeActive($q) { return $q->where('is_active', true); }
    public function scopeUpcoming($q) { return $q->where('reminder_time', '>=', now()); }
}
