<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrmSetting extends Model
{
    use HasFactory;

    protected $table = 'crm_settings';

    protected $fillable = [
        'key',
        'value',
    ];

    /**
     * Get setting value by key with optional default
     */
    public static function get(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Set setting value by key
     */
    public static function set(string $key, $value): static
    {
        return static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }
}
