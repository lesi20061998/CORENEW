@php
    $layout = $config['layout'] ?? 'slider';
    $uniqueId = 'bs-' . $widget->id;
    
    // Externalized Classes
    $outerClass = $config['outer_class'] ?? match($layout) {
        'slider' => 'rts-banner-area-one mb--30',
        'grid'   => 'category-feature-area rts-section-gapTop',
        default  => 'rts-banner-area-two mt--60'
    };
    $containerClass = $config['container_class'] ?? 'container';
    $rowClass = $config['row_class'] ?? 'row';
    $titleClass = $config['title_class'] ?? 'title';
    $btnClass = $config['btn_class'] ?? 'rts-btn btn-primary';
@endphp

@if($layout === 'slider')
    <!-- Widget #{{ $widget->id }}: Banner Slider (Style 1) -->
    <div class="{{ $outerClass }} h1-hero-variant" {!! $sectionStyles !!}>
        <div class="{{ $containerClass }}">
            <div class="{{ $rowClass }}">
                <div class="col-lg-12">
                    <div class="category-area-main-wrapper-one">
                        <div class="swiper mySwiper-category-1 swiper-data" data-swiper='{
                            "spaceBetween":1,
                            "slidesPerView":1,
                            "loop": true,
                            "speed": 1500,
                            "autoplay":{"delay":{{ $config['autoplay_delay'] ?? 4000 }}},
                            "navigation":{"nextEl":".{{ $uniqueId }}-next","prevEl":".{{ $uniqueId }}-prev"},
                            "breakpoints":{"0":{"slidesPerView":1},"1140":{"slidesPerView":1}}
                        }'>
                            <div class="swiper-wrapper">
                                @foreach($slides as $item)
                                <div class="swiper-slide">
                                    <div class="banner-bg-image bg_image {{ $item['bg_class'] ?? 'bg_one-banner' }} ptb--120 ptb_md--80 ptb_sm--60"
                                         @if(!empty($item['image'])) style="background-image: url('{{ $item['image'] }}')" @endif>
                                        <div class="banner-one-inner-content">
                                            @if(!empty($item['subtitle']))
                                                <span class="pre">{{ $item['subtitle'] }}</span>
                                            @endif
                                            <h1 class="{{ $titleClass }}">{!! nl2br(e($item['title'] ?? '')) !!}</h1>
                                            @if(!empty($item['btn_text']))
                                            <a href="{{ $item['btn_link'] ?? '#' }}" class="{{ $btnClass }} radious-sm with-icon">
                                                <div class="btn-text">{{ $item['btn_text'] }}</div>
                                                <div class="arrow-icon"><x-theme-icon name="arrow-right" /></div>
                                            </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <button class="swiper-button-next {{ $uniqueId }}-next"><x-theme-icon name="arrow-right" /></button>
                            <button class="swiper-button-prev {{ $uniqueId }}-prev"><x-theme-icon name="arrow-left" /></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@elseif($layout === 'hero_2')
    <!-- Widget #{{ $widget->id }}: Hero Split (Style 2) -->
    <div class="rts-banner-area-two mt--30 {{ $config['outer_class'] ?? '' }}" {!! $sectionStyles !!}>
        <div class="{{ $containerClass }}">
            <div class="{{ $rowClass }} align-items-center">
                @foreach($slides as $item)
                <div class="col-lg-12">
                    <div class="banner-area-two-start" style="padding: 0;">
                        <div class="banner-bg-iamge-area bg_image {{ $item['bg_class'] ?? 'bg_banner-2' }}" 
                             style="min-height: 450px; border-radius: 12px; @if(!empty($item['image'])) background-image: url('{{ $item['image'] }}') @endif">
                            <div class="content" style="padding: 60px;">
                                @if(!empty($item['subtitle'])) <span class="pre-title" style="color: var(--color-primary); font-weight: 700;">{{ $item['subtitle'] }}</span> @endif
                                <h1 class="{{ $titleClass }}" style="font-size: 48px; margin-top: 10px;">{!! nl2br(e($item['title'] ?? '')) !!}</h1>
                                <div class="rts-btn-banner-area" style="margin-top: 30px;">
                                    @if(!empty($item['btn_text']))
                                    <a href="{{ $item['btn_link'] ?? '#' }}" class="{{ $btnClass }} radious-sm with-icon">
                                        <div class="btn-text">{{ $item['btn_text'] }}</div>
                                        <div class="arrow-icon"><x-theme-icon name="arrow-right" /></div>
                                    </a>
                                    @endif
                                    @if(!empty($item['price']))
                                    <div class="price-area">
                                        <span>từ</span>
                                        <h3 class="title">{{ $item['price'] }}</h3>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @break {{-- Only show first slide for this layout if multiple provided --}}
                @endforeach
            </div>
        </div>
    </div>

