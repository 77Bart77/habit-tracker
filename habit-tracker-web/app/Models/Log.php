<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $table = 'logs';
    public $timestamps = false; // tabela ma tylko created_at

    protected $fillable = [
        'user_id',
        'action',
        'ip_address',
        'user_agent',
        // 'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // N:1 — kto wykonał akcję
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // (opcjonalnie) szybkie filtry
    public function scopeForUser($q, int $userId) { return $q->where('user_id', $userId); }
    public function scopeAction($q, string $action) { return $q->where('action', $action); }
    public function scopeToday($q) { return $q->whereDate('created_at', now()->toDateString()); }
}
