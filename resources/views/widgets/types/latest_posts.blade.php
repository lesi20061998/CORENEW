@php
    $columns = $config['columns'] ?? 4;
    $gridClass = match($columns) {
        '2' => 'col-lg-6 col-md-6 col-sm-12',
        '3' => 'col-lg-4 col-md-6 col-sm-12',
        '4' => 'col-lg-3 col-md-6 col-sm-12',
        default => 'col-lg-3 col-md-6 col-sm-12'
    };
@endphp

<!-- blog area start -->
<div class="blog-area-start rts-section-gapBottom" {!! $sectionStyles !!}>
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-12">
                <div class="title-area-between">
                    <h2 class="title-left mb--0">{{ $config['title'] ?? 'Tin tức & Bài viết' }}</h2>
                    @if($config['show_btn'] ?? false)
                        <div class="rts-btn-wrapper">
                            <a href="{{ $config['btn_link'] ?? '/blog' }}" class="rts-btn btn-primary radious-sm with-icon">
                                <div class="btn-text">{{ $config['btn_text'] ?? 'Xem tất cả' }}</div>
                                <div class="arrow-icon"><i class="fa-light fa-arrow-right"></i></div>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="cover-card-main-over">
                    <div class="row g-4">
            @forelse($posts as $post)
            <div class="{{ $gridClass }}">
                <div class="single-blog-area-start">
                    <a href="{{ $post->url ?? '#' }}" class="thumbnail">
                        <img class="lazy" data-src="{{ $post->image ?? asset('assets/images/blog/blog-one.jpg') }}" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" alt="blog">
                    </a>
                    <div class="blog-body">
                        <div class="top-area">
                            <div class="single-meta">
                                <i class="fa-light fa-clock"></i>
                                <span>{{ $post->published_at ? $post->published_at->format('d M, Y') : '01 Jan, 2024' }}</span>
                            </div>
                            @if($post->category)
                                <div class="single-meta">
                                    <i class="fa-regular fa-folder"></i>
                                    <span>{{ $post->category->name }}</span>
                                </div>
                            @endif
                        </div>
                        <a href="{{ $post->url ?? '#' }}">
                            <h4 class="title">{{ $post->name }}</h4>
                        </a>
                        @if($config['show_excerpt'] ?? false)
                            <p class="disc">{{ $post->short_description ?? Str::limit(strip_tags($post->description), 100) }}</p>
                        @endif
                    </div>
                </div>
            </div>
            @empty
                <div class="col-12 text-center py-5 text-muted">
                    <p>Chưa có bài viết nào.</p>
                </div>
            @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- blog area end -->
