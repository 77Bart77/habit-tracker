<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';

    // Jeśli tabela ma tylko created_at (bez updated_at)
    public $timestamps = false;

    protected $fillable = [
        'user_id',      // odbiorca powiadomienia
        'title',        // tytuł lub typ (np. "Nowe wyzwanie", "Cel ukończony")
        'message',      // treść powiadomienia
        'is_read',      // czy przeczytane
        'created_at',   // data wysłania
    ];

    protected $casts = [
        'is_read'    => 'boolean',
        'created_at' => 'datetime',
    ];

    /**
     * N:1 — powiadomienie należy do użytkownika
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
