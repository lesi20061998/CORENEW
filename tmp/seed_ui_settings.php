<?php

use App\Models\Setting;

$icons = config('ui.icons', []);
$images = config('ui.images', []);

echo "Seeding Icons...\n";
foreach ($icons as $name => $class) {
    if ($name === 'fallback') continue;
    
    $key = "icon_{$name}";
    // Only set if not already set by admin
    if (!Setting::where('key', $key)->exists()) {
        Setting::set($key, $class, 'appearance', 'text');
        echo "Set $key -> $class\n";
    }
}

echo "\nSeeding Images...\n";
foreach ($images as $name => $path) {
    if ($name === 'fallback') continue;
    
    $key = match($name) {
        'logo' => 'site_logo',
        'favicon' => 'site_favicon',
        default => "image_{$name}"
    };
    
    if (!Setting::where('key', $key)->exists()) {
        Setting::set($key, $path, 'appearance', 'text');
        echo "Set $key -> $path\n";
    }
}

echo "\nDone!\n";
