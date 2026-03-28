<?php

namespace App\Widgets\Types;

use App\Models\Widget as WidgetModel;
use App\Widgets\BaseWidget;

/**
 * Widget: Promo Banners - Banner khuyến mãi dạng card có ảnh nền
 * Map từ: .category-feature-area (index.html - 4 card với bg_image)
 */
class PromoBannersWidget extends BaseWidget
{
    public static string $label       = 'Banner khuyến mãi';
    public static string $description = 'Các banner khuyến mãi dạng card với ảnh nền và nút CTA';
    public static string $icon        = 'fa-solid fa-rectangle-ad';

    public static function fields(): array
    {
        return [
            ['key' => 'columns', 'label' => 'Số cột', 'type' => 'select',
                'options' => ['2' => '2 cột', '3' => '3 cột', '4' => '4 cột'],
                'default' => '4'],
            ['key' => 'items', 'label' => 'Banners', 'type' => 'repeater', 'default' => [],
                'fields' => [
                    ['key' => 'badge',    'label' => 'Badge nhỏ',    'type' => 'text'],
                    ['key' => 'title',    'label' => 'Tiêu đề',      'type' => 'text'],
                    ['key' => 'subtitle', 'label' => 'Phụ đề',       'type' => 'text'],
                    ['key' => 'image',    'label' => 'Ảnh nền',      'type' => 'image'],
                    ['key' => 'btn_text', 'label' => 'Nút CTA',      'type' => 'text', 'default' => 'Mua ngay'],
                    ['key' => 'btn_link', 'label' => 'Link',         'type' => 'text', 'default' => '/shop'],
                    ['key' => 'bg_class', 'label' => 'CSS class nền','type' => 'text'],
                ],
            ],
        ];
    }

    public static function render(array $config, WidgetModel $widget): string
    {
        return static::view('widgets.types.promo_banners', ['config' => $config, 'widget' => $widget]);
    }
}
