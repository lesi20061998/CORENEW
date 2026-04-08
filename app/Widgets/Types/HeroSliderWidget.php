<?php

namespace App\Widgets\Types;

use App\Models\Widget as WidgetModel;
use App\Widgets\BaseWidget;

class HeroSliderWidget extends BaseWidget
{
    public static string $label       = 'Banner Chế độ Slider';
    public static string $description = 'Banner lớn đầu trang với hiệu ứng trượt Swiper';
    public static string $icon        = 'fa-solid fa-images';

    public static function fields(): array
    {
        return [
            ['key' => 'slides', 'label' => 'Danh sách Slide', 'type' => 'repeater',
                'default' => [
                    [
                        'image'       => 'theme/images/banner/banner-01.png',
                        'pre_title'   => 'Giảm đến 30% cho đơn hàng đầu tiên từ 1.500.000đ',
                        'title'       => "Đừng bỏ lỡ những ưu đãi\nthực phẩm tuyệt vời",
                        'btn_text'    => 'Mua ngay',
                        'btn_link'    => '/shop',
                    ],
                    [
                        'image'       => 'theme/images/banner/banner-02.png',
                        'pre_title'   => 'Giảm đến 30% cho đơn hàng đầu tiên từ 1.500.000đ',
                        'title'       => "Thực phẩm tươi sạch\nmỗi ngày cho gia đình bạn",
                        'btn_text'    => 'Khám phá ngay',
                        'btn_link'    => '/shop',
                    ],
                ],
                'fields' => [
                    ['key' => 'image',     'label' => 'Ảnh nền', 'type' => 'image'],
                    ['key' => 'pre_title', 'label' => 'Text nhỏ (Pre)', 'type' => 'text', 'col' => 'col-md-6'],
                    ['key' => 'title',     'label' => 'Tiêu đề chính', 'type' => 'text', 'col' => 'col-md-6'],
                    ['key' => 'description', 'label' => 'Mô tả ngắn',     'type' => 'text'],
                    ['key' => 'btn_text',  'label' => 'Text trên nút',   'type' => 'text', 'default' => 'Shop Now', 'col' => 'col-md-6'],
                    ['key' => 'btn_link',  'label' => 'Link đính kèm',   'type' => 'text', 'default' => '/shop', 'col' => 'col-md-6'],
                ]
            ],
            ['key' => 'autoplay_delay', 'label' => 'Tốc độ tự chạy (ms)', 'type' => 'number', 'default' => 4000],
        ];
    }

    public static function render(array $config, WidgetModel $widget): string
    {
        return static::view('widgets.types.hero_main', [
            'config' => $config,
            'widget' => $widget,
            'slides' => $config['slides'] ?? []
        ]);
    }
}
