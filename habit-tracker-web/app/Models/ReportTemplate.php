<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportTemplate extends Model
{
    protected $table = 'report_templates';

    // W bazie: id, name, description, created_at, updated_at
    // → timestamps zostają włączone
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * 1:N — szablon ma wiele raportów (powiązanie przez report_template_id)
     */
    public function reports()
    {
        return $this->hasMany(Report::class, 'report_template_id', 'id');
    }
}
