<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request, $category_slug = null)
    {
        $categoriesQuery = Category::active();
        if (\Illuminate\Support\Facades\Schema::hasTable('category_product')) {
            $categoriesQuery->withCount([
                'products' => function ($q) {
                    $q->active();
                }
            ]);
        }
        $categories = $categoriesQuery->get();

        // Fetch ALL active products for frontend filtering
        $productsQuery = Product::active()->orderBy('sort_order', 'asc')->orderBy('created_at', 'desc');
        if (\Illuminate\Support\Facades\Schema::hasTable('category_product')) {
            $productsQuery->with('categories');
        }
        $allProducts = $productsQuery->get();

        $productsJson = $allProducts->map(function ($p) {
            $effectivePrice = (float) $p->effective_price;
            $originalPrice = (float) $p->price;
            $hasDiscount = $effectivePrice < $originalPrice;

            return [
                'id' => $p->id,
                'name' => $p->name,
                'slug' => $p->slug,
                'price' => $effectivePrice,
                'formatted_price' => $effectivePrice <= 0 ? 'Giá liên hệ' : number_format($effectivePrice, 0, ',', '.') . ' ₫',
                'old_price' => $originalPrice,
                'formatted_old_price' => ($hasDiscount && $effectivePrice > 0) ? number_format($originalPrice, 0, ',', '.') . ' ₫' : null,
                'thumbnail_url' => $p->thumbnail_url ?: asset('theme/images/grocery/01.jpg'),
                'unit' => $p->unit ?? '500g Pack',
                'on_sale' => $hasDiscount && $effectivePrice > 0,
                'has_contact_price' => $effectivePrice <= 0,
                'discount_percent' => $effectivePrice <= 0 ? null : ($p->flash_discount_percent ?? $p->getDiscountPercentAttribute()),
                'category_ids' => $p->categories->pluck('id')->toArray(),
                'category_slugs' => $p->categories->pluck('slug')->toArray(),
                'category_names' => $p->categories->pluck('name')->toArray(),
                'created_at' => $p->created_at->toIso8601String(),
                'is_featured' => (bool) $p->is_featured,
                'is_best_seller' => (bool) $p->is_best_seller,
                'is_favorite' => (bool) $p->is_favorite,
                'sort_order' => (int) ($p->sort_order ?? 0),
                'url' => route('shop.show', ['slug' => $p->slug]),
            ];
        });

        // Current active category for initial filtering if URL contains one
        $activeCategorySlug = $category_slug ?: $request->get('category');

        // Default products for initial SSR rendering (first page)
        $initialProducts = $allProducts;
        if ($activeCategorySlug) {
            $initialProducts = $allProducts->filter(function ($p) use ($activeCategorySlug) {
                return in_array($activeCategorySlug, $p->categories->pluck('slug')->toArray());
            });
        }
        $products = $initialProducts->take(12);

        return view('shop.index', compact('products', 'categories', 'productsJson', 'activeCategorySlug'));
    }

    public function category(Request $request, $category_slug)
    {
        return $this->index($request, $category_slug);
    }

    public function searchSuggest(Request $request)
    {
        $q = $request->get('q', '');

        $query = Product::active()->with('categories')->orderBy('sort_order', 'asc');

        if ($q) {
            $query->where('name', 'like', '%' . $q . '%');
        }

        $products = $query->limit(10)->get()->map(function ($p) {
            $effectivePrice = (float) $p->effective_price;
            $oldPrice = (float) ($p->old_price ?: $p->price);
            $hasDiscount = $effectivePrice < $oldPrice;

            return [
                'name' => $p->name,
                'slug' => $p->slug,
                'price' => $effectivePrice,
                'formatted_price' => $effectivePrice <= 0 ? 'Giá liên hệ' : number_format($effectivePrice, 0, ',', '.') . ' ₫',
                'old_price' => $oldPrice,
                'formatted_old_price' => ($oldPrice > 0) ? number_format($oldPrice, 0, ',', '.') . ' ₫' : null,
                'has_discount' => $hasDiscount,
                'discount_percent' => $hasDiscount ? round((1 - ($effectivePrice / $oldPrice)) * 100) : null,
                'thumbnail_url' => $p->thumbnail_url ?: asset('theme/images/grocery/01.jpg'),
                'url' => route('shop.show', ['slug' => $p->slug]),
                'category' => $p->categories->pluck('name')->take(2)->implode(', '),
            ];
        });

        return response()->json($products);
    }

    public function show(string $slug)
    {
        $product = Product::where('slug', $slug)
            ->where('status', 'active')
            ->with(['categories', 'productAttributes.attribute', 'productAttributes.attributeValue', 'variants.attributeValues.attribute'])
            ->firstOrFail();

        $relatedQuery = Product::where('status', 'active');
        if (\Illuminate\Support\Facades\Schema::hasTable('category_product')) {
            $relatedQuery->whereHas('categories', function ($q) use ($product) {
                $q->whereIn('categories.id', $product->categories->pluck('id'));
            });
        }
        $relatedProducts = $relatedQuery->where('id', '!=', $product->id)
            ->limit(4)
            ->get();

        return view('shop.show', compact('product', 'relatedProducts'));
    }
}
