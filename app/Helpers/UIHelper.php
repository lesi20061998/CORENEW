<?php

/**
 * Resolve an icon class based on its semantic name defined in config/ui.php
 */
if (!function_exists('resolve_icon')) {
    function resolve_icon(string $name, ?string $default = null): string
    {
        // 1. Check DB settings first (Integration with Admin Settings)
        $dbKey = "icon_{$name}";
        $dbValue = setting($dbKey);
        if ($dbValue) {
            return $dbValue;
        }

        // 2. Check config/ui.php
        $mapping = config('ui.icons', []);
        
        // Match from config
        if (isset($mapping[$name])) {
            return $mapping[$name];
        }

        // Return provided default or global fallback
        return $default ?: ($mapping['fallback'] ?? 'fas fa-question-circle');
    }
}

/**
 * Resolve an image URL based on its semantic name defined in config/ui.php or DB
 */
if (!function_exists('resolve_image')) {
    function resolve_image(string $name, ?string $default = null): string
    {
        // 1. Check DB settings first (Integration with Admin Settings)
        // Logo and Favicon are common
        $dbKey = match($name) {
            'logo' => 'site_logo',
            'favicon' => 'site_favicon',
            default => "image_{$name}"
        };
        
        $dbValue = setting($dbKey);
        if ($dbValue) {
            return (\Illuminate\Support\Str::contains($dbValue, '://')) ? $dbValue : asset($dbValue);
        }

        // 2. Check config/ui.php
        $mapping = config('ui.images', []);
        
        // Match from config
        $path = $name;
        if (isset($mapping[$name])) {
            $path = $mapping[$name];
        }

        // Return path (if starts with http or theme/ already, just return asset)
        // Check for fallback
        if (empty($path)) {
            $path = $mapping['fallback'] ?? 'images/default.png';
        }

        return (\Illuminate\Support\Str::contains($path, '://')) ? $path : asset($path);
    }
}
