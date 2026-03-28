<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Page extends Model
{
    protected $fillable = [
        'title', 'slug', 'content', 'template', 'status', 'sort_order',
        'meta_title', 'meta_description', 'meta_keywords', 'canonical_url', 'seo_focus_keyword', 'robots_meta', 'schema_json',
    ];

    protected $casts = [
        'schema_json' => 'array',
        'robots_meta' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(fn($m) => $m->slug ??= Str::slug($m->title));
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function getMetaTitleAttribute($value): string
    {
        return $value ?: $this->title;
    }

    /**
     * Returns available blade templates from resources/views/templates/
     */
    public static function availableTemplates(): array
    {
        $path = resource_path('views/templates');
        if (!is_dir($path)) return ['default' => 'Default'];

        $templates = ['default' => 'Default'];
        foreach (glob($path . '/*.blade.php') as $file) {
            $key = basename($file, '.blade.php');
            $templates[$key] = ucwords(str_replace(['-', '_'], ' ', $key));
        }
        return $templates;
    }
}
