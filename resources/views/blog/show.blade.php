@extends('layouts.app')

@section('title', $post->title . ' - ' . setting('site_name', 'VietTinMart'))
@section('meta_description', $post->meta_description ?? Str::limit(strip_tags($post->excerpt ?? ''), 160))
@section('meta_keywords', $post->meta_keywords ?? setting('seo_meta_keywords', ''))
@section('canonical', $post->canonical_url ?? url()->current())
@section('og_type', 'article')
@section('og_image', $post->thumbnail ? asset($post->thumbnail) : setting('seo_og_image', asset('theme/images/fav.png')))
@section('twitter_card', 'summary_large_image')

@include('components.seo-schema', ['context' => 'post', 'model' => $post])

@section('content')

<div class="rts-breadcrumb-area breadcrumb-bg bg_image">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                <h1 class="title">Chi tiết bài viết</h1>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Trang chủ</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('blog.index') }}">Blog</a></li>
                        <li class="breadcrumb-item active">{{ Str::limit($post->title, 40) }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="rts-blog-details-section rts-section-gap">
    <div class="container">
        <div class="row">

            {{-- Main Content --}}
            <div class="col-xl-8 col-lg-8 col-md-12">
                <div class="blog-details-wrapper">
                    @php
                        $thumbUrl = $post->thumbnail;
                        if ($thumbUrl && !Str::startsWith($thumbUrl, ['http://', 'https://'])) {
                            $thumbUrl = Str::startsWith($thumbUrl, 'storage/') ? asset($thumbUrl) : asset('storage/' . $thumbUrl);
                        }
                    @endphp
                    @if($thumbUrl)
                    <div class="thumbnail mb--30">
                        <img src="{{ $thumbUrl }}" alt="{{ $post->title }}" class="img-fluid w-100">
                    </div>
                    @endif

                    <div class="blog-meta d-flex gap-3 mb--20">
                        <span><i class="fa-light fa-clock me-1"></i>{{ $post->published_at?->format('d/m/Y') ?? $post->created_at->format('d/m/Y') }}</span>
                        @if($post->author)
                        <span><i class="fa-regular fa-user me-1"></i>{{ $post->author->name }}</span>
                        @endif
                    </div>

                    <h1 class="blog-title mb--20">{{ $post->title }}</h1>

                    <div class="blog-content">
                        {!! $post->content !!}
                    </div>

                    {{-- Tags --}}
                    @if($post->tags && $post->tags->count())
                    <div class="blog-tags mt--30">
                        <strong>Tags:</strong>
                        @foreach($post->tags as $tag)
                        <a href="{{ route('blog.index', ['tag' => $tag->slug]) }}" class="tag-badge">{{ $tag->name }}</a>
                        @endforeach
                    </div>
                    @endif

                    {{-- Share --}}
                    <div class="blog-share mt--30 d-flex align-items-center gap-3">
                        <strong>Chia sẻ:</strong>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank" class="share-btn facebook">
                            <i class="fa-brands fa-facebook-f"></i>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($post->title) }}" target="_blank" class="share-btn twitter">
                            <i class="fa-brands fa-twitter"></i>
                        </a>
                    </div>
                </div>

                {{-- Related Posts --}}
                @if($relatedPosts->count())
                <div class="related-posts mt--60">
                    <h3 class="title-left mb--30">Bài viết liên quan</h3>
                    <div class="row g-4">
                        @foreach($relatedPosts as $related)
                        @php
                            $rThumb = $related->thumbnail;
                            if ($rThumb && !Str::startsWith($rThumb, ['http://', 'https://'])) {
                                $rThumb = Str::startsWith($rThumb, 'storage/') ? asset($rThumb) : asset('storage/' . $rThumb);
                            }
                            if (!$rThumb) $rThumb = asset('theme/images/blog/01.jpg');
                        @endphp
                        <div class="col-lg-4 col-md-6">
                            <div class="single-blog-area-start">
                                <a href="{{ route('blog.show', $related->slug) }}" class="thumbnail">
                                    <img src="{{ $rThumb }}" alt="{{ $related->title }}">
                                </a>
                                <div class="blog-body">
                                    <div class="top-area">
                                        <div class="single-meta">
                                            <i class="fa-light fa-clock"></i>
                                            <span>{{ $related->published_at?->format('d M, Y') }}</span>
                                        </div>
                                    </div>
                                    <a href="{{ route('blog.show', $related->slug) }}">
                                        <h4 class="title">{{ Str::limit($related->title, 60) }}</h4>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="col-xl-4 col-lg-4 col-md-12">
                <div class="blog-sidebar-wrapper">
                    <div class="single-sidebar-widget">
                        <h5 class="title">Tìm kiếm</h5>
                        <form action="{{ route('blog.index') }}" method="GET" class="sidebar-search">
                            <input type="text" name="q" placeholder="Tìm bài viết...">
                            <button type="submit"><i class="fa-light fa-magnifying-glass"></i></button>
                        </form>
                    </div>
                    @widgetArea('blog_sidebar')
                </div>
            </div>

        </div>
    </div>
</div>

@endsection
