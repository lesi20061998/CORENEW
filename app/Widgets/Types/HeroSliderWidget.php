<?php

namespace App\Widgets\Types;

use App\Models\Widget as WidgetModel;
use App\Widgets\BaseWidget;

/**
 * Widget: Hero Slider - Banner chính trang chủ
 * Map từ: .rts-banner-area-one (index.html) và .rts-banner-area-two (index-two.html)
 */
class HeroSliderWidget extends BaseWidget
{
    public static string $label       = 'Hero Slider';
    public static string $description = 'Banner slider chính trang chủ với tiêu đề, phụ đề và nút CTA';
    public static string $icon        = 'fa-solid fa-images';

    public static function fields(): array
    {
        return [
            ['key' => 'style',    'label' => 'Kiểu hiển thị', 'type' => 'select',
                'options' => ['fullwidth' => 'Toàn chiều rộng', 'boxed' => 'Có viền'],
                'default' => 'fullwidth'],
            ['key' => 'autoplay', 'label' => 'Tự động chạy',  'type' => 'toggle', 'default' => true],
            ['key' => 'interval', 'label' => 'Thời gian (ms)', 'type' => 'number', 'default' => 4000],
            ['key' => 'slides',   'label' => 'Slides',         'type' => 'repeater', 'default' => [],
                'fields' => [
                    ['key' => 'bg_image',    'label' => 'Ảnh nền',       'type' => 'image'],
                    ['key' => 'bg_class',    'label' => 'CSS class nền',  'type' => 'text'],
                    ['key' => 'pre_title',   'label' => 'Tiêu đề nhỏ',   'type' => 'text'],
                    ['key' => 'title',       'label' => 'Tiêu đề chính', 'type' => 'text'],
                    ['key' => 'description', 'label' => 'Mô tả',         'type' => 'textarea'],
                    ['key' => 'btn_text',    'label' => 'Nút CTA',        'type' => 'text', 'default' => 'Mua ngay'],
                    ['key' => 'btn_link',    'label' => 'Link nút',       'type' => 'text', 'default' => '/shop'],
                    ['key' => 'price_label', 'label' => 'Nhãn giá',      'type' => 'text'],
                    ['key' => 'price',       'label' => 'Giá hiển thị',  'type' => 'text'],
                ],
            ],
        ];
    }

    public static function render(array $config, WidgetModel $widget): string
    {
        return static::view('widgets.types.hero_slider', ['config' => $config, 'widget' => $widget]);
    }
}
