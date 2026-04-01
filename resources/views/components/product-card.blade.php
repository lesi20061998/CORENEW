@props(['product'])

@php
    $thumbnailUrl = $product->thumbnail_url ?: asset('theme/images/grocery/01.jpg');
    $categoryName = $product->categories->first()->name ?? 'Grocery';
    $discountPercent = $product->discount_percent;
@endphp

<div class="vtm-product-card">
    {{-- Image Area --}}
    <div class="image-and-action-area-wrapper">
        <a href="{{ route('shop.show', ['slug' => $product->slug]) }}" class="thumbnail-preview">
            @if($product->on_sale)
                <div class="vtm-badge-ribbon">
                    <span>{{ $discountPercent }}%</span><br>Giảm giá
                </div>
            @endif
            <img src="{{ $thumbnailUrl }}" alt="{{ $product->name }}">
        </a>
        <div class="action-share-option">
            <div class="single-action openuptip message-show-action" data-flow="up" title="Add To Wishlist" onclick="cwAction.addWishlist({{ $product->id }}, this)">
                <i class="fa-light fa-heart"></i>
            </div>
            <div class="single-action openuptip" data-flow="up" title="Compare" onclick="cwAction.addCompare({{ $product->id }}, this)">
                <i class="fa-solid fa-arrows-retweet"></i>
            </div>
            <div class="single-action openuptip cta-quickview product-details-popup-btn" data-flow="up" title="Quick View" onclick="cwAction.quickView({{ $product->id }})">
                <i class="fa-regular fa-eye"></i>
            </div>
        </div>
    </div>

    {{-- Content Area --}}
    <div class="content-area">
        <div class="category-text">{{ $categoryName }}</div>
        <a href="{{ route('shop.show', ['slug' => $product->slug]) }}">
            <h3 class="product-title-h3">{{ Str::limit($product->name, 50) }}</h3>
        </a>
        
        <div class="stars-area">
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
        </div>

        <div class="price-and-btn-wrapper">
            <div class="price-area">
                <span class="current-price">{{ $product->formatted_price }}</span>
                @if($product->old_price > $product->effective_price)
                    <span class="old-price">{{ $product->formatted_old_price }}</span>
                @endif
            </div>

            <div class="cart-counter-action mt-4">
                @if(!$product->has_contact_price)
                    <button type="button" @click="cart.add({{ $product->id }}, $event.target)" 
                            class="rts-btn btn-primary w-100 py-3 rounded-xl font-bold uppercase text-[11px] tracking-wider d-flex align-items-center justify-content-center gap-2 border-0">
                        <i class="fas fa-shopping-cart m-0" style="font-size: 1rem;"></i> MUA NGAY
                    </button>
                @else
                    <a href="{{ route('shop.show', ['slug' => $product->slug]) }}" 
                       class="rts-btn btn-primary w-100 py-3 rounded-xl font-bold uppercase text-[11px] tracking-wider text-center d-flex align-items-center justify-content-center gap-2">
                        <i class="fas fa-phone m-0" style="font-size: 1rem;"></i> LIÊN HỆ
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>