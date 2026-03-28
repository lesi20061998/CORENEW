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
    public static string $label       = 'Sản phẩm theo tab';
    public static string $description = 'Grid sản phẩm với tabs lọc theo danh mục (Weekly Best Selling)';
    public static string $icon        = 'fa-solid fa-table-list';

    public static function fields(): array
    {
        return [
            ['key' => 'title',   'label' => 'Tiêu đề section', 'type' => 'text',   'default' => 'Bán chạy trong tuần'],
            ['key' => 'columns', 'label' => 'Số cột',          'type' => 'select',
                'options' => ['4' => '4 cột', '5' => '5 cột', '6' => '6 cột'],
                'default' => '6'],
            ['key' => 'limit',   'label' => 'Số SP mỗi tab',   'type' => 'number', 'default' => 12],
            ['key' => 'bg_light','label' => 'Nền sáng',        'type' => 'toggle', 'default' => true],
            ['key' => 'tabs',    'label' => 'Tabs',             'type' => 'repeater', 'default' => [],
                'fields' => [
                    ['key' => 'label',       'label' => 'Tên tab',     'type' => 'text'],
                    ['key' => 'category_id', 'label' => 'ID danh mục (để trống = tất cả)', 'type' => 'number'],
                    ['key' => 'filter',      'label' => 'Lọc',         'type' => 'select',
                        'options' => ['best_selling' => 'Bán chạy', 'new' => 'Mới nhất', 'sale' => 'Giảm giá', 'all' => 'Tất cả']],
                ],
            ],
        ];
    }

    public static function render(array $config, WidgetModel $widget): string
    {
        $tabs = $config['tabs'] ?? [];
        $limit = (int)($config['limit'] ?? 12);

        $tabProducts = [];
        foreach ($tabs as $i => $tab) {
            $tabProducts[$i] = static::fetchProducts($tab, $limit);
        }

        return static::view('widgets.types.product_tabs', [
            'config'      => $config,
            'widget'      => $widget,
            'tabProducts' => $tabProducts,
        ]);
    }

    public static function fetchProducts(array $tab, int $limit): \Illuminate\Database\Eloquent\Collection
    {
        $query = Product::where('status', 'active');

        if (!empty($tab['category_id'])) {
            $query->where('category_id', (int)$tab['category_id']);
        }

        $filter = $tab['filter'] ?? 'best_selling';
        match ($filter) {
            'sale'    => $query->whereNotNull('compare_price')->whereColumn('price', '<', 'compare_price'),
            'new'     => $query->latest(),
            default   => $query->orderByDesc('id'),
        };

        return $query->limit($limit)->get();
    }
}
