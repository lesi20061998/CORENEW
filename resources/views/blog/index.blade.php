@extends('layouts.app')

@section('title', 'Tin tức & Bài viết - VietTinMart')

@section('content')
<x-breadcrumb :items="[
    ['label' => 'Cửa hàng', 'url' => route('home')],
    ['label' => 'Tin tức']
]" />

<!-- blog list area start -->
<div class="rts-blog-list-area rts-section-gap">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-8">
                <div class="row g-4">
                    @forelse($posts as $post)
                    <div class="col-lg-12">
                        <div class="single-blog-list-style">
                            <a href="{{ url($post->slug) }}" class="thumbnail">
                                @php
                                    $th = $post->thumbnail;
                                    $m  = [];
                                    if (!$th) {
                                        $thUrl = asset('theme/images/blog/01.jpg');
                                    } elseif (str_starts_with($th, 'http')) {
                                        if (preg_match('#/storage/(media/.+)$#', $th, $m)) {
                                            $thUrl = asset('storage/' . $m[1]);
                                        } elseif (preg_match('#/public/(storage/media/.+)$#', $th, $m)) {
                                            $thUrl = asset($m[1]);
                                        } else {
                                            $thUrl = $th;
                                        }
                                    } elseif (str_starts_with($th, 'storage/') || str_starts_with($th, 'media/')) {
                                        $thUrl = asset(ltrim($th, '/'));
                                    } else {
                                        $thUrl = asset('storage/' . ltrim($th, '/'));
                                    }
                                @endphp
                                <img src="{{ $thUrl }}" alt="{{ $post->title }}">
                            </a>
                            <div class="blog-content">
                                <div class="top-area">
                                    <div class="single-meta">
                                        <i class="fa-light fa-calendar-days"></i>
                                        <span>{{ $post->published_at ? $post->published_at->format('d/m/Y') : $post->created_at->format('d/m/Y') }}</span>
                                    </div>
                                    <div class="single-meta">
                                        <i class="fa-light fa-user"></i>
                                        <span>{{ $post->author->name ?? 'Admin' }}</span>
                                    </div>
                                </div>
                                <a href="{{ url($post->slug) }}">
                                    <h3 class="title">{{ $post->title }}</h3>
                                </a>
                                <p class="disc">
                                    {{ $post->excerpt ?: Str::limit(strip_tags($post->content), 150) }}
                                </p>
                                <a href="{{ url($post->slug) }}" class="rts-btn btn-primary mt--10">Đọc thêm <i class="fa-regular fa-arrow-right ms-2"></i></a>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12 text-center py-5 bg-white border rounded">
                        <i class="fa-light fa-newspaper fa-3x mb-3 text-muted"></i>
                        <p class="text-muted">Hiện chưa có bài viết nào.</p>
                    </div>
                    @endforelse
                </div>
                
                <div class="row mt--40">
                    <div class="col-lg-12">
                        <div class="pagination-area">
                            {{ $posts->links('vendor.pagination.bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="blog-sidebar-area">
                    <div class="single-sidebar-widget search">
                        <h4 class="title">Tìm kiếm</h4>
                        <form action="{{ route('blog.index') }}" class="search-form">
                            <input name="q" value="{{ request('q') }}" type="text" placeholder="Nhập từ khóa..." required>
                            <button type="submit"><i class="fa-light fa-magnifying-glass"></i></button>
                        </form>
                    </div>
                    
                    <div class="single-sidebar-widget recent-post">
                        <h4 class="title">Bài viết gần đây</h4>
                        <div class="recent-post-wrapper">
                            @foreach($recentPosts ?? [] as $recent)
                            <div class="single-recent-post">
                                <a href="{{ url($recent->slug) }}" class="thumbnail">
                                    @php
                                        $rth = $recent->thumbnail;
                                        $m   = [];
                                        if (!$rth) {
                                            $rthUrl = asset('theme/images/blog/11.jpg');
                                        } elseif (str_starts_with($rth, 'http')) {
                                            if (preg_match('#/storage/(media/.+)$#', $rth, $m)) {
                                                $rthUrl = asset('storage/' . $m[1]);
                                            } else {
                                                $rthUrl = $rth;
                                            }
                                        } else {
                                            $rthUrl = asset('storage/' . ltrim($rth, '/'));
                                        }
                                    @endphp
                                    <img src="{{ $rthUrl }}" alt="recent-post" style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px;">
                                </a>
                                <div class="content">
                                    <span class="date">{{ $recent->published_at ? $recent->published_at->format('d/m/Y') : $recent->created_at->format('d/m/Y') }}</span>
                                    <a href="{{ url($recent->slug) }}">
                                        <h5 class="title">{{ Str::limit($recent->title, 40) }}</h5>
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="single-sidebar-widget newsletter" style="background-image: url('{{ asset('theme/images/blog/sidebar-bg.png') }}')">
                        <h4 class="title text-white">Bản tin của chúng tôi</h4>
                        <p class="disc text-white opacity-75">Đăng ký để nhận những thông báo mới nhất về sản phẩm và tin tức.</p>
                        <form action="{{ route('newsletter.subscribe') }}" method="POST" class="newsletter-form-sidebar">
                            @csrf
                            <input name="email" type="email" placeholder="Email của bạn" required>
                            <button type="submit" class="rts-btn btn-primary w-100 mt-3">Đăng ký</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.single-blog-list-style {
    display: flex;
    gap: 30px;
    background: #fff;
    padding: 20px;
    border-radius: 12px;
    border: 1px solid #f1f5f9;
    transition: all 0.3s ease;
    margin-bottom: 30px;
}
.single-blog-list-style:hover {
    box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    transform: translateY(-5px);
}
.single-blog-list-style .thumbnail {
    flex: 0 0 350px;
    overflow: hidden;
    border-radius: 10px;
}
.single-blog-list-style .thumbnail img {
    width: 100%;
    height: 250px;
    object-fit: cover;
    transition: 0.5s;
}
.single-blog-list-style:hover .thumbnail img {
    transform: scale(1.1);
}
.single-blog-list-style .blog-content {
    display: flex;
    flex-direction: column;
    justify-content: center;
}
.single-blog-list-style .top-area {
    display: flex;
    gap: 20px;
    margin-bottom: 15px;
}
.single-blog-list-style .top-area .single-meta {
    font-size: 14px;
    color: var(--color-body);
    display: flex;
    align-items: center;
    gap: 8px;
}
.single-blog-list-style .top-area .single-meta i {
    color: var(--color-primary);
}
.single-blog-list-style .title {
    font-size: 24px;
    font-weight: 700;
    margin-bottom: 15px;
    line-height: 1.4;
    transition: 0.3s;
}
.single-blog-list-style .title:hover {
    color: var(--color-primary);
}
.single-sidebar-widget {
    background: #fff;
    padding: 30px;
    border-radius: 12px;
    border: 1px solid #f1f5f9;
    margin-bottom: 40px;
}
.single-sidebar-widget .title {
    font-size: 18px;
    font-weight: 700;
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 1px solid #f1f5f9;
    position: relative;
}
.single-sidebar-widget .title::after {
    content: '';
    position: absolute;
    bottom: -1px;
    left: 0;
    width: 50px;
    height: 2px;
    background: var(--color-primary);
}
.search-form {
    position: relative;
}
.search-form input {
    width: 100%;
    height: 50px;
    border: 1px solid #f1f5f9;
    border-radius: 8px;
    padding: 0 50px 0 20px;
}
.search-form button {
    position: absolute;
    right: 0;
    top: 0;
    width: 50px;
    height: 50px;
    background: none;
    border: none;
    color: var(--color-primary);
}
.recent-post-wrapper .single-recent-post {
    display: flex;
    gap: 15px;
    margin-bottom: 20px;
}
.recent-post-wrapper .single-recent-post:last-child {
    margin-bottom: 0;
}
.recent-post-wrapper .single-recent-post .content .date {
    font-size: 12px;
    color: var(--color-body);
    display: block;
    margin-bottom: 5px;
}
.recent-post-wrapper .single-recent-post .content .title {
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 0;
    line-height: 1.4;
    border: none;
    padding: 0;
}
@media (max-width: 991px) {
    .single-blog-list-style {
        flex-direction: column;
    }
    .single-blog-list-style .thumbnail {
        flex: 0 0 100%;
    }
}
</style>
@endsection
