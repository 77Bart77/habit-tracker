<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Statistic extends Model
{
    protected $table = 'statistics';

    // W tabeli jest tylko updated_at (brak created_at) → wyłączamy timestamps
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'total_goals',
        'completed_goals',
        'active_challenges',
        'completed_challenges',
        'total_likes',
        'total_comments',
        'updated_at', // będziemy ustawiali ręcznie przy przeliczeniu
    ];

    protected $casts = [
        'total_goals'           => 'integer',
        'completed_goals'       => 'integer',
        'active_challenges'     => 'integer',
        'completed_challenges'  => 'integer',
        'total_likes'           => 'integer',
        'total_comments'        => 'integer',
        'updated_at'            => 'datetime',
    ];

    /**
     * N:1 — statystyki należą do użytkownika
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
