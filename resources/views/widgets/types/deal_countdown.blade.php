@php
    $title       = $config['title']        ?? 'Sản phẩm khuyến mãi';
    $countdownTo = $config['countdown_to'] ?? '12/31/2025 23:59:59';
    $promoCards  = $config['promo_cards']  ?? [];
@endphp

<div class="rts-grocery-feature-area rts-section-gapBottom">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="title-area-between">
                    <h2 class="title-left">{{ $title }}</h2>
                    <div class="countdown">
                        <div class="countDown">{{ $countdownTo }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="product-with-discount">
                    <div class="row g-5">
                        @if(count($promoCards))
                        <div class="col-xl-4 col-lg-12">
                            @foreach($promoCards as $card)
                            <a href="{{ $card['link'] ?? '/shop' }}" class="single-discount-with-bg {{ $card['bg_class'] ?? '' }}">
                                <div class="inner-content">
                                    <h4 class="title">{{ $card['title'] ?? '' }}</h4>
                                    <div class="price-area">
                                        <span>Chỉ</span>
                                        <h4 class="title">{{ $card['price'] ?? '' }}</h4>
                                    </div>
                                </div>
                            </a>
                            @endforeach
                        </div>
                        <div class="col-xl-8 col-lg-12">
                            <div class="row">
                                @foreach($products as $product)
                                @php
                                    $image    = $product->image ? asset('storage/' . $product->image) : asset('theme/images/grocery/03.jpg');
                                    $price    = (float) $product->price;
                                    $oldPrice = ($product->compare_price && $product->compare_price > $price) ? (float) $product->compare_price : null;
                                    $discount = $oldPrice ? round((1 - $price / $oldPrice) * 100) : 0;
                                @endphp
                                <div class="col-lg-6">
                                    <div class="single-shopping-card-one discount-offer">
                                        <a href="{{ route('slug.show', $product->slug) }}" class="thumbnail-preview">
                                            @if($discount > 0)
                                            <div class="badge">
                                                <span>{{ $discount }}%<br>Off</span>
                                                <i class="fa-solid fa-bookmark"></i>
                                            </div>
                                            @endif
                                            <img src="{{ $image }}" alt="{{ $product->name }}">
                                        </a>
                                        <div class="body-content">
                                            <a href="{{ route('slug.show', $product->slug) }}">
                                                <h4 class="title">{{ $product->name }}</h4>
                                            </a>
                                            @if($product->unit)
                                            <span class="availability">{{ $product->unit }}</span>
                                            @endif
                                            <div class="price-area">
                                                <span class="current">{{ number_format($price) }}đ</span>
                                                @if($oldPrice)
                                                <div class="previous">{{ number_format($oldPrice) }}đ</div>
                                                @endif
                                            </div>
                                            <div class="cart-counter-action">
                                                <div class="quantity-edit">
                                                    <input type="text" class="input" value="1">
                                                    <div class="button-wrapper-action">
                                                        <button class="button"><i class="fa-regular fa-chevron-down"></i></button>
                                                        <button class="button plus">+<i class="fa-regular fa-chevron-up"></i></button>
                                                    </div>
                                                </div>
                                                <button class="rts-btn btn-primary radious-sm with-icon add-to-cart-btn"
                                                    data-product-id="{{ $product->id }}"
                                                    data-product-name="{{ $product->name }}"
                                                    data-product-price="{{ $price }}"
                                                    data-product-image="{{ $image }}">
                                                    <div class="btn-text">Thêm</div>
                                                    <div class="arrow-icon"><i class="fa-regular fa-cart-shopping"></i></div>
                                                    <div class="arrow-icon"><i class="fa-regular fa-cart-shopping"></i></div>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @else
                        <div class="col-lg-12">
                            <div class="row">
                                @foreach($products as $product)
                                @php
                                    $image    = $product->image ? asset('storage/' . $product->image) : asset('theme/images/grocery/03.jpg');
                                    $price    = (float) $product->price;
                                    $oldPrice = ($product->compare_price && $product->compare_price > $price) ? (float) $product->compare_price : null;
                                    $discount = $oldPrice ? round((1 - $price / $oldPrice) * 100) : 0;
                                @endphp
                                <div class="col-lg-6">
                                    <div class="single-shopping-card-one discount-offer">
                                        <a href="{{ route('slug.show', $product->slug) }}" class="thumbnail-preview">
                                            @if($discount > 0)
                                            <div class="badge">
                                                <span>{{ $discount }}%<br>Off</span>
                                                <i class="fa-solid fa-bookmark"></i>
                                            </div>
                                            @endif
                                            <img src="{{ $image }}" alt="{{ $product->name }}">
                                        </a>
                                        <div class="body-content">
                                            <a href="{{ route('slug.show', $product->slug) }}">
                                                <h4 class="title">{{ $product->name }}</h4>
                                            </a>
                                            @if($product->unit)
                                            <span class="availability">{{ $product->unit }}</span>
                                            @endif
                                            <div class="price-area">
                                                <span class="current">{{ number_format($price) }}đ</span>
                                                @if($oldPrice)
                                                <div class="previous">{{ number_format($oldPrice) }}đ</div>
                                                @endif
                                            </div>
                                            <div class="cart-counter-action">
                                                <div class="quantity-edit">
                                                    <input type="text" class="input" value="1">
                                                    <div class="button-wrapper-action">
                                                        <button class="button"><i class="fa-regular fa-chevron-down"></i></button>
                                                        <button class="button plus">+<i class="fa-regular fa-chevron-up"></i></button>
                                                    </div>
                                                </div>
                                                <button class="rts-btn btn-primary radious-sm with-icon add-to-cart-btn"
                                                    data-product-id="{{ $product->id }}"
                                                    data-product-name="{{ $product->name }}"
                                                    data-product-price="{{ $price }}"
                                                    data-product-image="{{ $image }}">
                                                    <div class="btn-text">Thêm</div>
                                                    <div class="arrow-icon"><i class="fa-regular fa-cart-shopping"></i></div>
                                                    <div class="arrow-icon"><i class="fa-regular fa-cart-shopping"></i></div>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
