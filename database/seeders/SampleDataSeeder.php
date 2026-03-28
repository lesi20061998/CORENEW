<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Product;
use App\Models\ProductAttribute;
use Illuminate\Database\Seeder;

class SampleDataSeeder extends Seeder
{
    public function run(): void
    {
        // Create Color attribute
        $colorAttribute = Attribute::create([
            'name' => 'Color',
            'type' => 'checkbox',
            'is_filterable' => true,
            'sort_order' => 1
        ]);

        $colors = [
            ['value' => 'Red', 'color_code' => '#FF0000'],
            ['value' => 'Blue', 'color_code' => '#0000FF'],
            ['value' => 'Green', 'color_code' => '#008000'],
            ['value' => 'Black', 'color_code' => '#000000'],
            ['value' => 'White', 'color_code' => '#FFFFFF'],
        ];

        foreach ($colors as $index => $color) {
            AttributeValue::create([
                'attribute_id' => $colorAttribute->id,
                'value' => $color['value'],
                'color_code' => $color['color_code'],
                'sort_order' => $index
            ]);
        }

        // Create Size attribute
        $sizeAttribute = Attribute::create([
            'name' => 'Size',
            'type' => 'select',
            'is_filterable' => true,
            'sort_order' => 2
        ]);

        $sizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];
        foreach ($sizes as $index => $size) {
            AttributeValue::create([
                'attribute_id' => $sizeAttribute->id,
                'value' => $size,
                'sort_order' => $index
            ]);
        }

        // Create Material attribute
        $materialAttribute = Attribute::create([
            'name' => 'Material',
            'type' => 'select',
            'is_filterable' => true,
            'sort_order' => 3
        ]);

        $materials = ['Cotton', 'Polyester', 'Wool', 'Silk', 'Denim'];
        foreach ($materials as $index => $material) {
            AttributeValue::create([
                'attribute_id' => $materialAttribute->id,
                'value' => $material,
                'sort_order' => $index
            ]);
        }

        // Create sample products
        $products = [
            [
                'name' => 'Classic T-Shirt',
                'description' => 'Comfortable cotton t-shirt perfect for everyday wear.',
                'price' => 299000,
                'stock' => 50,
                'status' => 'active'
            ],
            [
                'name' => 'Denim Jeans',
                'description' => 'High-quality denim jeans with a modern fit.',
                'price' => 899000,
                'stock' => 30,
                'status' => 'active'
            ],
            [
                'name' => 'Wool Sweater',
                'description' => 'Warm and cozy wool sweater for cold weather.',
                'price' => 1299000,
                'stock' => 20,
                'status' => 'active'
            ],
            [
                'name' => 'Silk Blouse',
                'description' => 'Elegant silk blouse for formal occasions.',
                'price' => 1599000,
                'stock' => 15,
                'status' => 'active'
            ],
            [
                'name' => 'Cotton Dress',
                'description' => 'Light and breathable cotton dress for summer.',
                'price' => 799000,
                'stock' => 25,
                'status' => 'active'
            ]
        ];

        foreach ($products as $productData) {
            $product = Product::create($productData);

            // Assign random attributes to products
            $this->assignRandomAttributes($product, $colorAttribute, $sizeAttribute, $materialAttribute);
        }
    }

    private function assignRandomAttributes($product, $colorAttribute, $sizeAttribute, $materialAttribute)
    {
        // Assign 1-2 random colors
        $colorValues = $colorAttribute->values->random(rand(1, 2));
        foreach ($colorValues as $colorValue) {
            ProductAttribute::create([
                'product_id' => $product->id,
                'attribute_id' => $colorAttribute->id,
                'attribute_value_id' => $colorValue->id
            ]);
        }

        // Assign 1-3 random sizes
        $sizeValues = $sizeAttribute->values->random(rand(1, 3));
        foreach ($sizeValues as $sizeValue) {
            ProductAttribute::create([
                'product_id' => $product->id,
                'attribute_id' => $sizeAttribute->id,
                'attribute_value_id' => $sizeValue->id
            ]);
        }

        // Assign 1 random material
        $materialValue = $materialAttribute->values->random(1)->first();
        ProductAttribute::create([
            'product_id' => $product->id,
            'attribute_id' => $materialAttribute->id,
            'attribute_value_id' => $materialValue->id
        ]);
    }
}