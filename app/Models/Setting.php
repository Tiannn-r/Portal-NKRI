<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
    ];

    /**
     * Ambil nilai pengaturan berdasarkan key (dengan cache memori).
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $all = \Illuminate\Support\Facades\Cache::remember('portal_all_settings', 3600, function () {
            return static::all()->keyBy('key');
        });

        $setting = $all->get($key);

        if (!$setting) {
            return $default;
        }

        return match ($setting->type) {
            'boolean' => filter_var($setting->value, FILTER_VALIDATE_BOOLEAN),
            'integer' => (int) $setting->value,
            'json' => json_decode($setting->value, true),
            default => $setting->value,
        };
    }

    /**
     * Simpan nilai pengaturan dan bersihkan cache.
     */
    public static function set(string $key, mixed $value, string $type = 'string'): static
    {
        \Illuminate\Support\Facades\Cache::forget('portal_all_settings');

        $storedValue = match ($type) {
            'boolean' => $value ? '1' : '0',
            'json' => is_string($value) ? $value : json_encode($value),
            default => (string) $value,
        };

        return static::updateOrCreate(
            ['key' => $key],
            ['value' => $storedValue, 'type' => $type]
        );
    }
}

