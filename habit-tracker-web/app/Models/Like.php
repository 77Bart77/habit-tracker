<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $table = 'likes';

    // Tabela ma tylko created_at — brak updated_at
    public $timestamps = false;

    // Kolumny, które mogą być masowo wypełniane
    protected $fillable = [
        'user_id',
        'post_id',
        'created_at',
    ];

    /**
     * N:1 — użytkownik, który polubił post (user_id → users.id)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * N:1 — post, który został polubiony (post_id → posts.id)
     */
    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id', 'id');
    }
}
