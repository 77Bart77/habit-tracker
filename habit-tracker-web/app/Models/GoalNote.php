<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoalNote extends Model
{
    protected $table = 'goal_notes';
    public $timestamps = false; 

    protected $fillable = [
        'goal_id',
        'content',
       
    ];

    // N:1 — notatka należy do konkretnego celu
    public function goal()
    {
        return $this->belongsTo(Goal::class, 'goal_id', 'id');
    }
}
