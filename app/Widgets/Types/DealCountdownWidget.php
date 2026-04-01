<?php

namespace App\Widgets\Types;

use App\Models\Widget as WidgetModel;
use App\Models\Product;
use App\Widgets\BaseWidget;

class DealCountdownWidget extends BaseWidget
{
    public static string $label       = 'Flash Sale (Giảm giá)';
    public static string $description = 'Hiển thị sản phẩm Big Sale với đồng hồ đếm ngược';
    public static string $icon        = 'fa-solid fa-bolt';

    public static function fields(): array
    {
        return [
            ['key' => 'title', 'label' => 'Tiêu đề section', 'type' => 'text', 'default' => 'Products With Discounts'],
            ['key' => 'end_date', 'label' => 'Thời gian kết thúc', 'type' => 'text', 'default' => '12/31/2026 23:59:59'],
            
            ['key' => 'promo_cards', 'label' => 'Banners Khuyến mãi (Bên trái)', 'type' => 'repeater', 
                'default' => [
                    [
                        'title'    => "Alpro Organic Flavored\nFresh Juice",
                        'price'    => '$15.00',
                        'bg_class' => 'bg-1',
                        'link'     => '/shop',
                    ],
                    [
                        'title'    => "Alpro Organic Flavored\nFresh Juice",
                        'price'    => '$15.00',
                        'bg_class' => 'bg-2',
                        'link'     => '/shop',
                    ],
                ],
                'fields' => [
                    ['key' => 'title', 'label' => 'Tiêu đề banner', 'type' => 'text'],
                    ['key' => 'price', 'label' => 'Giá hiển thị', 'type' => 'text', 'col' => 'col-md-6'],
                    ['key' => 'bg_class', 'label' => 'CSS class (bg-1, bg-2)', 'type' => 'text', 'col' => 'col-md-6'],
                    ['key' => 'image', 'label' => 'Ảnh nền (nếu có)', 'type' => 'image'],
                    ['key' => 'link',  'label' => 'Link', 'type' => 'text'],
                ]
            ],

            ['key' => 'category_id', 'label' => 'Lấy sản phẩm từ danh mục', 'type' => 'category_select', 'default' => []],
            ['key' => 'limit', 'label' => 'Số sản phẩm bên phải', 'type' => 'number', 'default' => 4],
        ];
    }

    public static function render(array $config, WidgetModel $widget): string
    {
        $categoryIds = array_filter((array)($config['category_id'] ?? []));
        $products = Product::where('status', 'active')
            ->when(!empty($categoryIds), fn($q) => $q->whereIn('category_id', $categoryIds))
            ->whereNotNull('compare_price')
            ->whereColumn('price', '<', 'compare_price')
            ->latest()
            ->limit((int)($config['limit'] ?? 4))
            ->get();

        return static::view('widgets.types.deal_flash', [
            'config'   => $config,
            'widget'   => $widget,
            'products' => $products
        ]);
    }
}
