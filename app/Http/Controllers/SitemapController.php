<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Page;
use App\Models\Post;
use App\Models\Product;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Sitemap Index (Main)
     */
    public function index(): Response
    {
        return response()->view('sitemaps.index', [
            'sitemaps' => [
                ['url' => url('post-sitemap.xml'), 'lastmod' => Post::where('status', 'published')->latest('updated_at')->first()?->updated_at],
                ['url' => url('page-sitemap.xml'), 'lastmod' => Page::where('status', 'published')->latest('updated_at')->first()?->updated_at],
                ['url' => url('product-sitemap.xml'), 'lastmod' => Product::where('status', 'active')->latest('updated_at')->first()?->updated_at],
                ['url' => url('category-sitemap.xml'), 'lastmod' => Category::latest('updated_at')->first()?->updated_at],
            ],
        ])->header('Content-Type', 'text/xml');
    }

    public function posts(): Response
    {
        $posts = Post::where('status', 'published')->latest()->get();
        return response()->view('sitemaps.posts', ['posts' => $posts])->header('Content-Type', 'text/xml');
    }

    public function pages(): Response
    {
        $pages = Page::where('status', 'published')->latest()->get();
        return response()->view('sitemaps.pages', ['pages' => $pages])->header('Content-Type', 'text/xml');
    }

    public function products(): Response
    {
        $products = Product::where('status', 'active')->latest()->get();
        return response()->view('sitemaps.products', ['products' => $products])->header('Content-Type', 'text/xml');
    }

    public function categories(): Response
    {
        $categories = Category::latest()->get();
        return response()->view('sitemaps.categories', ['categories' => $categories])->header('Content-Type', 'text/xml');
    }

    /**
     * Human-readable Sitemap (HTML)
     */
    public function htmlIndex()
    {
        $products = Product::where('status', 'active')->latest()->get();
        $categories = Category::latest()->get();
        $posts = Post::where('status', 'published')->latest()->get();
        $pages = Page::where('status', 'published')->latest()->get();

        return view('sitemap_html', compact('products', 'categories', 'posts', 'pages'));
    }
}
