<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::whereIn('type', ['product', 'blog'])->delete();

        // ─── Danh mục sản phẩm ───────────────────────────────────────
        $productCats = [
            ['name' => 'Rau củ quả',        'slug' => 'rau-cu-qua',        'image' => 'theme/images/category/img-1.png',  'sort_order' => 1],
            ['name' => 'Trái cây tươi',      'slug' => 'trai-cay-tuoi',     'image' => 'theme/images/category/img-2.png',  'sort_order' => 2],
            ['name' => 'Thịt & Hải sản',     'slug' => 'thit-hai-san',      'image' => 'theme/images/category/img-3.png',  'sort_order' => 3],
            ['name' => 'Sữa & Trứng',        'slug' => 'sua-trung',         'image' => 'theme/images/category/img-4.png',  'sort_order' => 4],
            ['name' => 'Bánh & Ngũ cốc',     'slug' => 'banh-ngu-coc',      'image' => 'theme/images/category/img-5.png',  'sort_order' => 5],
            ['name' => 'Đồ uống',            'slug' => 'do-uong',           'image' => 'theme/images/category/img-6.png',  'sort_order' => 6],
            ['name' => 'Gia vị & Dầu ăn',   'slug' => 'gia-vi-dau-an',     'image' => 'theme/images/category/img-7.png',  'sort_order' => 7],
            ['name' => 'Thực phẩm đông lạnh','slug' => 'thuc-pham-dong-lanh','image' => 'theme/images/category/img-8.png', 'sort_order' => 8],
            ['name' => 'Snack & Bánh kẹo',   'slug' => 'snack-banh-keo',    'image' => 'theme/images/category/img-9.png',  'sort_order' => 9],
            ['name' => 'Hữu cơ & Healthy',   'slug' => 'huu-co-healthy',    'image' => 'theme/images/category/img-10.png', 'sort_order' => 10],
        ];

        foreach ($productCats as $cat) {
            Category::create(array_merge($cat, ['type' => 'product', 'is_active' => true]));
        }

        // ─── Danh mục blog ────────────────────────────────────────────
        $blogCats = [
            ['name' => 'Sức khỏe & Dinh dưỡng', 'slug' => 'suc-khoe-dinh-duong', 'sort_order' => 1],
            ['name' => 'Công thức nấu ăn',       'slug' => 'cong-thuc-nau-an',    'sort_order' => 2],
            ['name' => 'Mẹo vặt nhà bếp',        'slug' => 'meo-vat-nha-bep',     'sort_order' => 3],
            ['name' => 'Tin tức thực phẩm',       'slug' => 'tin-tuc-thuc-pham',   'sort_order' => 4],
        ];

        foreach ($blogCats as $cat) {
            Category::create(array_merge($cat, ['type' => 'blog', 'is_active' => true]));
        }

        $this->command->info('✅ Đã seed ' . Category::count() . ' danh mục');
    }
}
