<div class="rts-cuystomers-feedback-area rts-section-gap2">
    <div class="container-3">
        <div class="row">
            <div class="col-lgl-12">
                <div class="title-area-left pl--0">
                    <h2 class="title-left mb--0">
                        {{ $config['title'] ?? 'Customer Feedbacks' }}
                    </h2>
                </div>
            </div>
        </div>
        <div class="row mt--50">
            <div class="col-lg-12">
                <div class="customers-feedback-area-main-wrapper">
                    <!-- rts category area satart -->
                    <div class="rts-caregory-area-one ">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="category-area-main-wrapper-one">
                                    <div class="swiper mySwiper-category-1 swiper-data" data-swiper='{
                                        "spaceBetween":24,
                                        "slidesPerView":2,
                                        "loop": true,
                                        "speed": 1000,
                                        "navigation":{
                                            "nextEl":".swiper-button-nexts",
                                            "prevEl":".swiper-button-prevs"
                                            },
                                        "breakpoints":{
                                        "0":{
                                            "slidesPerView":1,
                                            "spaceBetween": 24},
                                        "320":{
                                            "slidesPerView":1,
                                            "spaceBetween":24},
                                        "480":{
                                            "slidesPerView":1,
                                            "spaceBetween":24},
                                        "640":{
                                            "slidesPerView":1,
                                            "spaceBetween":24},
                                        "840":{
                                            "slidesPerView":1,
                                            "spaceBetween":24},
                                        "1140":{
                                            "slidesPerView":2,
                                            "spaceBetween":24}
                                        }
                                    }'>
                                        <div class="swiper-wrapper">
                                            @foreach($config['items'] ?? [] as $item)
                                            <div class="swiper-slide">
                                                <div class="single-customers-feedback-area">
                                                    <div class="top-thumbnail-area">
                                                        <div class="left">
                                                            <img src="{{ asset($item['avatar'] ?: 'theme/images/testimonial/01.png') }}" alt="author">
                                                            <div class="information">
                                                                <h4 class="title">
                                                                    {{ $item['name'] ?? '' }}
                                                                </h4>
                                                                <span>{{ $item['role'] ?? '' }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="right">
                                                            <img src="{{ asset($item['logo'] ?: 'theme/images/testimonial/02.png') }}" alt="logo">
                                                        </div>
                                                    </div>
                                                    <div class="body-content">
                                                        <p class="disc">
                                                            “{{ $item['content'] ?? '' }}”
                                                        </p>
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
                    <!-- rts category area end -->
                </div>
            </div>
        </div>
    </div>
</div>
