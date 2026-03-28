@php
    $slides   = $config['slides']   ?? [];
    $autoplay = $config['autoplay'] ?? true;
    $interval = $config['interval'] ?? 4000;
    $style    = $config['style']    ?? 'fullwidth';
@endphp

@if(count($slides))
<div class="background-light-gray-color rts-section-gap bg_light-1 pt_sm--20">
    <div class="rts-banner-area-one mb--30">
        <div class="{{ $style === 'boxed' ? 'container' : 'container' }}">
            <div class="row">
                <div class="col-lg-12">
                    <div class="category-area-main-wrapper-one">
                        <div class="swiper mySwiper-category-1 swiper-data" data-swiper='{
                            "spaceBetween": 0,
                            "slidesPerView": 1,
                            "loop": true,
                            "speed": 2000,
                            "autoplay": {{ $autoplay ? '{"delay": ' . $interval . '}' : 'false' }},
                            "navigation": {"nextEl": ".swiper-button-next", "prevEl": ".swiper-button-prev"}
                        }'>
                            <div class="swiper-wrapper">
                                @foreach($slides as $slide)
                                <div class="swiper-slide">
                                    <div class="banner-bg-image bg_image {{ $slide['bg_class'] ?? '' }} ptb--120 ptb_md--80 ptb_sm--60"
                                        @if(!empty($slide['bg_image'])) style="background-image: url('{{ asset('storage/' . $slide['bg_image']) }}')" @endif>
                                        <div class="banner-one-inner-content">
                                            @if(!empty($slide['pre_title']))
                                            <span class="pre">{{ $slide['pre_title'] }}</span>
                                            @endif
                                            @if(!empty($slide['title']))
                                            <h1 class="title">{!! nl2br(e($slide['title'])) !!}</h1>
                                            @endif
                                            @if(!empty($slide['description']))
                                            <p class="disc">{{ $slide['description'] }}</p>
                                            @endif
                                            <div class="rts-btn-banner-area">
                                                @if(!empty($slide['btn_text']))
                                                <a href="{{ $slide['btn_link'] ?? '/shop' }}" class="rts-btn btn-primary radious-sm with-icon">
                                                    <div class="btn-text">{{ $slide['btn_text'] }}</div>
                                                    <div class="arrow-icon"><i class="fa-light fa-arrow-right"></i></div>
                                                    <div class="arrow-icon"><i class="fa-light fa-arrow-right"></i></div>
                                                </a>
                                                @endif
                                                @if(!empty($slide['price']))
                                                <div class="price-area">
                                                    <span>{{ $slide['price_label'] ?? 'từ' }}</span>
                                                    <h3 class="title">{{ $slide['price'] }}</h3>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <button class="swiper-button-next"><i class="fa-regular fa-arrow-right"></i></button>
                            <button class="swiper-button-prev"><i class="fa-regular fa-arrow-left"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
