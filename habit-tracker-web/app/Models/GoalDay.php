<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Goal;
use App\Models\GoalAttachment;


class GoalDay extends Model
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_DONE    = 'done';
    public const STATUS_SKIPPED = 'skipped';
    
    protected $table = 'goal_days';
    public $timestamps = false; 

    protected $fillable = [
        'goal_id',
        'date',
        'status',
        'note',
        'created_at',

        
    ];

   protected $casts = [
    'date' => 'date:Y-m-d',
];


    // N:1 — day należy do goal
    public function goal()
    {
        return $this->belongsTo(Goal::class, 'goal_id', 'id');
    }

    public function attachments()
{
    return $this->hasMany(GoalAttachment::class, 'goal_day_id', 'id');
}

}
