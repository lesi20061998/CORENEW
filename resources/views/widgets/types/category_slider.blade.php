@php
    $title         = $config['title']          ?? '';
    $slidesPerView = $config['slides_per_view'] ?? 10;
    $style         = $config['style']          ?? 'circle';
    $showCount     = $config['show_count']     ?? false;
    $source        = $config['source']         ?? 'db';
    $manualItems   = $config['items']          ?? [];

    // Dùng DB hoặc manual items
    $items = $source === 'db' ? $categories : collect($manualItems);
@endphp

@if($items->count())
<div class="rts-caregory-area-one rts-section-gap">
    <div class="container">
        @if($title)
        <div class="row mb--30">
            <div class="col-lg-12">
                <h2 class="title-left">{{ $title }}</h2>
            </div>
        </div>
        @endif
        <div class="row">
            <div class="col-lg-12">
                <div class="category-area-main-wrapper-one">
                    <div class="swiper mySwiper-category-1 swiper-data" data-swiper='{
                        "spaceBetween": 12,
                        "slidesPerView": {{ $slidesPerView }},
                        "loop": true,
                        "speed": 1000,
                        "breakpoints": {
                            "0":    {"slidesPerView": 2,  "spaceBetween": 12},
                            "480":  {"slidesPerView": 3,  "spaceBetween": 12},
                            "640":  {"slidesPerView": 4,  "spaceBetween": 12},
                            "840":  {"slidesPerView": 6,  "spaceBetween": 12},
                            "1140": {"slidesPerView": {{ $slidesPerView }}, "spaceBetween": 12}
                        }
                    }'>
                        <div class="swiper-wrapper">
                            @foreach($items as $item)
                            @php
                                $name  = $source === 'db' ? $item->name  : ($item['name']  ?? '');
                                $image = $source === 'db' ? ($item->image ? asset('storage/' . $item->image) : asset('theme/images/category/01.png')) : ($item['image'] ?? asset('theme/images/category/01.png'));
                                $link  = $source === 'db' ? route('shop.index', ['category' => $item->slug]) : ($item['link'] ?? '/shop');
                                $count = $source === 'db' ? $item->products_count : ($item['count'] ?? '');
                            @endphp
                            <div class="swiper-slide">
                                <a href="{{ $link }}" class="single-category-one">
                                    <img src="{{ $image }}" alt="{{ $name }}">
                                    <p>{{ $name }}</p>
                                    @if($showCount && $count)
                                    <span>{{ $count }} sản phẩm</span>
                                    @endif
                                </a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
