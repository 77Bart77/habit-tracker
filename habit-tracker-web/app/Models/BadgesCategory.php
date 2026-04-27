<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BadgesCategory extends Model
{
    protected $table = 'badges_categories';
    public $timestamps = false; // w tabeli nie ma created_at / updated_at

    protected $fillable = [
        'name',         // nazwa kategorii odznak, np. "Motywacja", "Zdrowie"
        'description',  // opis kategorii
    ];

    // (opcjonalnie) jeśli w przyszłości dodasz powiązania z achievements lub badges:
    // public function achievements()
    // {
    //     return $this->hasMany(Achievement::class, 'badge_category_id', 'id');
    // }
}
