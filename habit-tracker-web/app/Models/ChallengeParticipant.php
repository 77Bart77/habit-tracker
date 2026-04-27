<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChallengeParticipant extends Model
{
    protected $table = 'challenge_participants';

    // brak updated_at → timestamps wyłączone
    public $timestamps = false;

    protected $fillable = [
        'challenge_id',
        'user_id',
        'joined_at',
        'progress',
        // 'created_at' — pomijamy w fillable
    ];

    protected $casts = [
        'joined_at' => 'datetime',
        'progress'  => 'integer',
    ];

    /**
     * N:1 — uczestnik należy do wyzwania
     */
    public function challenge()
    {
        return $this->belongsTo(Challenge::class, 'challenge_id', 'id');
    }

    /**
     * N:1 — uczestnik to konkretny użytkownik
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
