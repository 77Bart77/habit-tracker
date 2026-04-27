<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = 'posts';

    // Kolumny, które mogą być masowo wypełniane (fillable)
    protected $fillable = [
        'user_id',
        'title',
        'content',
    ];

    // timestamps domyślnie są włączone (bo tabela ma created_at i updated_at)

    /**
     * N:1 — właściciel posta (user_id → users.id)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * 1:N — komentarze pod postem
     */
    public function comments()
    {
        return $this->hasMany(Comment::class, 'post_id', 'id');
    }

    /**
     * 1:N — polubienia posta
     */
    public function likes()
    {
        return $this->hasMany(Like::class, 'post_id', 'id');
    }
}
