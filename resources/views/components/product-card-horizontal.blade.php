@props(['product'])

@php
    $thumbnailUrl = $product->thumbnail_url ?: asset('theme/images/grocery/01.jpg');
    $discountPercent = $product->discount_percent;
@endphp

<div class="single-shopping-card-one {{ $discountPercent > 0 ? 'discount-offer' : '' }}">
    <a href="{{ route('shop.show', $product->slug) }}" class="thumbnail-preview">
        @if($discountPercent > 0)
            <div class="badge">
                <span>{{ $discountPercent }}% <br>
                    Off
                </span>
                <i class="fa-solid fa-bookmark"></i>
            </div>
        @endif
        <img src="{{ $thumbnailUrl }}" alt="{{ $product->name }}">
    </a>
    <div class="body-content">

        <a href="{{ route('shop.show', $product->slug) }}">
            <h4 class="title">{{ $product->name }}</h4>
        </a>
        <span class="availability">{{ $product->unit ?? 'Gói' }}</span>
        <div class="price-area">
            <span class="current">{{ $product->formatted_price }}</span>
            @if($product->compare_price > $product->price)
                <div class="previous">{{ number_format($product->compare_price) }}đ</div>
            @endif
        </div>
        <div class="cart-counter-action" x-data="{ qty: 1 }">
            <div class="quantity-edit">
                <input type="text" class="input" x-model="qty" readonly>
                <div class="button-wrapper-action">
                    <button class="button" @click="qty > 1 ? qty-- : 1"><i class="fa-regular fa-chevron-down"></i></button>
                    <button class="button plus" @click="qty++"><i class="fa-regular fa-chevron-up"></i></button>
                </div>
            </div>
            <a href="javascript:void(0);" @click="cart.add({{ $product->id }}, $event.target, qty)" class="rts-btn btn-primary radious-sm with-icon">
                <div class="btn-text">
                    Thêm
                </div>
                <div class="arrow-icon">
                    <i class="fa-regular fa-cart-shopping"></i>
                </div>
                <div class="arrow-icon">
                    <i class="fa-regular fa-cart-shopping"></i>
                </div>
            </a>
        </div>
    </div>
</div>
