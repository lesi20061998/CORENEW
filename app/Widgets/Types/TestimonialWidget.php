<?php

namespace App\Widgets\Types;

use App\Models\Widget as WidgetModel;
use App\Widgets\BaseWidget;

/**
 * Widget: Testimonial - Đánh giá khách hàng
 */
class TestimonialWidget extends BaseWidget
{
    public static string $label       = 'Đánh giá khách hàng';
    public static string $description = 'Slider hiển thị đánh giá / nhận xét từ khách hàng';
    public static string $icon        = 'fa-solid fa-quote-left';

    public static function fields(): array
    {
        return [
            ['key' => 'title',    'label' => 'Tiêu đề section', 'type' => 'text', 'default' => 'Khách hàng nói gì về chúng tôi'],
            ['key' => 'subtitle', 'label' => 'Phụ đề',          'type' => 'text'],
            ['key' => 'items',    'label' => 'Đánh giá',        'type' => 'repeater', 'default' => [],
                'fields' => [
                    ['key' => 'name',     'label' => 'Tên khách hàng', 'type' => 'text'],
                    ['key' => 'position', 'label' => 'Chức vụ / Địa điểm', 'type' => 'text'],
                    ['key' => 'avatar',   'label' => 'Ảnh đại diện',  'type' => 'image'],
                    ['key' => 'content',  'label' => 'Nội dung đánh giá', 'type' => 'textarea'],
                    ['key' => 'stars',    'label' => 'Số sao (1-5)',  'type' => 'number', 'default' => 5],
                ],
            ],
        ];
    }

    public static function render(array $config, WidgetModel $widget): string
    {
        return static::view('widgets.types.testimonial', ['config' => $config, 'widget' => $widget]);
    }
}
