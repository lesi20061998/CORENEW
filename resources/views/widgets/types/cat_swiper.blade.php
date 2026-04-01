<div class="background-light-gray-color rts-section-gapBottom bg_light-1" {!! $sectionStyles ?? '' !!}>
    <div class="rts-caregory-area-one">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="category-area-main-wrapper-one">
                    <div class="swiper mySwiper-category-1 swiper-data" data-swiper='{"spaceBetween":12,"slidesPerView":{{ $config['slidesPerView'] ?? 10 }},"loop": {{ ($config['loop'] ?? true) ? 'true' : 'false' }},"speed": 1000,"breakpoints":{"0":{"slidesPerView":2,"spaceBetween": 12},"480":{"slidesPerView":3,"spaceBetween":12},"640":{"slidesPerView":4,"spaceBetween":12},"1140":{"slidesPerView":{{ $config['slidesPerView'] ?? 10 }},"spaceBetween":12}}}'>
                        <div class="swiper-wrapper">
                            @foreach($categories as $cat)
                            <div class="swiper-slide">
                                <a href="{{ route('shop.category', ['category_slug' => $cat->slug]) }}" class="single-category-one">
                                    <img src="{{ $cat->image ?: asset('theme/images/category/01.png') }}" alt="{{ $cat->name }}" loading="lazy">
                                    <p>{{ $cat->name }}</p>
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
</div>
<!-- rts category area end -->
