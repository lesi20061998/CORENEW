@push('styles')
<style>
    .category-area-main-wrapper-one {
        height: auto;
        min-height: auto;
        position: relative;
        overflow: hidden;
        background: #f8f9fa;
        border-radius: 12px;
    }
    /* Hide slides until swiper is ready to avoid layout jumping */
    .mySwiper-hero-main:not(.swiper-initialized) .swiper-slide:not(:first-child) {
        display: none;
    }
    .mySwiper-hero-main:not(.swiper-initialized) .swiper-slide:first-child {
        opacity: 0.8;
    }
    .banner-bg-image {
        aspect-ratio: 1170/460;
        height: auto;
        min-height: auto;
    }
    @media (max-width: 768px) {
        .category-area-main-wrapper-one, .banner-bg-image { min-height: auto; height: auto; aspect-ratio: 16/9; }
    }
</style>
@endpush
<div class="background-light-gray-color rts-section-gapTop bg_light-1 pt_sm--20" {!! $sectionStyles ?? '' !!}>
    <div class="rts-banner-area-one mb--20">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="category-area-main-wrapper-one">
                    <div class="swiper mySwiper-hero-main swiper-data" data-swiper='{"spaceBetween":1,"slidesPerView":1,"loop": true,"speed": 2000,"autoplay":{"delay":{{ $config['autoplay_delay'] ?? 4000 }}},"navigation":{"nextEl":".swiper-button-next","prevEl":".swiper-button-prev"}}'>
                        <div class="swiper-wrapper">
                            @foreach($slides as $slide)
                            <div class="swiper-slide">
                                <div class="banner-bg-image bg_image {{ $slide['bg_class'] ?? 'bg_one-banner' }} ptb--120 ptb_md--80 ptb_sm--60" 
                                    @if(!empty($slide['image'])) 
                                        style="background-image: url('{{ $slide['image'] }}')"
                                    @endif>
                                    @if(!empty($slide['image']))
                                        <img src="{{ $slide['image'] }}" alt="banner" style="display:none;" {{ $loop->first ? 'loading=eager' : 'loading=lazy' }}>
                                    @endif
                                    <div class="banner-one-inner-content">
                                        <span class="pre">{{ $slide['pre_title'] ?? $slide['pre_text'] ?? '' }}</span>
                                        <h1 class="title">{!! nl2br(e($slide['title'] ?? '')) !!}</h1>
                                        @if(!empty($slide['description']))
                                            <p class="disc">{!! nl2br(e($slide['description'])) !!}</p>
                                        @endif
                                            <a href="{{ $slide['btn_link'] ?? '#' }}" class="rts-btn btn-primary radious-sm with-icon">
                                                <div class="btn-text">{{ $slide['btn_text'] ?? 'Mua ngay' }}</div>
                                                <div class="arrow-icon"><i class="fa-light fa-arrow-right"></i></div>
                                                <div class="arrow-icon"><i class="fa-light fa-arrow-right"></i></div>
                                            </a>
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
<!-- rts banner area end -->
