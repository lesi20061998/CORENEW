<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AttributeSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Attribute::truncate();
        AttributeValue::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $attrs = [
            ['name' => 'Kích thước', 'values' => ['S', 'M', 'L', 'XL', '2XL']],
            ['name' => 'Màu sắc', 'values' => ['Đỏ', 'Xanh', 'Vàng', 'Đen', 'Trắng']],
            ['name' => 'Trọng lượng', 'values' => ['250g', '500g', '1kg', '2kg', '5kg']],
            ['name' => 'Dung tích', 'values' => ['330ml', '500ml', '1L', '1.5L', '2L']],
        ];

        foreach ($attrs as $a) {
            $attribute = Attribute::create([
                'name' => $a['name'],
                'slug' => Str::slug($a['name']),
                'type' => 'select',
                'is_filterable' => true,
                'sort_order' => 0
            ]);

            foreach ($a['values'] as $idx => $v) {
                AttributeValue::create([
                    'attribute_id' => $attribute->id,
                    'value' => $v,
                    'sort_order' => $idx
                ]);
            }
        }

        $this->command->info('✅ Đã seed ' . Attribute::count() . ' thuộc tính');
    }
}
