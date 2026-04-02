<div class="rts-banner-area-one mb--30" {!! $sectionStyles ?? '' !!}>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="category-area-main-wrapper-one">
                    <div class="swiper mySwiper-category-1 swiper-data" data-swiper='{
                        "spaceBetween":1,
                        "slidesPerView":1,
                        "loop": true,
                        "speed": 2000,
                        "autoplay":{
                            "delay":"{{ $config['autoplay_delay'] ?? 4000 }}"
                        },
                        "navigation":{
                            "nextEl":".swiper-button-next",
                            "prevEl":".swiper-button-prev"
                        },
                        "breakpoints":{
                        "0":{
                            "slidesPerView":1,
                            "spaceBetween": 0},
                        "320":{
                            "slidesPerView":1,
                            "spaceBetween":0},
                        "480":{
                            "slidesPerView":1,
                            "spaceBetween":0},
                        "640":{
                            "slidesPerView":1,
                            "spaceBetween":0},
                        "840":{
                            "slidesPerView":1,
                            "spaceBetween":0},
                        "1140":{
                            "slidesPerView":1,
                            "spaceBetween":0}
                        }
                    }'>
                        <div class="swiper-wrapper">
                            @foreach($slides as $slide)
                            <div class="swiper-slide">
                                <div class="banner-bg-image bg_image {{ $slide['bg_class'] ?? 'bg_one-banner' }} ptb--120 ptb_md--80 ptb_sm--60"
                                     @if(!empty($slide['image'])) style="background-image: url('{{ $slide['image'] }}')" @endif>
                                    <div class="banner-one-inner-content">
                                        <span class="pre">{{ $slide['pre_title'] ?? '' }}</span>
                                        <h1 class="title">
                                            {!! nl2br(e($slide['title'])) !!}
                                        </h1>
                                        @if(!empty($slide['description']))
                                        <p class="disc">{!! nl2br(e($slide['description'])) !!}</p>
                                        @endif
                                        <a href="{{ $slide['btn_link'] ?? '#' }}" class="rts-btn btn-primary radious-sm with-icon">
                                            <div class="btn-text">
                                                {{ $slide['btn_text'] ?? 'Shop Now' }}
                                            </div>
                                            <div class="arrow-icon">
                                                <i class="fa-light fa-arrow-right"></i>
                                            </div>
                                            <div class="arrow-icon">
                                                <i class="fa-light fa-arrow-right"></i>
                                            </div>
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
