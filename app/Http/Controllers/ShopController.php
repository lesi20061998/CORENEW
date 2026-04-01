<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request, $category_slug = null)
    {
        $categories = Category::active()->withCount(['products' => function($q) {
            $q->active();
        }])->get();
        
        // Fetch ALL active products for frontend filtering
        $allProducts = Product::active()->with('categories')->get();

        $productsJson = $allProducts->map(function($p) {
            $effectivePrice = (float)$p->effective_price;
            $originalPrice = (float)$p->price;
            $hasDiscount = $effectivePrice < $originalPrice;
            
            return [
                'id'               => $p->id,
                'name'             => $p->name,
                'slug'             => $p->slug,
                'price'            => $effectivePrice,
                'formatted_price'  => $effectivePrice <= 0 ? 'Giá liên hệ' : number_format($effectivePrice, 0, ',', '.') . ' ₫',
                'old_price'        => $originalPrice,
                'formatted_old_price' => ($hasDiscount && $effectivePrice > 0) ? number_format($originalPrice, 0, ',', '.') . ' ₫' : null,
                'thumbnail_url'    => $p->thumbnail_url ?: asset('theme/images/grocery/01.jpg'),
                'unit'             => $p->unit ?? '500g Pack',
                'on_sale'          => $hasDiscount && $effectivePrice > 0,
                'has_contact_price' => $effectivePrice <= 0,
                'discount_percent' => $effectivePrice <= 0 ? null : ($p->flash_discount_percent ?? $p->getDiscountPercentAttribute()),
                'category_ids'     => $p->categories->pluck('id')->toArray(),
                'category_slugs'   => $p->categories->pluck('slug')->toArray(),
                'created_at'       => $p->created_at->toIso8601String(),
                'is_featured'      => (bool)$p->is_featured,
                'url'              => route('shop.show', ['slug' => $p->slug]),
            ];
        });

        // Current active category for initial filtering if URL contains one
        $activeCategorySlug = $category_slug ?: $request->get('category');
        
        // Default products for initial SSR rendering (first page)
        $initialProducts = $allProducts;
        if ($activeCategorySlug) {
             $initialProducts = $allProducts->filter(function($p) use ($activeCategorySlug) {
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
        $q        = $request->get('q', '');
        $category = $request->get('category', '');

        $query = Product::where('status', 'active')->select('id', 'name', 'slug');

        if ($q) {
            $query->where('name', 'like', '%' . $q . '%');
        }

        if ($category) {
            $cat = Category::where('slug', $category)->first();
            if ($cat) {
                $query->whereHas('categories', function($q) use ($cat) {
                    $q->where('categories.id', $cat->id);
                });
            }
        }

        $products = $query->limit(10)->get()->map(fn($p) => [
            'name' => $p->name,
            'slug' => $p->slug,
        ]);

        return response()->json($products);
    }

    public function show(string $slug)
    {
        $product = Product::where('slug', $slug)
            ->where('status', 'active')
            ->with(['categories', 'productAttributes', 'variants'])
            ->firstOrFail();

        $relatedProducts = Product::where('status', 'active')
            ->whereHas('categories', function($q) use ($product) {
                $q->whereIn('categories.id', $product->categories->pluck('id'));
            })
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();

        return view('shop.show', compact('product', 'relatedProducts'));
    }
}
