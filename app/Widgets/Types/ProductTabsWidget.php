<?php

namespace App\Widgets\Types;

use App\Models\Widget as WidgetModel;
use App\Models\Product;
use App\Widgets\BaseWidget;

/**
 * Widget: Product Tabs - Sản phẩm bán chạy theo tab (Weekly Best Selling)
 * Map từ: .weekly-best-selling-area (index.html)
 */
class ProductTabsWidget extends BaseWidget
{
    public static string $label = 'Sản phẩm theo tab';
    public static string $description = 'Grid sản phẩm với tabs lọc theo danh mục (Weekly Best Selling)';
    public static string $icon = 'fa-solid fa-table-list';

    public static function fields(): array
    {
        return [
            ['key' => 'title', 'label' => 'Tiêu đề section', 'type' => 'text', 'default' => 'Weekly Best Selling Groceries'],
            [
                'key' => 'columns',
                'label' => 'Số cột',
                'type' => 'select',
                'options' => ['4' => '4 cột', '5' => '5 cột', '6' => '6 cột'],
                'default' => '6'
            ],
            ['key' => 'limit', 'label' => 'Số SP mỗi tab', 'type' => 'number', 'default' => 12],
            ['key' => 'bg_light', 'label' => 'Nền sáng', 'type' => 'toggle', 'default' => true],
            [
                'key' => 'tabs',
                'label' => 'Tabs lọc danh mục',
                'type' => 'repeater',
                'default' => [
                    ['label' => 'Frozen Foods', 'filter' => 'best_selling'],
                    ['label' => 'Diet Foods', 'filter' => 'new'],
                    ['label' => 'Healthy Foods', 'filter' => 'sale'],
                    ['label' => 'Vitamin Items', 'filter' => 'all'],
                ],
                'fields' => [
                    ['key' => 'label', 'label' => 'Tên tab', 'type' => 'text', 'col' => 'col-md-4'],
                    ['key' => 'category_id', 'label' => 'Danh mục', 'type' => 'category_select', 'single' => true, 'col' => 'col-md-4'],
                    [
                        'key' => 'filter',
                        'label' => 'Lọc theo',
                        'type' => 'select',
                        'col' => 'col-md-4',
                        'options' => ['best_selling' => 'Bán chạy', 'new' => 'Mới nhất', 'sale' => 'Giảm giá', 'all' => 'Tất cả']
                    ],
                ],
            ],
        ];
    }

    public static function render(array $config, WidgetModel $widget): string
    {
        $tabs = $config['tabs'] ?? [];
        $limit = (int) ($config['limit'] ?? 12);

        $tabProducts = [];
        foreach ($tabs as $i => $tab) {
            $tabProducts[$i] = static::fetchProducts($tab, $limit);
        }

        return static::view('widgets.types.product_tabs', [
            'config' => $config,
            'widget' => $widget,
            'tabProducts' => $tabProducts,
        ]);
    }

    public static function fetchProducts(array $tab, int $limit): \Illuminate\Database\Eloquent\Collection
    {
        $query = Product::where('status', 'active');

        if (!empty($tab['category_id'])) {
            $query->where('category_id', (int) $tab['category_id']);
        }

        $filter = $tab['filter'] ?? 'best_selling';
        match ($filter) {
            'sale' => $query->whereNotNull('compare_price')->whereColumn('price', '<', 'compare_price'),
            'new' => $query->latest(),
            default => $query->orderByDesc('id'),
        };

        return $query->limit($limit)->get();
    }
}
