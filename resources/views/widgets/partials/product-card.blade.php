@php
    $image     = $product->image ? asset($product->image) : asset('theme/images/grocery/01.jpg');
    $price     = (float) $product->effective_price;
    $flashPrice = $product->flash_price;
    $isFlash   = $flashPrice !== null;
    $oldPrice  = $isFlash ? (float)$product->price : (($product->compare_price && $product->compare_price > $price) ? (float) $product->compare_price : null);
    $discount  = $isFlash ? $product->flash_discount_percent : ($oldPrice ? (int) round((1 - $price / $oldPrice) * 100) : 0);
    $detailUrl = route('slug.show', $product->slug);
@endphp

<div class="single-shopping-card-one">
    <div class="image-and-action-area-wrapper">
        <a href="{{ $detailUrl }}" class="thumbnail-preview">
            @if($discount > 0)
            <div class="badge">
                <span>{{ $discount }}%<br>Off</span>
                <i class="fa-solid fa-bookmark"></i>
            </div>
            @endif
            <img src="{{ $image }}" alt="{{ $product->name }}">
        </a>
        <div class="action-share-option">
            <span class="single-action openuptip message-show-action" data-flow="up" title="Yêu thích">
                <i class="fa-light fa-heart"></i>
            </span>
            <span class="single-action openuptip cta-quickview product-details-popup-btn"
                data-flow="up" title="Xem nhanh"
                data-product-id="{{ $product->id }}">
                <i class="fa-regular fa-eye"></i>
            </span>
        </div>
    </div>

    <div class="body-content">
        <a href="{{ $detailUrl }}">
            <h4 class="title">{{ $product->name }}</h4>
        </a>
        <div class="price-area">
            <span class="current">{{ number_format($price, 0, ',', '.') }}đ</span>
            @if($oldPrice)
            <div class="previous">{{ number_format($oldPrice, 0, ',', '.') }}đ</div>
            @endif
        </div>
        <div class="cart-counter-action">
            <div class="quantity-edit">
                <input type="text" class="input" value="1" min="1">
                <div class="button-wrapper-action">
                    <button class="button"><i class="fa-regular fa-chevron-down"></i></button>
                    <button class="button plus">+<i class="fa-regular fa-chevron-up"></i></button>
                </div>
            </div>
            <button class="rts-btn btn-primary radious-sm with-icon add-to-cart-btn"
                data-product-id="{{ $product->id }}"
                data-product-name="{{ $product->name }}"
                data-product-price="{{ $price }}"
                data-product-image="{{ $image }}"
                data-product-slug="{{ $product->slug }}">
                <div class="btn-text">Thêm</div>
                <div class="arrow-icon"><i class="fa-regular fa-cart-shopping"></i></div>
                <div class="arrow-icon"><i class="fa-regular fa-cart-shopping"></i></div>
            </button>
        </div>
    </div>
</div>
