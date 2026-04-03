@extends('layouts.app')

@section('title', 'Sitemap — ' . setting('site_name'))

@push('styles')
<style>
    .sitemap-container {
        padding: 60px 0;
    }
    .sitemap-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 40px;
    }
    .sitemap-section h2 {
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 20px;
        color: var(--color-primary);
        border-bottom: 2px solid var(--color-primary);
        padding-bottom: 10px;
    }
    .sitemap-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .sitemap-list li {
        margin-bottom: 10px;
    }
    .sitemap-list a {
        color: var(--color-body);
        font-size: 15px;
        transition: color 0.3s;
        display: block;
    }
    .sitemap-list a:hover {
        color: var(--color-primary);
    }
    .breadcrumb-area {
        background-color: #f8f9fa;
        padding: 30px 0;
        margin-bottom: 0;
    }
</style>
@endpush

@section('content')
<div class="breadcrumb-area">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                <li class="breadcrumb-item active" aria-current="page">Sitemap</li>
            </ol>
        </nav>
    </div>
</div>

<div class="sitemap-container">
    <div class="container">
        <h1 class="mb--40">Website Sitemap</h1>
        
        <div class="sitemap-grid">
            {{-- Pages Section --}}
            <div class="sitemap-section">
                <h2>Trang chính</h2>
                <ul class="sitemap-list">
                    <li><a href="{{ route('home') }}">Trang chủ</a></li>
                    <li><a href="{{ route('shop.index') }}">Cửa hàng</a></li>
                    <li><a href="{{ route('blog.index') }}">Blog - Tin tức</a></li>
                    <li><a href="{{ route('contact.index') }}">Liên hệ</a></li>
                    @foreach($pages as $page)
                        <li><a href="{{ url($page->slug) }}">{{ $page->title }}</a></li>
                    @endforeach
                </ul>
            </div>

            {{-- Categories Section --}}
            <div class="sitemap-section">
                <h2>Danh mục sản phẩm</h2>
                <ul class="sitemap-list">
                    @foreach($categories as $cat)
                        <li><a href="{{ route('shop.category', $cat->slug) }}">{{ $cat->name }}</a></li>
                    @endforeach
                </ul>
            </div>

            {{-- Posts Section --}}
            <div class="sitemap-section">
                <h2>Bài viết mới nhất</h2>
                <ul class="sitemap-list">
                    @foreach($posts as $post)
                        <li><a href="{{ url($post->slug) }}">{{ $post->name }}</a></li>
                    @endforeach
                </ul>
            </div>

            {{-- Products Section --}}
            <div class="sitemap-section">
                <h2>Sản phẩm nổi bật</h2>
                <ul class="sitemap-list">
                    @foreach($products->take(20) as $product)
                        <li><a href="{{ route('shop.show', $product->slug) }}">{{ $product->name }}</a></li>
                    @endforeach
                    @if($products->count() > 20)
                        <li><a href="{{ route('shop.index') }}" style="font-style: italic;">... và các sản phẩm khác</a></li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
