<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\GoalAttachment;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Models\GoalDay;
use App\Models\GoalComment;
use App\Models\GoalLike;
use App\Models\User;
use App\Models\GoalCategory;
use App\Models\ProRequest;






class Goal extends Model
{
    public const STATUS_ACTIVE   = 'active';
    public const STATUS_FINISHED = 'finished';
    public const STATUS_PAUSED   = 'paused';

    protected $table = 'goals';

    protected $fillable = [
        'user_id',
        'goal_category_id',
        'title',
        'description',

        'start_date',
        'end_date',
        'status',
        'is_public',
        'interval_days',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'is_public'  => 'boolean',
    ];

    // progress
    protected $appends = ['progress_percent'];

    //procenty celu
    protected function progressPercent(): Attribute
    {
        return Attribute::make(
            get: function () {
                
                $days = $this->relationLoaded('days')
                    ? $this->days
                    : $this->days()->get();

                $total = $days->count();
                if ($total === 0) {
                    return 0;
                }

                $done = $days
                    ->where('status', GoalDay::STATUS_DONE)
                    ->count();

                return (int) round($done / $total * 100);
            },
        );
    }


    // N:1 — właściciel celu
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // N:1 — kategoria celu
    public function category()
    {
        return $this->belongsTo(GoalCategory::class, 'goal_category_id', 'id');
    }

    // 1:N — rekordy dzienne realizacji
    public function days()
    {
        return $this->hasMany(GoalDay::class, 'goal_id', 'id');
    }

    public function attachments()
    {
        return $this->hasMany(GoalAttachment::class, 'goal_id', 'id');
    }

    public function likes()
    {
        return $this->hasMany(GoalLike::class, 'goal_id', 'id');
    }


    public function comments()
    {
        return $this->hasMany(GoalComment::class, 'goal_id', 'id');
    }

    public function proRequest()
    {
        return $this->hasOne(ProRequest::class, 'goal_id', 'id');
    }
}
