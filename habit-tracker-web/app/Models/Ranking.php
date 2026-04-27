<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ranking extends Model
{
    protected $table = 'rankings';

    
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'total_points',
        'level',
        'last_update',
    ];

    protected $casts = [
        'total_points' => 'integer',
        'level'        => 'integer',
        'last_update'  => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
