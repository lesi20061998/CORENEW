@php
    $columns = $config['columns'] ?? 4;
    $gridClass = match($columns) {
        '2' => 'col-lg-6 col-md-6 col-sm-12 col-12',
        '3' => 'col-lg-4 col-md-6 col-sm-12 col-12',
        '4' => 'col-lg-3 col-md-6 col-sm-12 col-12',
        default => 'col-lg-3 col-md-6 col-sm-12 col-12'
    };
@endphp

<!-- Promo Banners Area start -->
<div class="category-feature-area rts-section-gapTop" {!! $sectionStyles ?? '' !!}>
    <div class="container">
        <div class="row g-4">
            @foreach($config['items'] ?? [] as $item)
            <div class="{{ $gridClass }}">
                <div class="single-feature-card lazy {{ $item['bg_class'] ?? 'bg_image one' }}"
                     @if(!empty($item['image'])) data-bg="{{ $item['image'] }}" @endif>
                    <div class="content-area">
                        @if(!empty($item['badge']))
                            <a class="rts-btn btn-primary" href="{{ $item['btn_link'] ?? '#' }}">{{ $item['badge'] }}</a>
                        @endif
                        
                        <h3 class="title animated fadeIn">
                            {!! nl2br(e($item['title'] ?? '')) !!}
                            @if(!empty($item['subtitle']))
                                <br><span>{{ $item['subtitle'] }}</span>
                            @endif
                        </h3>

                        <a class="shop-now-goshop-btn" href="{{ $item['btn_link'] ?? '#' }}">
                            <span class="text">{{ $item['btn_text'] ?? 'Mua ngay' }}</span>
                            <div class="plus-icon">
                                <i class="fa-sharp fa-regular fa-plus"></i>
                            </div>
                            <div class="plus-icon">
                                <i class="fa-sharp fa-regular fa-plus"></i>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
<!-- Promo Banners Area end -->
