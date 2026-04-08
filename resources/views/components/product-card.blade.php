@props(['product'])

@php
    $thumbnailUrl = $product->thumbnail_url ?: asset('theme/images/grocery/01.jpg');
    $categoryName = $product->categories->first()->name ?? 'Sản phẩm';
    $discountPercent = $product->discount_percent;
@endphp

<div class="single-shopping-card-one" x-data="{ qty: 1 }">
    <!-- image and action area start -->
    <div class="image-and-action-area-wrapper">
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
        <div class="action-share-option">
            <div class="single-action openuptip message-show-action" data-flow="up" title="Thêm yêu thích"
                @click="cwAction.addWishlist({{ $product->id }}, $event.target)">
                <i class="fa-light fa-heart"></i>
            </div>
            <div class="single-action openuptip" data-flow="up" title="So sánh"
                @click="cwAction.addCompare({{ $product->id }}, $event.target)">
                <i class="fa-solid fa-arrows-retweet"></i>
            </div>
            <div class="single-action openuptip cta-quickview product-details-popup-btn" data-flow="up"
                title="Xem nhanh" @click="cwAction.quickView({{ $product->id }})">
                <i class="fa-regular fa-eye"></i>
            </div>
        </div>
    </div>
    <!-- image and action area end -->
    <div class="body-content">

        <a href="{{ route('shop.show', $product->slug) }}">
            <h4 class="title">{{ $product->name }}</h4>
        </a>
        <span class="availability">{{ $product->unit ?? 'Gói' }}</span>
        <div class="price-area">
            <span class="current">{{ $product->formatted_price }}</span>
            @if($product->old_price > $product->effective_price)
                <div class="previous">{{ number_format($product->old_price) }}đ</div>
            @endif
        </div>
        @if(!$product->has_contact_price)
            <div class="cart-counter-action">
                <div class="quantity-edit">
                    <input type="text" class="input" x-model="qty" readonly>
                    <div class="button-wrapper-action">
                        <button class="button" @click="qty > 1 ? qty-- : 1"><i
                                class="fa-regular fa-chevron-down"></i></button>
                        <button class="button plus" @click="qty++"><i class="fa-regular fa-chevron-up"></i></button>
                    </div>
                </div>
                <a href="javascript:void(0);" @click="cart.add({{ $product->id }}, $event.target, qty)"
                    class="rts-btn btn-primary radious-sm with-icon">
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
        @else
            <div class="cart-counter-action">
                <a href="tel:{{ setting('site_phone') }}" class="rts-btn btn-primary radious-sm w-100 text-center">
                    Liên hệ ngay
                </a>
            </div>
        @endif
    </div>
</div>