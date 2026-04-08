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
            ['key' => 'items', 'label' => 'Banners', 'type' => 'repeater', 
                'default' => [
                    [
                        'badge'    => 'Ưu đãi cuối tuần',
                        'title'    => 'Nước ngô nguyên chất',
                        'subtitle' => 'Tươi ngon bổ dưỡng',
                        'image'    => 'theme/images/banner/promo-01.png',
                        'btn_text' => 'Mua ngay',
                        'btn_link' => '/shop',
                    ],
                    [
                        'badge'    => 'Ưu đãi cuối tuần',
                        'title'    => 'Khoai tây hữu cơ',
                        'subtitle' => 'Sản phẩm mới nhất',
                        'image'    => 'theme/images/banner/promo-02.png',
                        'btn_text' => 'Mua ngay',
                        'btn_link' => '/shop',
                    ],
                ],
                'fields' => [
                    ['key' => 'image',     'label' => 'Ảnh nền',      'type' => 'image'],
                    ['key' => 'badge',     'label' => 'Badge nhỏ',    'type' => 'text', 'col' => 'col-md-6'],
                    ['key' => 'title',     'label' => 'Tiêu đề',      'type' => 'text', 'col' => 'col-md-6'],
                    ['key' => 'subtitle',  'label' => 'Mô tả ngắn',   'type' => 'text'],
                    [
                        'key' => 'bg_style',
                        'label' => 'Màu chủ đạo (nếu không có ảnh)',
                        'type' => 'select',
                        'options' => [
                            'one' => 'Xanh lá (Ekomart)',
                            'two' => 'Cam (Năng động)',
                            'three' => 'Xanh dương (Tươi mát)',
                            'four' => 'Đỏ (Khuyến mãi mạnh)',
                        ],
                        'default' => 'one'
                    ],
                    ['key' => 'btn_text',  'label' => 'Nút bấm',      'type' => 'text', 'default' => 'Mua ngay', 'col' => 'col-md-6'],
                    ['key' => 'btn_link',  'label' => 'Đường dẫn',    'type' => 'text', 'default' => '/shop', 'col' => 'col-md-6'],
                ],
            ],
        ];
    }

    public static function render(array $config, WidgetModel $widget): string
    {
        return static::view('widgets.types.promo_banners', ['config' => $config, 'widget' => $widget]);
    }
}
