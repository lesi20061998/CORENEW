<?php

namespace App\Widgets\Types;

use App\Models\Widget as WidgetModel;
use App\Models\Product;
use App\Widgets\BaseWidget;

/**
 * Widget: Deal Countdown - Sản phẩm khuyến mãi có đồng hồ đếm ngược
 * Map từ: .rts-grocery-feature-area (phần countdown + .product-with-discount)
 */
class DealCountdownWidget extends BaseWidget
{
    public static string $label       = 'Deal đếm ngược';
    public static string $description = 'Sản phẩm giảm giá kèm đồng hồ đếm ngược thời gian';
    public static string $icon        = 'fa-solid fa-fire-flame-curved';

    public static function fields(): array
    {
        return [
            ['key' => 'title',        'label' => 'Tiêu đề',              'type' => 'text',   'default' => 'Sản phẩm khuyến mãi'],
            ['key' => 'countdown_to', 'label' => 'Đếm ngược đến (MM/DD/YYYY HH:MM:SS)', 'type' => 'text', 'default' => '12/31/2025 23:59:59'],
            ['key' => 'limit',        'label' => 'Số sản phẩm hiển thị', 'type' => 'number', 'default' => 4],
            ['key' => 'category_id',  'label' => 'Danh mục (tùy chọn)', 'type' => 'number', 'default' => null],
            ['key' => 'promo_cards',  'label' => 'Banner phụ bên trái',  'type' => 'repeater', 'default' => [],
                'fields' => [
                    ['key' => 'title',    'label' => 'Tiêu đề',   'type' => 'text'],
                    ['key' => 'price',    'label' => 'Giá',        'type' => 'text'],
                    ['key' => 'bg_class', 'label' => 'CSS class',  'type' => 'text', 'default' => 'bg-1'],
                    ['key' => 'link',     'label' => 'Link',       'type' => 'text', 'default' => '/shop'],
                ],
            ],
        ];
    }

    public static function render(array $config, WidgetModel $widget): string
    {
        $query = Product::where('status', 'active')
            ->whereNotNull('compare_price')
            ->whereColumn('price', '<', 'compare_price');

        if (!empty($config['category_id'])) {
            $query->where('category_id', $config['category_id']);
        }

        $products = $query->limit((int)($config['limit'] ?? 4))->get();

        return static::view('widgets.types.deal_countdown', [
            'config'   => $config,
            'widget'   => $widget,
            'products' => $products,
        ]);
    }
}
