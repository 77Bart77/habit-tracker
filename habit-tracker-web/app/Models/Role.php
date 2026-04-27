<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';
    protected $fillable = ['name', 'label']; // timestamps są domyślne

    // M:N — rola ↔ użytkownicy przez 'user_roles' (pivot ma tylko created_at)
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_roles', 'role_id', 'user_id')
                    ->withPivot('created_at'); // bez withTimestamps()
    }
}
