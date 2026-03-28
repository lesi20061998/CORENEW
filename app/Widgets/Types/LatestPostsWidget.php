<?php

namespace App\Widgets\Types;

use App\Models\Widget as WidgetModel;
use App\Models\Post;
use App\Widgets\BaseWidget;

/**
 * Widget: Latest Posts - Bài viết mới nhất
 * Map từ: .blog-area-start (index.html)
 */
class LatestPostsWidget extends BaseWidget
{
    public static string $label       = 'Bài viết mới nhất';
    public static string $description = 'Hiển thị các bài viết blog mới nhất dạng grid';
    public static string $icon        = 'fa-solid fa-newspaper';

    public static function fields(): array
    {
        return [
            ['key' => 'title',        'label' => 'Tiêu đề section',  'type' => 'text',   'default' => 'Tin tức & Bài viết'],
            ['key' => 'limit',        'label' => 'Số bài viết',      'type' => 'number', 'default' => 4],
            ['key' => 'columns',      'label' => 'Số cột',           'type' => 'select',
                'options' => ['2' => '2 cột', '3' => '3 cột', '4' => '4 cột'],
                'default' => '4'],
            ['key' => 'show_excerpt', 'label' => 'Hiện tóm tắt',    'type' => 'toggle', 'default' => false],
            ['key' => 'show_btn',     'label' => 'Hiện nút xem thêm','type' => 'toggle', 'default' => false],
            ['key' => 'btn_text',     'label' => 'Text nút',         'type' => 'text',   'default' => 'Xem tất cả bài viết'],
            ['key' => 'btn_link',     'label' => 'Link nút',         'type' => 'text',   'default' => '/blog'],
        ];
    }

    public static function render(array $config, WidgetModel $widget): string
    {
        $posts = Post::where('status', 'published')
            ->latest('published_at')
            ->limit((int)($config['limit'] ?? 4))
            ->get();

        return static::view('widgets.types.latest_posts', [
            'config' => $config,
            'widget' => $widget,
            'posts'  => $posts,
        ]);
    }
}
