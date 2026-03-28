<?php

namespace App\Widgets\Types;

use App\Models\Widget as WidgetModel;
use App\Widgets\BaseWidget;

/**
 * Widget: Features Bar - Thanh tính năng nổi bật
 * Map từ: .rts-feature-area (index.html)
 */
class FeaturesBarWidget extends BaseWidget
{
    public static string $label       = 'Thanh tính năng';
    public static string $description = 'Hiển thị các tính năng: giao hàng, đổi trả, giá tốt, hỗ trợ 24/7';
    public static string $icon        = 'fa-solid fa-shield-check';

    public static function fields(): array
    {
        return [
            ['key' => 'items', 'label' => 'Tính năng', 'type' => 'repeater', 'default' => [],
                'fields' => [
                    ['key' => 'icon',     'label' => 'Icon (FA class)',  'type' => 'text', 'default' => 'fa-solid fa-truck'],
                    ['key' => 'title',    'label' => 'Tiêu đề',          'type' => 'text'],
                    ['key' => 'subtitle', 'label' => 'Mô tả phụ',        'type' => 'text'],
                ],
            ],
        ];
    }

    public static function render(array $config, WidgetModel $widget): string
    {
        return static::view('widgets.types.features_bar', ['config' => $config, 'widget' => $widget]);
    }
}
