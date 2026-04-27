<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChallengeProgress extends Model
{
    protected $table = 'challenge_progress';

    // Brak updated_at → timestamps wyłączone
    public $timestamps = false;

    protected $fillable = [
        'challenge_id',
        'user_id',
        'progress_value',
        'note',
        // 'created_at' — pomijamy w fillable
    ];

    protected $casts = [
        'progress_value' => 'integer',
        'created_at'     => 'datetime',
    ];

    /**
     * N:1 — rekord należy do wyzwania
     */
    public function challenge()
    {
        return $this->belongsTo(Challenge::class, 'challenge_id', 'id');
    }

    /**
     * N:1 — rekord należy do użytkownika
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
