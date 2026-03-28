@php
    $items = $config['items'] ?? [];
@endphp

@if(count($items))
<div class="category-feature-area rts-section-gapTop">
    <div class="container">
        <div class="row g-4">
            @foreach($items as $item)
            <div class="col-lg-3 col-md-6 col-sm-12 col-12">
                <div class="single-feature-card bg_image {{ $item['bg_class'] ?? '' }}">
                    <div class="content-area">
                        @if(!empty($item['badge']))
                        <a href="{{ $item['btn_link'] ?? '/shop' }}" class="rts-btn btn-primary">{{ $item['badge'] }}</a>
                        @endif
                        <h3 class="title">
                            {{ $item['title'] ?? '' }}
                            @if(!empty($item['subtitle']))
                            <span>{{ $item['subtitle'] }}</span>
                            @endif
                        </h3>
                        <a href="{{ $item['btn_link'] ?? '/shop' }}" class="shop-now-goshop-btn">
                            <span class="text">{{ $item['btn_text'] ?? 'Mua ngay' }}</span>
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
@endif
