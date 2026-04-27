<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GoalAttachment extends Model
{
    use HasFactory;

    protected $table = 'goal_attachments';

    protected $fillable = [
        'goal_id',
        'goal_day_id',
        'user_id',
        'file_path',
        'mime_type',
        'original_name',
    ];

    public function goal()
    {
        return $this->belongsTo(Goal::class, 'goal_id', 'id');
    }

    public function goalDay()
    {
        return $this->belongsTo(GoalDay::class, 'goal_day_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
