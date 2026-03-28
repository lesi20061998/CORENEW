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

    public function getValueAttribute($value)
    {
        return match($this->type) {
            'boolean' => (bool) $value,
            'json' => json_decode($value, true),
            default => $value
        };
    }

    public function setValueAttribute($value)
    {
        $this->attributes['value'] = match($this->type) {
            'boolean' => $value ? '1' : '0',
            'json' => json_encode($value),
            default => $value
        };
    }

    public static function get($key, $default = null)
    {
        // Direct match
        $setting = static::where('key', $key)->first();
        if ($setting) {
            return $setting->value;
        }

        // Dot notation (e.g., social.facebook) -> main key 'social', subkey 'facebook'
        if (str_contains($key, '.')) {
            [$mainKey, $subKey] = explode('.', $key, 2);
            $setting = static::where('key', $mainKey)->first();
            if ($setting && $setting->type === 'json') {
                return data_get($setting->value, $subKey, $default);
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