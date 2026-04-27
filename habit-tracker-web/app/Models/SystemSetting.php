<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    protected $table = 'system_settings';

    // W bazie: id, key, value, description, created_at, updated_at
    // → timestamps zostają włączone
    protected $fillable = [
        'key',          // nazwa ustawienia, np. "daily_reminder_time"
        'value',        // wartość, np. "08:00"
        'description',  // opis ustawienia
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Helper — szybkie pobranie ustawienia po kluczu
     */
    public static function getValue(string $key, $default = null)
    {
        return static::where('key', $key)->value('value') ?? $default;
    }

    /**
     * Helper — zapis lub aktualizacja ustawienia
     */
    public static function setValue(string $key, $value, ?string $description = null)
    {
        return static::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'description' => $description]
        );
    }
}
