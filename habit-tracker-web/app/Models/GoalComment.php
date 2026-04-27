<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoalComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'goal_id',
        'user_id',
        'content',
    ];

    // Relacje
    public function goal()
    {
        return $this->belongsTo(Goal::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

