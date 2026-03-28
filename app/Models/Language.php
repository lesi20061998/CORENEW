<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $fillable = ['code','name','native_name','flag','is_default','is_active','sort_order'];

    protected $casts = ['is_default' => 'boolean', 'is_active' => 'boolean'];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    public static function getDefault(): ?self
    {
        return static::where('is_default', true)->first();
    }

    public static function getActiveCodes(): array
    {
        return static::active()->pluck('code')->toArray();
    }
}
