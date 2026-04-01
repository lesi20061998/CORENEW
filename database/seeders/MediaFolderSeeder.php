<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MediaFolderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $folders = [
            ['name' => 'Chung',       'icon' => 'fa-folder',      'color' => '#64748b'],
            ['name' => 'Sản phẩm',    'icon' => 'fa-box-open',    'color' => '#f59e0b'],
            ['name' => 'Bài viết',    'icon' => 'fa-newspaper',   'color' => '#10b981'],
            ['name' => 'Trang tĩnh',  'icon' => 'fa-file-lines',  'color' => '#3b82f6'],
            ['name' => 'Banner',      'icon' => 'fa-image',       'color' => '#8b5cf6'],
            ['name' => 'Danh mục',    'icon' => 'fa-layer-group', 'color' => '#ec4899'],
            ['name' => 'Tài liệu',    'icon' => 'fa-file-pdf',    'color' => '#ef4444'],
        ];

        foreach ($folders as $idx => $f) {
            \App\Models\MediaFolder::updateOrCreate(
                ['name' => $f['name']],
                [
                    'icon'       => $f['icon'],
                    'color'      => $f['color'],
                    'parent_id'  => null,
                    'sort_order' => $idx * 10
                ]
            );
        }
    }
}
