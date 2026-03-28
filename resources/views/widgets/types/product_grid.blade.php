@php
    $title      = $config['title']      ?? 'Sản phẩm';
    $layout     = $config['layout']     ?? 'slider';
    $columns    = $config['columns']    ?? '4';
    $showBtn    = $config['show_btn']   ?? true;
    $btnText    = $config['btn_text']   ?? 'Xem tất cả';
    $btnLink    = $config['btn_link']   ?? '/shop';
    $tabs       = $config['tabs']       ?? [];
    $wrapClass  = $config['wrap_class'] ?? 'rts-grocery-feature-area rts-section-gapBottom';
@endphp

<div class="{{ $wrapClass }}">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="title-area-between">
                    <h2 class="title-left">{{ $title }}</h2>
                    @if($layout === 'slider')
                    <div class="next-prev-swiper-wrapper">
                        <div class="swiper-button-prev"><i class="fa-regular fa-chevron-left"></i></div>
                        <div class="swiper-button-next"><i class="fa-regular fa-chevron-right"></i></div>
                    </div>
                    @endif
                    @if($layout === 'grid_tabs' && count($tabs))
                    <ul class="nav nav-tabs best-selling-grocery" role="tablist">
                        @foreach($tabs as $i => $tab)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ $i === 0 ? 'active' : '' }}"
                                data-bs-toggle="tab"
                                data-bs-target="#tab-{{ $widget->id }}-{{ $i }}"
                                type="button">{{ $tab['label'] ?? '' }}</button>
                        </li>
                        @endforeach
                    </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        @if($layout === 'slider')
        <div class="row">
            <div class="col-lg-12">
                <div class="swiper mySwiper-product swiper-data" data-swiper='{
                    "spaceBetween": 20,
                    "slidesPerView": {{ $columns }},
                    "loop": true,
                    "navigation": {"nextEl": ".swiper-button-next", "prevEl": ".swiper-button-prev"},
                    "breakpoints": {
                        "0":    {"slidesPerView": 1},
                        "480":  {"slidesPerView": 2},
                        "768":  {"slidesPerView": 3},
                        "1140": {"slidesPerView": {{ $columns }}}
                    }
                }'>
                    <div class="swiper-wrapper">
                        @foreach($products as $product)
                        <div class="swiper-slide">
                            @include('widgets.partials.product-card', ['product' => $product])
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        @elseif($layout === 'grid_tabs' && count($tabs))
        <div class="row">
            <div class="col-lg-12">
                <div class="tab-content">
                    @foreach($tabs as $i => $tab)
                    @php
                        $tabProducts = \App\Widgets\Types\ProductGridWidget::fetchProducts(
                            $config,
                            $tab['filter'] ?? 'all',
                            !empty($tab['category_id']) ? (int)$tab['category_id'] : null
                        );
                    @endphp
                    <div class="tab-pane fade {{ $i === 0 ? 'show active' : '' }}"
                        id="tab-{{ $widget->id }}-{{ $i }}">
                        <div class="row g-4">
                            @foreach($tabProducts as $product)
                            <div class="col-xxl-2 col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12">
                                @include('widgets.partials.product-card', ['product' => $product])
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        @else
        <div class="row g-4">
            @foreach($products as $product)
            <div class="col-xxl-2 col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12">
                @include('widgets.partials.product-card', ['product' => $product])
            </div>
            @endforeach
        </div>
        @endif

        @if($showBtn && $btnText)
        <div class="row mt--40">
            <div class="col-lg-12 text-center">
                <a href="{{ $btnLink }}" class="rts-btn btn-primary radious-sm with-icon">
                    <div class="btn-text">{{ $btnText }}</div>
                    <div class="arrow-icon"><i class="fa-light fa-arrow-right"></i></div>
                    <div class="arrow-icon"><i class="fa-light fa-arrow-right"></i></div>
                </a>
            </div>
        </div>
        @endif
    </div>
</div>
