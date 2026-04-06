<?php

/**
 * Retrieve a setting value (cached — 1 DB query per request).
 * Supports dot-notation for JSON fields: setting('social_links.facebook')
 */
if (!function_exists('setting')) {
    function setting(string $key, mixed $default = null): mixed
    {
        $cached = \App\Models\Setting::getAllCached();

        // Direct match
        if ($cached->has($key)) {
            $raw = $cached->get($key);
            // Auto-decode JSON strings
            if (is_string($raw) && str_starts_with(ltrim($raw), '{') || (is_string($raw) && str_starts_with(ltrim($raw), '['))) {
                $decoded = json_decode($raw, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    return $decoded;
                }
            }
            return $raw;
        }

        // Dot-notation: 'social_links.facebook'
        if (str_contains($key, '.')) {
            [$mainKey, $subKey] = explode('.', $key, 2);
            if ($cached->has($mainKey)) {
                $raw = $cached->get($mainKey);
                $arr = is_array($raw) ? $raw : json_decode($raw, true);
                if (is_array($arr)) {
                    return data_get($arr, $subKey, $default);
                }
            }
        }

        return $default;
    }
}

/** Alias for backward compatibility */
if (!function_exists('get_setting')) {
    function get_setting(string $key, mixed $default = null): mixed
    {
        return setting($key, $default);
    }
}

/** Decode a JSON setting and return as array */
if (!function_exists('setting_json')) {
    function setting_json(string $key, array $default = []): array
    {
        $v = setting($key);
        if (is_array($v)) return $v;
        if (is_string($v)) {
            $decoded = json_decode($v, true);
            return json_last_error() === JSON_ERROR_NONE ? $decoded : $default;
        }
        return $default;
    }
}

/**
 * Check if a specific SEO feature is enabled for a content type.
 *
 * Usage: seo_enabled('product', 'og_image')
 * Reads from settings key: seo_product_config (JSON)
 * Falls back to TRUE if not configured.
 *
 * @param string $type    'product' | 'post' | 'page'
 * @param string $feature 'meta_title'|'meta_description'|'meta_keywords'|'og'|'twitter'|'canonical'|'schema'
 */
if (!function_exists('seo_enabled')) {
    function seo_enabled(string $type, string $feature): bool
    {
        $config = setting_json("seo_{$type}_config", []);
        // If the key exists and is explicitly false, disable it
        if (isset($config[$feature])) {
            return (bool) $config[$feature];
        }
        // Default: ON (safe fallback)
        return true;
    }
}

if (!function_exists('set_setting')) {
    function set_setting(string $key, mixed $value, string $group = 'general', string $type = 'text'): mixed
    {
        return \App\Models\Setting::set($key, $value, $group, $type);
    }
}

if (!function_exists('change_locale_url')) {
    function change_locale_url($locale)
    {
        $segments = request()->segments();
        $activeCodes = \Illuminate\Support\Facades\Cache::remember('active_language_codes', 3600, function() {
            return \App\Models\Language::where('is_active', true)->pluck('code')->toArray();
        });
        
        $activeCodesLower = array_map('strtolower', $activeCodes);
        
        // Remove existing locale from segments if present
        if (isset($segments[0]) && in_array(strtolower($segments[0]), $activeCodesLower)) {
            array_shift($segments);
        }
        
        $defaultLocale = \Illuminate\Support\Facades\Cache::remember('default_language_code', 3600, function() {
            return \App\Models\Language::where('is_default', true)->value('code') ?: config('app.fallback_locale');
        });

        // Add prefix if not default
        if (strtolower($locale) !== strtolower($defaultLocale)) {
            array_unshift($segments, strtolower($locale));
        }
        
        $queryString = request()->getQueryString();
        $path = implode('/', $segments);
        return url($path) . ($queryString ? '?' . $queryString : '');
    }
}