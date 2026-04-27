<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $table = 'reports';

    // W bazie: id, user_id, title, description, status, created_at, updated_at
    // → timestamps zostają włączone (są obie kolumny)
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * N:1 — raport został utworzony przez użytkownika
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * 1:N — relacja do szablonu raportu (jeśli istnieje powiązanie przez report_template_id)
     */
    public function template()
    {
        return $this->belongsTo(ReportTemplate::class, 'report_template_id', 'id');
    }
}
