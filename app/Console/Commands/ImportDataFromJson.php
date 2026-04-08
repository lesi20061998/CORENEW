<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ImportDataFromJson extends Command
{
    protected $signature = 'import:data';
    protected $description = 'Import categories and products from JSON files and download local images';

    public function handle()
    {
        $this->info('Starting import...');

        // Clear existing data to avoid slug/ID conflicts
        $this->warn('Clearing existing categories and products...');
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Category::truncate();
        Product::truncate();
        DB::table('category_product')->truncate();
        
        // Also clear Media and MediaFolders if they were purely from our previous imports
        // (Optional: depending on whether the user wants to clear ALL media)
        // I'll assume they want a clean start for the imported images.
        // Media::truncate();
        // MediaFolder::truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 1. Import Categories
        $this->importCategories();

        // 2. Import Products
        $this->importProducts();

        $this->info('Import completed successfully!');
    }

    private function importCategories()
    {
        $path = base_path('public/categories.json');
        if (!File::exists($path)) {
            $this->error('categories.json not found!');
            return;
        }

        // Get or create category media folder
        $folder = \App\Models\MediaFolder::firstOrCreate(['slug' => 'categories'], ['name' => 'Categories']);

        $categories = json_decode(File::get($path), true);
        $this->info('Importing ' . count($categories) . ' categories (Step 1: Creation)...');

        foreach ($categories as $catData) {
            $catId = $catData['id'];
            $catName = $catData['name'];
            $catSlug = $catData['slug'] ?: Str::slug($catName);
            $catDesc = $catData['description'] ?? '';

            $localImagePath = null;
            if (!empty($catData['image'])) {
                $imageUrl = $catData['image'];
                if (!str_starts_with($imageUrl, 'http')) {
                    $imageUrl = 'https://viettinfood.com/' . ltrim($imageUrl, '/');
                }
                $localImagePath = $this->downloadImage($imageUrl, 'categories', $folder->id);
            }

            $category = Category::findOrNew($catId);
            $category->id = $catId;
            $category->fill([
                'name' => $catName,
                'slug' => $catSlug,
                'description' => $catDesc,
                'image' => $localImagePath,
                'is_active' => true,
                'type' => 'product',
            ]);
            $category->save();
        }

        $this->info('Updating category parents (Step 2)...');
        foreach ($categories as $catData) {
            if ($catData['parent'] > 0) {
                Category::where('id', $catData['id'])->update(['parent_id' => $catData['parent']]);
            } else {
                Category::where('id', $catData['id'])->update(['parent_id' => null]);
            }
        }
    }

    private function importProducts()
    {
        $path = base_path('public/products.json');
        if (!File::exists($path)) {
            $this->error('products.json not found!');
            return;
        }

        // Get or create product media folder
        $folder = \App\Models\MediaFolder::firstOrCreate(['slug' => 'products'], ['name' => 'Products']);

        $products = json_decode(File::get($path), true);
        $this->info('Importing ' . count($products) . ' products...');

        foreach ($products as $prodData) {
            $prodId = $prodData['id'];
            $prodName = $prodData['name'];
            $prodSlug = $prodData['slug'] ?: Str::slug($prodName);
            $prodDesc = $prodData['description'] ?? '';
            $prodShortDesc = $prodData['short_description'] ?? '';
            $prodSku = $prodData['sku'] ?? '';
            $prodPrice = is_numeric($prodData['price']) && $prodData['price'] > 0 ? (float)$prodData['price'] : 0;
            
            $localThumbnail = null;
            $localGallery = [];

            if (!empty($prodData['images'])) {
                foreach ($prodData['images'] as $idx => $imgData) {
                    $imageUrl = $imgData['src'];
                    $localPath = $this->downloadImage($imageUrl, 'products', $folder->id);
                    if ($idx === 0) {
                        $localThumbnail = $localPath;
                    }
                    if ($localPath) {
                        $localGallery[] = $localPath;
                    }
                }
            }

            $product = Product::findOrNew($prodId);
            $product->id = $prodId;
            $product->fill([
                'name' => $prodName,
                'slug' => $prodSlug,
                'description' => $prodDesc,
                'short_description' => $prodShortDesc,
                'sku' => $prodSku,
                'price' => $prodPrice,
                'image' => $localThumbnail,
                'images' => $localGallery,
                'status' => 'active',
                'stock_status' => 'in_stock',
                'stock' => 999,
            ]);
            $product->save();

            // Sync categories
            if (!empty($prodData['categories'])) {
                $categoryIds = collect($prodData['categories'])->pluck('id')->toArray();
                $product->categories()->sync($categoryIds);
                
                if (count($categoryIds) > 0) {
                    $product->update(['category_id' => $categoryIds[0]]);
                }
            }
        }
    }

    private function downloadImage($url, $folderName, $folderId = null)
    {
        try {
            if (empty($url)) return null;

            $urlPath = parse_url($url, PHP_URL_PATH);
            $rawFilename = basename($urlPath);
            if (empty($rawFilename)) {
                $filename = Str::random(10) . '.jpg';
            } else {
                $extension = pathinfo($rawFilename, PATHINFO_EXTENSION) ?: 'jpg';
                $baseName = pathinfo($rawFilename, PATHINFO_FILENAME);
                $filename = Str::slug($baseName) . '.' . $extension;
            }

            $relativePath = 'media/' . $folderName . '/' . $filename;
            
            if (Storage::disk('public')->exists($relativePath)) {
                // Register in Media table if not exists
                $this->registerMedia($relativePath, $filename, $folderId);
                return $relativePath;
            }

            // Ensure directory exists
            Storage::disk('public')->makeDirectory('media/' . $folderName);

            $this->info("Downloading: $url");
            $response = Http::withoutVerifying()->timeout(20)->get($url);
            if ($response->successful()) {
                Storage::disk('public')->put($relativePath, $response->body());
                $this->registerMedia($relativePath, $filename, $folderId);
                return $relativePath;
            }
        } catch (\Exception $e) {
            $this->warn("Failed to download image from $url: " . $e->getMessage());
        }

        return null;
    }

    private function registerMedia($path, $filename, $folderId)
    {
        $fullPath = Storage::disk('public')->path($path);
        if (!file_exists($fullPath)) return;

        $mimeType = File::mimeType($fullPath);
        $size = File::size($fullPath);
        $width = null;
        $height = null;

        if (str_starts_with($mimeType, 'image/')) {
            $dimensions = getimagesize($fullPath);
            if ($dimensions) {
                $width = $dimensions[0];
                $height = $dimensions[1];
            }
        }

        \App\Models\Media::updateOrCreate(
            ['path' => $path],
            [
                'name' => pathinfo($filename, PATHINFO_FILENAME),
                'file_name' => $filename,
                'mime_type' => $mimeType,
                'disk' => 'public',
                'size' => $size,
                'width' => $width,
                'height' => $height,
                'folder_id' => $folderId,
            ]
        );
    }
}
