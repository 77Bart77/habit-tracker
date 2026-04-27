<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    protected $table = 'achievements';
    public $timestamps = false; // brak updated_at

    protected $fillable = [
        'name',
        'description',
        'icon',
        'points_reward',
        // 'created_at'
    ];

    protected $casts = [
        'points_reward' => 'integer',
    ];

    // M:N — użytkownicy, którzy zdobyli dane osiągnięcie
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_achievements', 'achievement_id', 'user_id')
                    ->withPivot('earned_at')
                    ->withTimestamps(false); // pivot bez updated_at
    }
}
