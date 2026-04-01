@php
    $layout = $config['layout'] ?? 'grid';
    $isSwiper = $layout === 'swiper' || ($config['is_swiper'] ?? false) || str_contains($config['wrap_class'] ?? '', 'top-tranding-product') || str_contains($config['wrap_class'] ?? '', 'featured-product');
    $uniqueId = 'swiper-' . substr(md5(json_encode($config) . ($config['title'] ?? '')), 0, 8);
    
    // Adjust swiper data based on columns if provided
    $slidesPerView = (int)($config['columns'] ?? 6);
    $swiperData = [
        "spaceBetween" => 20,
        "slidesPerView" => $slidesPerView,
        "loop" => true,
        "speed" => 1000,
        "autoplay" => ["delay" => 5000, "pauseOnMouseEnter" => true],
        "navigation" => [
            "nextEl" => "." . $uniqueId . "-next",
            "prevEl" => "." . $uniqueId . "-prev"
        ],
        "breakpoints" => [
            "320" => ["slidesPerView" => 1],
            "480" => ["slidesPerView" => 2],
            "768" => ["slidesPerView" => 3],
            "1200" => ["slidesPerView" => min(4, $slidesPerView)],
            "1500" => ["slidesPerView" => $slidesPerView]
        ]
    ];
@endphp
<!-- rts grocery feature area start -->
<div id="{{ $uniqueId }}-wrapper" class="{{ $config['wrap_class'] ?? 'rts-grocery-feature-area rts-section-gapBottom' }}" {!! $sectionStyles !!}>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="title-area-between">
                    <h2 class="title-left">{{ $config['title'] ?? 'Featured Grocery' }}</h2>
                    <div class="next-prev-swiper-wrapper">
                        <div class="swiper-button-prev"><i class="fa-regular fa-chevron-left"></i></div>
                        <div class="swiper-button-next"><i class="fa-regular fa-chevron-right"></i></div>
                    </div>
                </div>
            </div>
        </div>

        @if($isSwiper)
        <div class="row">
            <div class="col-lg-12 mt--20">
                <div class="swiper {{ $uniqueId }} swiper-data" data-swiper='{!! json_encode($swiperData) !!}'>
                    <div class="swiper-wrapper">
                        @foreach($products as $product)
                        <div class="swiper-slide">
                            <x-product-card :product="$product" />
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        
        @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const wrapper = document.getElementById('{{ $uniqueId }}-wrapper');
                if(wrapper) {
                    const prevBtn = wrapper.querySelector('.swiper-button-prev');
                    const nextBtn = wrapper.querySelector('.swiper-button-next');
                    if(prevBtn) prevBtn.classList.add('{{ $uniqueId }}-prev');
                    if(nextBtn) nextBtn.classList.add('{{ $uniqueId }}-next');
                }
            });
        </script>
        @endpush

        @else
        <div class="row g-4 mt--20">
            @foreach($products as $product)
            <div class="col-xxl-2 col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                <x-product-card :product="$product" />
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
<!-- rts grocery feature area end -->
