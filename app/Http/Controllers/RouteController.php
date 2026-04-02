<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Post;
use App\Models\Product;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    public function index(Request $request, $param1, $param2 = null)
    {
        // Nếu $param2 có giá trị, nghĩa là route là {locale}/{slug} -> $param2 là slug
        // Nếu $param2 là null, nghĩa là route là {slug} -> $param1 là slug
        $slug = $param2 ?: $param1;

        // 1. Thử tìm sản phẩm
        $product = Product::where('slug', $slug)
            ->where('status', 'active')
            ->with(['categories', 'productAttributes', 'variants', 'activeCombos.variants'])
            ->first();

        if ($product) {
            $relatedProducts = Product::where('status', 'active')
                ->whereHas('categories', function($q) use ($product) {
                    $q->whereIn('categories.id', $product->categories->pluck('id'));
                })
                ->where('id', '!=', $product->id)
                ->limit(4)->get();

            return view('shop.show', compact('product', 'relatedProducts'));
        }

        // 2. Thử tìm bài viết
        $post = Post::where('slug', $slug)->where('status', 'published')
            ->with('author')->first();

        if ($post) {
            $relatedPosts = Post::where('status', 'published')
                ->where('id', '!=', $post->id)
                ->latest('published_at')->limit(3)->get();

            return view('blog.show', compact('post', 'relatedPosts'));
        }

        // 3. Thử tìm page
        $page = Page::where('slug', $slug)->where('status', 'published')->first();

        if ($page) {
            return view('pages.show', compact('page'));
        }

        abort(404);
    }
}
