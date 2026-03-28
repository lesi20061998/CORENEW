@php
    $title      = $config['title']        ?? 'Tin tức & Bài viết';
    $columns    = $config['columns']      ?? '4';
    $showExcerpt= $config['show_excerpt'] ?? false;
    $showBtn    = $config['show_btn']     ?? false;
    $btnText    = $config['btn_text']     ?? 'Xem tất cả bài viết';
    $btnLink    = $config['btn_link']     ?? '/blog';
    $colClass   = match((string)$columns) {
        '2' => 'col-lg-6 col-md-6 col-sm-12',
        '3' => 'col-lg-4 col-md-6 col-sm-12',
        default => 'col-lg-3 col-md-6 col-sm-12',
    };
@endphp

@if($posts->count())
<div class="blog-area-start rts-section-gapBottom">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="title-area-between">
                    <h2 class="title-left mb--0">{{ $title }}</h2>
                    @if($showBtn)
                    <a href="{{ $btnLink }}" class="rts-btn btn-primary radious-sm">{{ $btnText }}</a>
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="cover-card-main-over">
                    <div class="row g-4">
                        @foreach($posts as $post)
                        @php
                            $thumb = $post->thumbnail ? asset('storage/' . $post->thumbnail) : asset('theme/images/blog/01.jpg');
                        @endphp
                        <div class="{{ $colClass }}">
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
                                        @if($post->category)
                                        <div class="single-meta">
                                            <i class="fa-regular fa-folder"></i>
                                            <span>{{ $post->category->name ?? '' }}</span>
                                        </div>
                                        @endif
                                    </div>
                                    <a href="{{ route('blog.show', $post->slug) }}">
                                        <h4 class="title">{{ $post->title }}</h4>
                                    </a>
                                    @if($showExcerpt && $post->excerpt)
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
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
