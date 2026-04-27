<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Backup extends Model
{
    protected $table = 'backups';
    public $timestamps = false; // tabela ma tylko created_at

    protected $fillable = [
        'file_name',   // np. backup_2025-11-02_120000.sql
        'file_size',   // w bajtach
        'created_by',  // user_id, który wykonał backup
        // 'created_at', // ustawiane przez DB / ręcznie, nie w fillable
    ];

    protected $casts = [
        'file_size'  => 'integer',
        'created_at' => 'datetime',
    ];

    // N:1 — autor/wykonawca backupu
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
