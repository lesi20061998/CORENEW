@php
    $title    = $config['title']    ?? 'Khách hàng nói gì về chúng tôi';
    $subtitle = $config['subtitle'] ?? '';
    $items    = $config['items']    ?? [];
@endphp

@if(count($items))
<div class="rts-testimonial-area rts-section-gap bg_light-1">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="title-area-between mb--40">
                    <h2 class="title-left">{{ $title }}</h2>
                    @if($subtitle)
                    <p>{{ $subtitle }}</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="swiper mySwiper-testimonial swiper-data" data-swiper='{
                    "spaceBetween": 24,
                    "slidesPerView": 3,
                    "loop": true,
                    "autoplay": {"delay": 4000},
                    "breakpoints": {
                        "0":    {"slidesPerView": 1},
                        "768":  {"slidesPerView": 2},
                        "1140": {"slidesPerView": 3}
                    }
                }'>
                    <div class="swiper-wrapper">
                        @foreach($items as $item)
                        <div class="swiper-slide">
                            <div class="single-testimonial-area">
                                <div class="stars">
                                    @for($s = 1; $s <= 5; $s++)
                                    <i class="fa-{{ $s <= ($item['stars'] ?? 5) ? 'solid' : 'regular' }} fa-star" style="color: #f5a623;"></i>
                                    @endfor
                                </div>
                                <p class="disc">{{ $item['content'] ?? '' }}</p>
                                <div class="author-area">
                                    @if(!empty($item['avatar']))
                                    <div class="thumbnail">
                                        <img src="{{ asset('storage/' . $item['avatar']) }}" alt="{{ $item['name'] ?? '' }}">
                                    </div>
                                    @endif
                                    <div class="info">
                                        <h5 class="title">{{ $item['name'] ?? '' }}</h5>
                                        @if(!empty($item['position']))
                                        <span>{{ $item['position'] }}</span>
                                        @endif
                                    </div>
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
@endif
