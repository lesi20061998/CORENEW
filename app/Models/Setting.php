<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'group',
        'section',
        'sort_order',
        'type',
        'label',
        'description',
        'options',
    ];

    protected $casts = [
        'value' => 'string'
    ];

    /**
     * Boot the model to handle cache clearing.
     */
    protected static function boot()
    {
        parent::boot();

        static::saved(function () {
            static::clearCache();
        });

        static::deleted(function () {
            static::clearCache();
        });
    }

    /**
     * Clear the settings cache.
     */
    public static function clearCache()
    {
        \Illuminate\Support\Facades\Cache::forget('all_settings_cache');
    }

    /**
     * Get all settings from cache.
     */
    public static function getAllCached()
    {
        return \Illuminate\Support\Facades\Cache::rememberForever('all_settings_cache', function () {
            return static::all()->pluck('value', 'key');
        });
    }



    public static function get($key, $default = null)
    {
        $allSettings = static::getAllCached();

        // Direct match
        if ($allSettings->has($key)) {
            return $allSettings->get($key);
        }

        // Dot notation (e.g., social.facebook) -> main key 'social', subkey 'facebook'
        if (str_contains($key, '.')) {
            [$mainKey, $subKey] = explode('.', $key, 2);
            if ($allSettings->has($mainKey)) {
                $mainValue = $allSettings->get($mainKey);
                // If it's a JSON string, it might need decoding or it's already an array if fetched via model normally.
                // But pluck('value', 'key') gives raw values.
                // We need to decode if it is JSON.
                $item = static::where('key', $mainKey)->first();
                if ($item && $item->type === 'json') {
                    return data_get($item->value, $subKey, $default);
                }
            }
        }

        return $default;
    }

    public static function set($key, $value, $group = 'general', $type = 'text')
    {
        // Dot notation set (e.g., set('social.facebook', '...'))
        if (str_contains($key, '.')) {
            [$mainKey, $subKey] = explode('.', $key, 2);
            $setting = static::where('key', $mainKey)->first();
            if ($setting && $setting->type === 'json') {
                $currentValue = $setting->value;
                data_set($currentValue, $subKey, $value);
                $setting->update(['value' => $currentValue]);
                return $setting;
            }
        }

        return static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'group' => $group,
                'type' => $type
            ]
        );
    }
}