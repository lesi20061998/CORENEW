<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

use App\Traits\HasTranslations;

class Category extends Model
{
    use HasFactory, HasTranslations;

    public array $translatableFields = ['name', 'description', 'content', 'meta_title', 'meta_description'];

    protected $fillable = [
        'name', 'slug', 'description', 'content', 'image', 'icon',
        'parent_id', 'type', 'sort_order', 'is_active',
        'meta_title', 'meta_description', 'meta_keywords',
    ];

    protected $casts = ['is_active' => 'boolean'];

    protected static function boot()
    {
        parent::boot();
        static::creating(fn($m) => $m->slug ??= Str::slug($m->name));
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('sort_order');
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'category_product');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeRoots($query)
    {
        return $query->whereNull('parent_id');
    }
}
