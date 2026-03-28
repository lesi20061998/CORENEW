@extends('layouts.app')

@section('title', 'Cửa hàng - ' . setting('site_name', 'VietTinMart'))

@section('content')

{{-- Breadcrumb --}}
<div class="rts-breadcrumb-area breadcrumb-bg bg_image">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                <h1 class="title">Cửa hàng</h1>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                <div class="page-header-right-flex">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Cửa hàng</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Shop Top Widget Area --}}
@widgetArea('shop_top')

<div class="rts-shop-section rts-section-gap">
    <div class="container">
        <div class="row">

            {{-- Sidebar --}}
            <div class="col-xl-3 col-lg-4 col-md-12 col-sm-12 col-12">
                <div class="shop-sidebar-wrapper">

                    {{-- Tìm kiếm --}}
                    <div class="single-sidebar-widget">
                        <h5 class="title">Tìm kiếm</h5>
                        <form action="{{ route('shop.index') }}" method="GET" class="sidebar-search">
                            <input type="text" name="q" value="{{ request('q') }}" placeholder="Tìm sản phẩm...">
                            <button type="submit"><i class="fa-light fa-magnifying-glass"></i></button>
                        </form>
                    </div>

                    {{-- Danh mục --}}
                    <div class="single-sidebar-widget">
                        <h5 class="title">Danh mục</h5>
                        <ul class="category-list">
                            <li class="{{ !request('category') ? 'active' : '' }}">
                                <a href="{{ route('shop.index', request()->except('category', 'page')) }}">
                                    Tất cả danh mục
                                </a>
                            </li>
                            @foreach($categories as $cat)
                            <li class="{{ request('category') === $cat->slug ? 'active' : '' }}">
                                <a href="{{ route('shop.index', array_merge(request()->except('page'), ['category' => $cat->slug])) }}">
                                    {{ $cat->name }}
                                    <span>({{ $cat->products_count ?? 0 }})</span>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    {{-- Lọc giá --}}
                    <div class="single-sidebar-widget">
                        <h5 class="title">Khoảng giá</h5>
                        <form action="{{ route('shop.index') }}" method="GET" id="price-filter-form">
                            @foreach(request()->except(['min_price', 'max_price', 'page']) as $k => $v)
                            <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                            @endforeach
                            <div class="price-range-wrapper">
                                <div class="d-flex gap-2 mb-2">
                                    <input type="number" name="min_price" value="{{ request('min_price', 0) }}"
                                        placeholder="Từ" class="form-control form-control-sm">
                                    <input type="number" name="max_price" value="{{ request('max_price', '') }}"
                                        placeholder="Đến" class="form-control form-control-sm">
                                </div>
                                <button type="submit" class="rts-btn btn-primary w-100">Lọc giá</button>
                            </div>
                        </form>
                    </div>

                    {{-- Sidebar Widget Area --}}
                    @widgetArea('shop_sidebar')

                </div>
            </div>

            {{-- Product Listing --}}
            <div class="col-xl-9 col-lg-8 col-md-12 col-sm-12 col-12">

                {{-- Toolbar --}}
                <div class="shop-toolbar d-flex align-items-center justify-content-between mb--30">
                    <p class="showing-result">
                        Hiển thị {{ $products->firstItem() }}-{{ $products->lastItem() }} / {{ $products->total() }} sản phẩm
                    </p>
                    <div class="toolbar-right d-flex align-items-center gap-3">
                        <div class="layout-switcher">
                            <a href="{{ request()->fullUrlWithQuery(['layout' => 'grid']) }}"
                                class="layout-btn {{ request('layout', 'grid') === 'grid' ? 'active' : '' }}">
                                <i class="fa-solid fa-grid-2"></i>
                            </a>
                            <a href="{{ request()->fullUrlWithQuery(['layout' => 'list']) }}"
                                class="layout-btn {{ request('layout') === 'list' ? 'active' : '' }}">
                                <i class="fa-solid fa-list"></i>
                            </a>
                        </div>
                        <form action="{{ route('shop.index') }}" method="GET" class="sort-form">
                            @foreach(request()->except(['sort', 'page']) as $k => $v)
                            <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                            @endforeach
                            <select name="sort" onchange="this.form.submit()" class="form-select form-select-sm">
                                <option value="newest"       {{ request('sort') === 'newest'       ? 'selected' : '' }}>Mới nhất</option>
                                <option value="price_asc"    {{ request('sort') === 'price_asc'    ? 'selected' : '' }}>Giá tăng dần</option>
                                <option value="price_desc"   {{ request('sort') === 'price_desc'   ? 'selected' : '' }}>Giá giảm dần</option>
                                <option value="best_selling" {{ request('sort') === 'best_selling' ? 'selected' : '' }}>Bán chạy nhất</option>
                            </select>
                        </form>
                    </div>
                </div>

                {{-- Products Grid --}}
                @if(request('layout') === 'list')
                <div class="shop-list-wrapper">
                    @foreach($products as $product)
                    @include('shop.partials.product-list-item', ['product' => $product])
                    @endforeach
                </div>
                @else
                <div class="row g-4">
                    @forelse($products as $product)
                    <div class="col-xxl-4 col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12">
                        @include('widgets.partials.product-card', ['product' => $product])
                    </div>
                    @empty
                    <div class="col-12 text-center py-5">
                        <i class="fa-light fa-box-open" style="font-size: 3rem; color: #ccc;"></i>
                        <p class="mt-3">Không tìm thấy sản phẩm nào.</p>
                        <a href="{{ route('shop.index') }}" class="rts-btn btn-primary mt-2">Xem tất cả</a>
                    </div>
                    @endforelse
                </div>
                @endif

                {{-- Pagination --}}
                @if($products->hasPages())
                <div class="pagination-wrapper mt--40">
                    {{ $products->withQueryString()->links('vendor.pagination.custom') }}
                </div>
                @endif

            </div>
        </div>
    </div>
</div>

@endsection
