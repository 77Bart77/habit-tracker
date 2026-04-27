<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoalLike extends Model
{
    protected $table = 'goal_likes';

    // masz tylko created_at (bez updated_at)
    public $timestamps = false;

    protected $fillable = [
        'goal_id',
        'user_id',
        'created_at',
    ];

    public function goal()
    {
        return $this->belongsTo(Goal::class, 'goal_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
