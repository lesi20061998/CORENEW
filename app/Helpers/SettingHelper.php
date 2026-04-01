<?php

if (!function_exists('setting')) {
    function setting($key, $default = null)
    {
        return \App\Models\Setting::get($key, $default);
    }
}

if (!function_exists('get_setting')) {
    function get_setting($key, $default = null)
    {
        return \App\Models\Setting::get($key, $default);
    }
}

if (!function_exists('set_setting')) {
    function set_setting($key, $value, $group = 'general', $type = 'text')
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