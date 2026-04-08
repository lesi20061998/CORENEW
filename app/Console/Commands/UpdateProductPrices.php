<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Product;
use App\Models\ProductVariant;

class UpdateProductPrices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:update-prices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update all product prices randomly for Vietnam market (Original and Sale price)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $products = Product::with('variants')->get();
        $this->info("Found {$products->count()} products to update.");

        $bar = $this->output->createProgressBar($products->count());
        $bar->start();

        /** @var Product $product */
        foreach ($products as $product) {
            // Random original price (current price) from 50k to 5M
            $originalPrice = rand(5, 500) * 10000;
            
            // Random discount from 5% to 45%
            $discountPercent = rand(5, 45);
            $salePrice = $originalPrice * (1 - ($discountPercent / 100));

            // Round to nearest 1000
            $originalPrice = round($originalPrice, -3);
            $salePrice = round($salePrice, -3);

            // Ensure sale price is always less than original price
            if ($salePrice >= $originalPrice) {
                $salePrice = $originalPrice - 5000;
            }

            // Update product
            $product->update([
                'compare_price' => $originalPrice,
                'price' => $salePrice
            ]);

            // Update variants if exists
            if ($product->variants->count() > 0) {
                /** @var ProductVariant $variant */
                foreach ($product->variants as $variant) {
                    // Variants can have same price or slight variations (+/- 10%)
                    $varVariation = rand(90, 110) / 100;
                    $varOriginal = round($originalPrice * $varVariation, -3);
                    $varSale = round($salePrice * $varVariation, -3);

                    // Ensure variant sale price < original price
                    if ($varSale >= $varOriginal) {
                        $varSale = $varOriginal - 5000;
                    }

                    $variant->update([
                        'compare_price' => $varOriginal,
                        'price' => $varSale
                    ]);
                }
            }

            $bar->advance();
        }

        $bar->finish();
        $this->info("\nPrices updated successfully for all products and variants.");
    }
}
