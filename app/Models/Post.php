<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\HasTranslations;

class Post extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    public array $translatableFields = ['title', 'content', 'excerpt', 'meta_title', 'meta_description'];

    protected $fillable = [
        'title', 'slug', 'content', 'excerpt', 'thumbnail',
        'category_id', 'author_id', 'status', 'is_featured', 'published_at',
        'meta_title', 'meta_description', 'meta_keywords', 'canonical_url', 
        'seo_focus_keyword', 'robots_meta', 'schema_json', 'has_toc',
    ];

    protected $casts = [
        'is_featured'  => 'boolean',
        'has_toc'      => 'boolean',
        'published_at' => 'datetime',
        'schema_json'  => 'array',
        'robots_meta'  => 'array',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(fn($m) => $m->slug ??= Str::slug($m->title));
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /** Auto-generate meta title from title if not set */
    public function getMetaTitleAttribute($value): string
    {
        return $value ?: $this->title;
    }

    /** Auto-generate excerpt from content if not set */
    public function getExcerptAttribute($value): string
    {
        return $value ?: Str::limit(strip_tags($this->content), 160);
    }
}
