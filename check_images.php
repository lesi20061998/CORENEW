<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;

$products = Product::where('image', 'LIKE', '/%')->get();
if ($products->count() > 0) {
    echo "Found " . $products->count() . " products with image starting with '/'.\n";
} else {
    echo "No product images found starting with '/'. Checking first 50.\n";
    $products = Product::whereNotNull('image')->take(50)->get();
}
foreach ($products as $p) {
    echo "ID: " . $p->id . " | Image: " . $p->image . " | Thumbnail URL: " . $p->thumbnail_url . "\n";
}
