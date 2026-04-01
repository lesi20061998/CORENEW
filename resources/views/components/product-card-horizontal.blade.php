@props(['product'])

@php
    $thumbnailUrl = $product->thumbnail_url ?: asset('theme/images/grocery/01.jpg');
    $discountPercent = $product->discount_percent;
@endphp

<div class="vtm-product-card-horizontal border rounded p-3 bg-white mb-3">
    <div class="row align-items-center g-3">
        <!-- Image Section (Left) -->
        <div class="col-4">
            <div class="image-wrapper position-relative border rounded overflow-hidden">
                @if($product->on_sale)
                    <div class="vtm-badge-ribbon">
                        <span>{{ $discountPercent }}% <br> Off</span>
                    </div>
                @endif
                <a href="{{ route('shop.show', ['slug' => $product->slug]) }}">
                    <img src="{{ $thumbnailUrl }}" alt="{{ $product->name }}" class="w-100 img-fluid">
                </a>
            </div>
        </div>

        <!-- Content Section (Right) -->
        <div class="col-8">
            <div class="content-area">
                <a href="{{ route('shop.show', ['slug' => $product->slug]) }}">
                    <h5 class="product-title mb-1">{{ Str::limit($product->name, 45) }}</h5>
                </a>
                <p class="unit-text text-muted mb-2">{{ $product->unit ?? '500g Pack' }}</p>
                
                <div class="price-area mb-3 d-flex align-items-center gap-2">
                    <span class="current-price h4 mb-0 text-primary">{{ $product->formatted_price }}</span>
                    @if($product->compare_price > $product->price)
                        <span class="old-price text-muted text-decoration-line-through">{{ $product->formatted_old_price }}</span>
                    @endif
                </div>

                <div class="cart-counter-action">
                    <div class="quantity-edit">
                        <input type="text" class="input qv-qty-input me-1" value="01">
                        <div class="button-wrapper-action">
                            <button class="qv-qty-btn qv-minus">-<i class="fa-regular fa-chevron-down"></i></button>
                            <button class="plus qv-qty-btn qv-plus">+<i class="fa-regular fa-chevron-up"></i></button>
                        </div>
                    </div>

                    <a href="javascript:void(0)" onclick="cart.add({{ $product->id }}, this)" class="rts-btn btn-primary radious-sm with-icon">
                        <div class="btn-text">Add</div>
                        <div class="arrow-icon">
                            <i class="fa-regular fa-cart-shopping"></i>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
