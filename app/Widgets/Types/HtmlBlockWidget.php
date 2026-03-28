<?php

namespace App\Widgets\Types;

use App\Models\Widget as WidgetModel;
use App\Widgets\BaseWidget;

/**
 * Widget: HTML Block - Khối HTML tùy chỉnh
 * Dùng cho các section đặc biệt không có type riêng
 */
class HtmlBlockWidget extends BaseWidget
{
    public static string $label       = 'Khối HTML';
    public static string $description = 'Nhúng HTML tùy chỉnh vào bất kỳ vị trí nào';
    public static string $icon        = 'fa-solid fa-code';

    public static function fields(): array
    {
        return [
            ['key' => 'content', 'label' => 'Nội dung HTML', 'type' => 'html', 'default' => ''],
            ['key' => 'css',     'label' => 'CSS tùy chỉnh', 'type' => 'textarea', 'default' => ''],
        ];
    }

    public static function render(array $config, WidgetModel $widget): string
    {
        return static::view('widgets.types.html_block', ['config' => $config, 'widget' => $widget]);
    }
}
