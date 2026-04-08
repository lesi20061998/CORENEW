{{-- Hero Slider Widget — maps to HeroSliderWidget --}}
<div class="rts-hero-section" {!! $sectionStyles ?? '' !!}>
    <div class="swiper rts-hero-slider">
        <div class="swiper-wrapper">
            @foreach($slides ?? [] as $slide)
            <div class="swiper-slide">
                <div class="hero-single-slide"
                    @if(!empty($slide['image']))
                        style="background-image: url('{{ str_starts_with($slide['image'], 'http') ? $slide['image'] : asset($slide['image']) }}'); background-size: cover; background-position: center;"
                    @endif>
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-7">
                                <div class="hero-content-wrapper">
                                    @if(!empty($slide['pre_title']))
                                    <span class="pre-title">{{ $slide['pre_title'] }}</span>
                                    @endif
                                    @if(!empty($slide['title']))
                                    <h1 class="title">{!! nl2br(e($slide['title'])) !!}</h1>
                                    @endif
                                    @if(!empty($slide['description']))
                                    <p class="disc">{{ $slide['description'] }}</p>
                                    @endif
                                    @if(!empty($slide['btn_text']))
                                    <a href="{{ $slide['btn_link'] ?? '#' }}" class="rts-btn btn-primary radious-sm with-icon">
                                        <div class="btn-text">{{ $slide['btn_text'] }}</div>
                                        <div class="arrow-icon"><i class="fa-light fa-arrow-right"></i></div>
                                        <div class="arrow-icon"><i class="fa-light fa-arrow-right"></i></div>
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="swiper-pagination"></div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof Swiper !== 'undefined') {
        new Swiper('.rts-hero-slider', {
            loop: true,
            autoplay: { delay: {{ $config['autoplay_delay'] ?? 4000 }}, disableOnInteraction: false },
            pagination: { el: '.swiper-pagination', clickable: true },
            navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
        });
    }
});
</script>
@endpush
