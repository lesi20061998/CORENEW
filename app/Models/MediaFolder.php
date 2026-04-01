<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaFolder extends Model
{
    protected $fillable = ['name', 'slug', 'parent_id', 'icon', 'color', 'sort_order'];

    public function parent()
    {
        return $this->belongsTo(MediaFolder::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(MediaFolder::class, 'parent_id')->orderBy('sort_order')->orderBy('name');
    }

    public function media()
    {
        return $this->hasMany(Media::class, 'folder_id');
    }
}
