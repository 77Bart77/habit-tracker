<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class Friendship extends Model
{
    protected $table = 'friendships';

    // W bazie: id, user_id, friend_id, status, created_at (bez updated_at)
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'friend_id',
        'status',     // np. 'pending', 'accepted', 'blocked'
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * N:1 — użytkownik, który wysłał zaproszenie
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * N:1 — użytkownik, który otrzymał zaproszenie
     */
    public function friend()
    {
        return $this->belongsTo(User::class, 'friend_id', 'id');
    }

    /**
     * Zakres — zaakceptowane relacje (status = accepted)
     */
    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }

    /**
     * Zakres — oczekujące zaproszenia (status = pending)
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
