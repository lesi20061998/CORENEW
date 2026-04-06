<?php

namespace App\Widgets\Types;

use App\Models\Widget as WidgetModel;
use App\Widgets\BaseWidget;

class ChoosingReasonWidget extends BaseWidget
{
    public static string $label       = 'Why Choose Us';
    public static string $description = 'Hiển thị các khối lý do/dịch vụ (01 Thức phẩm sạch, 02...)';
    public static string $icon        = 'fa-solid fa-thumbs-up';

    public static function fields(): array
    {
        return [
            ['key' => 'title', 'label' => 'Tiêu đề', 'type' => 'text', 'default' => 'Tại Sao Chọn Chúng Tôi?'],
            ['key' => 'subtitle', 'label' => 'Mô tả ngắn', 'type' => 'text', 'default' => ''],
            ['key' => 'items', 'label' => 'Tính năng', 'type' => 'repeater',
                'fields' => [
                    ['key' => 'title', 'label' => 'Tiêu đề', 'type' => 'text', 'col' => 'col-md-3'],
                    ['key' => 'desc', 'label' => 'Mô tả', 'type' => 'text', 'col' => 'col-md-6'],
                    ['key' => 'icon', 'label' => 'Ảnh Icon (SVG/PNG)', 'type' => 'image', 'col' => 'col-md-3'],
                ]
            ],
        ];
    }

    public static function render(array $config, WidgetModel $widget): string
    {
        return static::view('widgets.types.choosing_reason', compact('config', 'widget'));
    }
}
