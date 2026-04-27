<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\Goal;


class GoalCategory extends Model
{
    use HasFactory;

    protected $table = 'goal_categories';

    protected $fillable = [
        'name',
        'color',
        'user_id',
    ];

    protected $casts = [
        'user_id' => 'integer',
    ];

    // Kategoria może należeć do użytkownika (jeśli to jego własna kategoria)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relacja 1:N — kategoria ma wiele celów
    public function goals()
    {
        return $this->hasMany(Goal::class, 'goal_category_id', 'id');
    }
}
