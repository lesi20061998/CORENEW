<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::where('is_active', true)->withCount('products')->get();

        $query = Product::where('status', 'active')->with('categories');

        // Filter by category
        if ($request->filled('category')) {
            $cat = Category::where('slug', $request->category)->first();
            if ($cat) {
                $query->whereHas('categories', function($q) use ($cat) {
                    $q->where('categories.id', $cat->id);
                });
            }
        }

        // Filter by search
        if ($request->filled('q')) {
            $query->where('name', 'like', '%' . $request->q . '%');
        }

        // Filter by price
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Sort
        match ($request->get('sort', 'newest')) {
            'price_asc'    => $query->orderBy('price', 'asc'),
            'price_desc'   => $query->orderBy('price', 'desc'),
            'best_selling' => $query->orderByDesc('id'),
            default        => $query->latest(),
        };

        $products = $query->paginate(12);

        return view('shop.index', compact('products', 'categories'));
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
