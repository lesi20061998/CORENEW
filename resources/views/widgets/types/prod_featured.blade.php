@php
    $layout = $config['layout'] ?? 'slider';
    $isSwiper = $layout === 'slider';
    $uniqueId = 'swiper-' . substr(md5(json_encode($config) . ($config['title'] ?? '')), 0, 8);
    $slidesPerView = (int)($config['columns'] ?? 6);
@endphp
<div id="{{ $uniqueId }}-wrapper" class="{{ $config['wrap_class'] ?? 'rts-grocery-feature-area rts-section-gapBottom' }}" {!! $sectionStyles !!}>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="title-area-between">
                    <h2 class="title-left">{{ $config['title'] ?? 'Featured Grocery' }}</h2>
                    @if($isSwiper)
                    <div class="next-prev-swiper-wrapper">
                        <div class="swiper-button-prev {{ $uniqueId }}-prev"><i class="fa-regular fa-chevron-left"></i></div>
                        <div class="swiper-button-next {{ $uniqueId }}-next"><i class="fa-regular fa-chevron-right"></i></div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                @if($isSwiper)
                <div class="category-area-main-wrapper-one">
                    <div class="swiper mySwiper-category-1 swiper-data" data-swiper='{
                        "spaceBetween":16,
                        "slidesPerView":{{ $slidesPerView }},
                        "loop": true,
                        "speed": 700,
                        "navigation":{
                            "nextEl":".{{ $uniqueId }}-next",
                            "prevEl":".{{ $uniqueId }}-prev"
                        },
                        "breakpoints":{
                        "0":{"slidesPerView":1,"spaceBetween": 12},
                        "480":{"slidesPerView":2,"spaceBetween":12},
                        "640":{"slidesPerView":2,"spaceBetween":16},
                        "840":{"slidesPerView":3,"spaceBetween":16},
                        "1140":{"slidesPerView":{{ min(5, $slidesPerView) }},"spaceBetween":16},
                        "1540":{"slidesPerView":{{ min(6, $slidesPerView) }},"spaceBetween":16},
                        "1840":{"slidesPerView":{{ $slidesPerView }},"spaceBetween":16}
                        }
                    }'>
                        <div class="swiper-wrapper">
                            @foreach($products as $product)
                            <div class="swiper-slide">
                                <x-product-card :product="$product" />
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @else
                <div class="row g-4 @if($layout == 'grid') mt--20 @endif">
                    @php
                        $gridClass = match($config['columns'] ?? '5') {
                            '2' => 'col-lg-6 col-md-6',
                            '3' => 'col-lg-4 col-md-6',
                            '4' => 'col-lg-3 col-md-6',
                            '6' => 'col-xxl-2 col-xl-2 col-lg-3 col-md-4 col-sm-6',
                            default => 'col-xxl-2 col-xl-3 col-lg-4 col-md-6 col-sm-6', // 5 columns (approx)
                        };
                    @endphp
                    @foreach($products as $product)
                    <div class="{{ $gridClass }} col-12">
                        <x-product-card :product="$product" />
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
