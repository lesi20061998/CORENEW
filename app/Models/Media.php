<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    protected $fillable = ['name', 'file_name', 'mime_type', 'path', 'disk', 'size', 'width', 'height', 'alt', 'folder', 'uploaded_by'];

    // Default root folders for the media manager
    public static array $rootFolders = [
        'Chung'       => ['icon' => 'fa-folder',       'color' => '#64748b'],
        'Sản phẩm'    => ['icon' => 'fa-box-open',     'color' => '#f59e0b'],
        'Bài viết'    => ['icon' => 'fa-newspaper',    'color' => '#10b981'],
        'Trang tĩnh'  => ['icon' => 'fa-file-lines',   'color' => '#3b82f6'],
        'Banner'      => ['icon' => 'fa-image',         'color' => '#8b5cf6'],
        'Danh mục'    => ['icon' => 'fa-layer-group',  'color' => '#ec4899'],
        'Tài liệu'    => ['icon' => 'fa-file-pdf',     'color' => '#ef4444'],
    ];

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
