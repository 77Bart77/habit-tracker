<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChallengeReward extends Model
{
    protected $table = 'challenge_rewards';

    // Najczęściej w tej tabeli nie ma updated_at
    public $timestamps = false;

    protected $fillable = [
        'challenge_id',  // FK → challenges.id
        'user_id',       // (jeśli nagroda przypisywana jest konkretnemu użytkownikowi)
        'name',          // nazwa nagrody / tytuł
        'points',        // liczba punktów / wartość nagrody
        'issued_at',     // kiedy przyznano
        // 'created_at'   // zwykle nie dodajemy do fillable
    ];

    protected $casts = [
        'points'    => 'integer',
        'issued_at' => 'datetime',
    ];

    /**
     * N:1 — nagroda należy do wyzwania
     */
    public function challenge()
    {
        return $this->belongsTo(Challenge::class, 'challenge_id', 'id');
    }

    /**
     * N:1 — (opcjonalnie) nagroda przypisana do użytkownika
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
