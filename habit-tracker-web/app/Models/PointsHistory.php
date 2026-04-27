<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class PointsHistory extends Model
{
    protected $table = 'points_history';

    // w tabeli jest created_at, ale nie ma updated_at
    public $timestamps = false;

    protected $fillable = [
        'user_id',        // kto dostaje punkty
        'actor_user_id',
        
        'event_date',     // data zdarzenia (np. "dziś")
        'action',         // np. "done_today", "goal_completed_80", "goal_completed_100", "like", "verified_80"
        'related_type',   // np. "goal", "challenge", "like"
        'related_id',     // id powiązanego rekordu
        'points',
        'created_at',     // kiedy zapisano log (ustawimy w serwisie)
    ];

    protected $casts = [
        'points'     => 'integer',
        'event_date' => 'date',
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

     public function actor()
    {
        return $this->belongsTo(User::class, 'actor_user_id', 'id');
    }

    // scopes
    public function scopeForUser(Builder $q, int $userId): Builder
    {
        return $q->where('user_id', $userId);
    }

    public function scopeForDate(Builder $q, $date): Builder
    {
        return $q->whereDate('event_date', $date);
    }
}
