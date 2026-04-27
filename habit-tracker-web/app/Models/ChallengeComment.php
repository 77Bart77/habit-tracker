<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChallengeComment extends Model
{
    protected $table = 'challenge_comments';

    // timestamps są domyślnie włączone

    protected $fillable = [
        'challenge_id',
        'user_id',
        'content',
    ];

    /**
     * N:1 — komentarz należy do wyzwania
     */
    public function challenge()
    {
        return $this->belongsTo(Challenge::class, 'challenge_id', 'id');
    }

    /**
     * N:1 — autor komentarza (użytkownik)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
