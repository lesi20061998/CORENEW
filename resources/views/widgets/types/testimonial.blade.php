<div class="rts-testimonial-area rts-section-gapBottom" {!! $sectionStyles !!}>
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-12">
                <div class="title-area-between mb--40">
                    <div class="title-area-left">
                        <h2 class="title">{{ $config['title'] ?? 'Đánh giá khách hàng' }}</h2>
                        @if(!empty($config['subtitle']))
                            <p class="disc">{{ $config['subtitle'] }}</p>
                        @endif
                    </div>
                </div>
                <div class="swiper mySwiper-testimonial-1 swiper-data" data-swiper='{"spaceBetween":30,"slidesPerView":3,"loop": true,"speed": 1000,"autoplay":{"delay":5000},"breakpoints":{"320":{"slidesPerView":1},"768":{"slidesPerView":2},"992":{"slidesPerView":3}}}'>
                    <div class="swiper-wrapper">
                        @foreach($config['items'] ?? [] as $item)
                        <div class="swiper-slide">
                            <div class="testimonial-one-wrapper">
                                <div class="rating-area">
                                    @for($i=1; $i<=5; $i++)
                                        <i class="fa-solid fa-star {{ $i <= ($item['stars'] ?? 5) ? 'active' : '' }}"></i>
                                    @endfor
                                </div>
                                <div class="testi-content">
                                    <p class="disc">{{ $item['content'] ?? '' }}</p>
                                </div>
                                <div class="testi-author-area">
                                    @if(!empty($item['avatar']))
                                        <div class="author-img">
                                            <img src="{{ $item['avatar'] }}" alt="author">
                                        </div>
                                    @endif
                                    <div class="author-info">
                                        <h6 class="name">{{ $item['name'] ?? 'Khách hàng' }}</h6>
                                        @if(!empty($item['position']))
                                            <span class="designation">{{ $item['position'] }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="quote-icon">
                                    <i class="fa-solid fa-quote-right"></i>
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
