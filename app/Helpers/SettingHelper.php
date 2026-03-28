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