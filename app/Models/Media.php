<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    protected $fillable = ['name', 'file_name', 'mime_type', 'path', 'disk', 'size', 'width', 'height', 'alt', 'folder_id', 'uploaded_by'];

    public function folder()
    {
        return $this->belongsTo(MediaFolder::class, 'folder_id');
    }

    // Hierarchical folders managed via MediaFolder model

    public function getUrlAttribute(): string
    {
        return Storage::disk($this->disk)->url($this->path);
    }

    public function isImage(): bool
    {
        return str_starts_with($this->mime_type, 'image/');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