@elseif($layout === 'hero_3')
    <!-- Widget #{{ $widget->id }}: Hero Centered (Style 3) -->
    <div class="rts-banner-area-three mt--30 {{ $config['outer_class'] ?? '' }}" {!! $sectionStyles !!}>
        <div class="{{ $containerClass }}">
            @foreach($slides as $item)
            <div class="banner-bg-image bg_image {{ $item['bg_class'] ?? '' }} ptb--150"
                 style="background-color: #f8f9fa; border-radius: 20px; text-align: center; @if(!empty($item['image'])) background-image: url('{{ $item['image'] }}') @endif">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <div class="banner-content">
                                @if(!empty($item['subtitle']))
                                    <span class="pre-title mb--15 d-block" style="letter-spacing: 2px; text-transform: uppercase; font-weight: 600;">{{ $item['subtitle'] }}</span>
                                @endif
                                <h1 class="title mb--30" style="font-size: 56px;">{!! nl2br(e($item['title'] ?? '')) !!}</h1>
                                @if(!empty($item['btn_text']))
                                    <a href="{{ $item['btn_link'] ?? '#' }}" class="rts-btn btn-primary radious-sm">
                                        {{ $item['btn_text'] }} <x-theme-icon name="arrow-right" class="ml--10" />
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @break
            @endforeach
        </div>
    </div>

@elseif($layout === 'grid')
    @php
        $columns = $config['columns'] ?? 4;
        $gridClass = $config['col_class'] ?? match((string)$columns) {
            '2' => 'col-lg-6 col-md-6 col-sm-12',
            '3' => 'col-lg-4 col-md-6 col-sm-12',
            default => 'col-lg-3 col-md-6 col-sm-12'
        };
    @endphp
    <!-- Widget #{{ $widget->id }}: Promo Banners Grid -->
    <div class="{{ $outerClass }}" {!! $sectionStyles !!}>
        <div class="{{ $containerClass }}">
            <div class="{{ $rowClass }} g-4">
                @foreach($slides as $item)
                <div class="{{ $gridClass }}">
                    <div class="single-feature-card lazy bg_image {{ $item['bg_class'] ?? 'one' }}"
                         @if(!empty($item['image'])) data-bg="{{ $item['image'] }}" @endif>
                        <div class="content-area">
                            @if(!empty($item['badge']))
                                <a class="{{ $btnClass }}" href="{{ $item['btn_link'] ?? '#' }}">{{ $item['badge'] }}</a>
                            @endif
                            <h3 class="{{ $titleClass }} animated fadeIn">
                                {!! nl2br(e($item['title'] ?? '')) !!}
                                @if(!empty($item['subtitle']))
                                    <br><span>{{ $item['subtitle'] }}</span>
                                @endif
                            </h3>
                            <a class="shop-now-goshop-btn" href="{{ $item['btn_link'] ?? '#' }}">
                                <span class="text">{{ $item['btn_text'] ?? 'Mua ngay' }}</span>
                                <div class="plus-icon"><x-theme-icon name="plus" /></div>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

@else
    <!-- Widget #{{ $widget->id }}: Single Banner -->
    <div class="{{ $outerClass }}" {!! $sectionStyles !!}>
        <div class="{{ $containerClass }}">
            @foreach($slides as $item)
            <div class="banner-area-two-start">
                <div class="banner-bg-iamge-area bg_banner-2 bg_image" @if(!empty($item['image'])) style="background-image: url('{{ $item['image'] }}')" @endif>
                    <div class="content">
                        @if(!empty($item['subtitle'])) <span class="pre-title">{{ $item['subtitle'] }}</span> @endif
                        <h1 class="{{ $titleClass }}">{!! nl2br(e($item['title'] ?? '')) !!}</h1>
                        <div class="rts-btn-banner-area">
                            @if(!empty($item['btn_text']))
                            <a href="{{ $item['btn_link'] ?? '#' }}" class="{{ $btnClass }} radious-sm with-icon">
                                <div class="btn-text">{{ $item['btn_text'] }}</div>
                                <div class="arrow-icon"><i class="fa-light fa-arrow-right"></i></div>
                            </a>
                            @endif
                            @if(!empty($item['price']))
                            <div class="price-area">
                                <span>từ</span>
                                <h3 class="title">{{ $item['price'] }}</h3>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
@endif
