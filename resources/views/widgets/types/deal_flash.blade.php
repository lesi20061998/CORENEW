<div class="rts-grocery-feature-area rts-section-gapBottom" {!! $sectionStyles !!}>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="title-area-between">
                    <h2 class="title-left">{{ $config['title'] ?? 'Products With Discounts' }}</h2>
                    <div class="countdown">
                        <div class="countDown">{{ $config['end_date'] ?? '10/05/2026 10:20:00' }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt--20">
            <div class="col-lg-12">
                <div class="product-with-discount">
                    <div class="row g-5">
                        <div class="col-xl-4 col-lg-12">
                            @foreach($config['promo_cards'] ?? [] as $banner)
                                <a href="{{ $banner['link'] ?? '#' }}"
                                    class="single-discount-with-bg {{ $banner['bg_class'] ?? '' }}"
                                    @if(!empty($banner['image'])) style="background-image: url('{{ $banner['image'] }}')"
                                    @endif>
                                    <div class="inner-content">
                                        <h4 class="title">{!! nl2br(e($banner['title'])) !!}</h4>
                                        <div class="price-area">
                                            <span>Only</span>
                                            <h4 class="title">{{ $banner['price'] ?? '' }}</h4>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                        <div class="col-xl-8 col-lg-12">
                            <div class="row g-4">
                                @foreach($products as $product)
                                    <div class="col-lg-6 col-md-12">
                                        <x-product-card-horizontal :product="$product" />
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