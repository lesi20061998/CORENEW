@extends('layouts.app')

@section('title', ($post->meta_title ?: $post->title) . ' - ' . setting('site_name', 'VietTin Mart'))
@section('meta_description', $post->meta_description ?: $post->excerpt)
@section('meta_keywords', $post->meta_keywords)
@section('canonical', url($post->slug))
@section('og_type', 'article')
@section('og_image', $post->thumbnail ? asset($post->thumbnail) : asset(setting('site_og_image')))


@section('content')
    <x-breadcrumb :items="[
            ['label' => 'Cửa hàng', 'url' => route('home')],
            ['label' => 'Tin tức', 'url' => route('blog.index')],
            ['label' => 'Chi tiết bài viết']
        ]" />

    <!-- blog detail area start -->
    <div class="rts-blog-detail-area rts-section-gap">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-8">
                    <div class="blog-detail-content-wrapper">
                        <div class="thumbnail mb--40">
                            <img src="{{ $post->thumbnail ? asset($post->thumbnail) : asset('theme/images/blog/01.jpg') }}"
                                alt="blog-detail"
                                style="width: 100%; border-radius: 12px; height: 450px; object-fit: cover;">
                        </div>

                        <div class="blog-meta-area mb--20">
                            <div class="single-meta">
                                <i class="fa-light fa-calendar-days"></i>
                                <span>{{ $post->published_at ? $post->published_at->format('d/m/Y') : $post->created_at->format('d/m/Y') }}</span>
                            </div>
                            <div class="single-meta">
                                <i class="fa-light fa-user"></i>
                                <span>{{ optional($post->author)->name ?? 'Admin' }}</span>
                            </div>
                            @if($post->category)
                                <div class="single-meta">
                                    <i class="fa-light fa-folder-open"></i>
                                    <span>{{ $post->category->name }}</span>
                                </div>
                            @endif
                        </div>

                        <h1 class="title mb--30">{{ $post->title }}</h1>

                        <div class="content entry-content mb--40">
                            {!! $post->content !!}
                        </div>

                        <div
                            class="blog-footer border-top pt--30 d-flex flex-wrap justify-content-between align-items-center gap-3">
                            <div class="tags-area">
                                <span>Thẻ:</span>
                                @forelse($post->tags as $tag)
                                    <a href="#"
                                        class="badge bg-light text-dark text-decoration-none px-3 py-2 ms-2">{{ $tag->name }}</a>
                                @empty
                                    <span class="text-muted small ms-2">Không có thẻ nào.</span>
                                @endforelse
                            </div>

                            <div class="share-area">
                                <span>Chia sẻ:</span>
                                <div class="social-icons ms-3 d-inline-flex gap-2">
                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                                        target="_blank" class="rts-btn btn-primary"
                                        style="width: 40px; height: 40px; padding: 0; line-height: 40px;"><i
                                            class="fa-brands fa-facebook-f"></i></a>
                                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($post->title) }}"
                                        target="_blank" class="rts-btn btn-primary"
                                        style="width: 40px; height: 40px; padding: 0; line-height: 40px; background: #1DA1F2;"><i
                                            class="fa-brands fa-twitter"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Related posts -->
                    <div class="related-posts-area mt--60">
                        <h3 class="title mb--40">Các bài viết liên quan</h3>
                        <div class="row g-4">
                            @foreach($relatedPosts ?? [] as $related)
                                <div class="col-lg-4 col-md-6">
                                    <div class="single-blog-style-one border rounded p-3">
                                        <a href="{{ url($related->slug) }}" class="thumbnail">
                                            <img src="{{ $related->thumbnail ? asset($related->thumbnail) : asset('theme/images/blog/02.jpg') }}"
                                                alt="blog"
                                                style="width: 100%; height: 180px; object-fit: cover; border-radius: 8px;">
                                        </a>
                                        <div class="blog-content pt-3">
                                            <span
                                                class="date d-block mb-1 small text-muted">{{ $related->published_at ? $related->published_at->format('d/m/Y') : $related->created_at->format('d/m/Y') }}</span>
                                            <a href="{{ url($related->slug) }}">
                                                <h5 class="title h6 mb-0">{{ Str::limit($related->title, 50) }}</h5>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- Sidebar (reuse parts from index or build unique ones) -->
                    <div class="blog-sidebar-area">


                        <div class="single-sidebar-widget recent-post">
                            <h4 class="title">Bài viết mới nhất</h4>
                            <div class="recent-post-wrapper">
                                @php
                                    $latest = \App\Models\Post::where('status', 'published')->where('id', '!=', $post->id)->latest('published_at')->limit(5)->get();
                                @endphp
                                @foreach($latest as $l)
                                    <div class="single-recent-post">
                                        <a href="{{ url($l->slug) }}" class="thumbnail">
                                            <img src="{{ $l->thumbnail ? asset($l->thumbnail) : asset('theme/images/blog/11.jpg') }}"
                                                alt="recent-post"
                                                style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px;">
                                        </a>
                                        <div class="content">
                                            <span
                                                class="date">{{ $l->published_at ? $l->published_at->format('d/m/Y') : $l->created_at->format('d/m/Y') }}</span>
                                            <a href="{{ url($l->slug) }}">
                                                <h5 class="title">{{ Str::limit($l->title, 40) }}</h5>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="single-sidebar-widget banner-ad"
                            style="background: var(--color-primary); border: none;">
                            <h3 class="title text-white border-white">Sản phẩm tươi sạch</h3>
                            <p class="text-white opacity-75">Săn ưu đãi lên tới 30% cho các mặt hàng nông sản trong tuần
                                này.</p>
                            <a href="{{ route('shop.index') }}" class="rts-btn btn-white w-100 mt-4">Mua ngay</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .blog-meta-area {
            display: flex;
            gap: 30px;
            align-items: center;
        }

        .blog-meta-area .single-meta {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            color: var(--color-body);
        }

        .blog-meta-area .single-meta i {
            color: var(--color-primary);
        }

        .entry-content {
            font-size: 16px;
            line-height: 1.8;
            color: #444;
        }

        .entry-content p {
            margin-bottom: 25px;
        }

        .entry-content h2,
        .entry-content h3 {
            color: var(--color-heading);
            margin-top: 40px;
            margin-bottom: 20px;
        }

        .entry-content img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            margin: 30px 0;
        }

        .btn-white {
            background: #fff;
            color: var(--color-primary);
        }

        .btn-white:hover {
            background: var(--color-secondary);
            color: #fff;
        }

        .single-sidebar-widget .author-area {
            padding-top: 10px;
        }
    </style>
@endsection