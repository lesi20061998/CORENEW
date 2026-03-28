@extends('layouts.app')

@section('title', 'Blog - ' . setting('site_name', 'VietTinMart'))

@section('content')

<div class="rts-breadcrumb-area breadcrumb-bg bg_image">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                <h1 class="title">Blog & Tin tức</h1>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Trang chủ</a></li>
                        <li class="breadcrumb-item active">Blog</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="rts-blog-section rts-section-gap">
    <div class="container">
        <div class="row">

            {{-- Blog Posts --}}
            <div class="col-xl-8 col-lg-8 col-md-12">
                <div class="row g-4">
                    @forelse($posts as $post)
                    @php $thumb = $post->thumbnail ? asset('storage/' . $post->thumbnail) : asset('theme/images/blog/01.jpg'); @endphp
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="single-blog-area-start">
                            <a href="{{ route('blog.show', $post->slug) }}" class="thumbnail">
                                <img src="{{ $thumb }}" alt="{{ $post->title }}">
                            </a>
                            <div class="blog-body">
                                <div class="top-area">
                                    <div class="single-meta">
                                        <i class="fa-light fa-clock"></i>
                                        <span>{{ $post->published_at?->format('d M, Y') ?? $post->created_at->format('d M, Y') }}</span>
                                    </div>
                                    @if($post->author)
                                    <div class="single-meta">
                                        <i class="fa-regular fa-user"></i>
                                        <span>{{ $post->author->name }}</span>
                                    </div>
                                    @endif
                                </div>
                                <a href="{{ route('blog.show', $post->slug) }}">
                                    <h4 class="title">{{ $post->title }}</h4>
                                </a>
                                @if($post->excerpt)
                                <p class="disc">{{ Str::limit($post->excerpt, 100) }}</p>
                                @endif
                                <a href="{{ route('blog.show', $post->slug) }}" class="shop-now-goshop-btn">
                                    <span class="text">Đọc thêm</span>
                                    <div class="plus-icon"><i class="fa-sharp fa-regular fa-plus"></i></div>
                                    <div class="plus-icon"><i class="fa-sharp fa-regular fa-plus"></i></div>
                                </a>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12 text-center py-5">
                        <p>Chưa có bài viết nào.</p>
                    </div>
                    @endforelse
                </div>

                @if($posts->hasPages())
                <div class="pagination-wrapper mt--40">
                    {{ $posts->withQueryString()->links('vendor.pagination.custom') }}
                </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="col-xl-4 col-lg-4 col-md-12">
                <div class="blog-sidebar-wrapper">

                    {{-- Search --}}
                    <div class="single-sidebar-widget">
                        <h5 class="title">Tìm kiếm</h5>
                        <form action="{{ route('blog.index') }}" method="GET" class="sidebar-search">
                            <input type="text" name="q" value="{{ request('q') }}" placeholder="Tìm bài viết...">
                            <button type="submit"><i class="fa-light fa-magnifying-glass"></i></button>
                        </form>
                    </div>

                    {{-- Recent Posts --}}
                    <div class="single-sidebar-widget">
                        <h5 class="title">Bài viết gần đây</h5>
                        @foreach($recentPosts as $recent)
                        @php $rThumb = $recent->thumbnail ? asset('storage/' . $recent->thumbnail) : asset('theme/images/blog/01.jpg'); @endphp
                        <div class="recent-post-item d-flex gap-3 mb-3">
                            <img src="{{ $rThumb }}" alt="{{ $recent->title }}" style="width: 70px; height: 70px; object-fit: cover; border-radius: 4px;">
                            <div>
                                <a href="{{ route('blog.show', $recent->slug) }}">
                                    <h6>{{ Str::limit($recent->title, 50) }}</h6>
                                </a>
                                <small>{{ $recent->published_at?->format('d/m/Y') }}</small>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- Blog Sidebar Widget Area --}}
                    @widgetArea('blog_sidebar')

                </div>
            </div>

        </div>
    </div>
</div>

@endsection
