<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Challenge extends Model
{
    protected $table = 'challenges';

    
    protected $fillable = [
        'title',
        'description',
        'goal_category_id',
        'created_by',
        'start_date',
        'end_date',
        'is_public',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'is_public'  => 'boolean',
    ];

    /**
     * N:1 — kategoria wyzwania (FK: goal_category_id -> goal_categories.id)
     */
    public function category()
    {
        return $this->belongsTo(GoalCategory::class, 'goal_category_id', 'id');
    }

    /**
     * N:1 — autor/organizator wyzwania (FK: created_by -> users.id)
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    /**
     * 1:N — komentarze w wyzwaniu
     */
    public function comments()
    {
        return $this->hasMany(ChallengeComment::class, 'challenge_id', 'id');
    }

    /**
     * 1:N — uczestnicy wyzwania
     */
    public function participants()
    {
        return $this->hasMany(ChallengeParticipant::class, 'challenge_id', 'id');
    }

    /**
     * 1:N — wpisy progresu (np. procent realizacji)
     */
    public function progressEntries()
    {
        return $this->hasMany(ChallengeProgress::class, 'challenge_id', 'id');
    }

    /**
     * 1:N — nagrody/przyznane punkty w ramach wyzwania
     */
    public function rewards()
    {
        return $this->hasMany(ChallengeReward::class, 'challenge_id', 'id');
    }

    //helper , pobranie oststnich komentarzy 
    public function latestComments()
{
    return $this->comments()->latest()->limit(5);
}

}
