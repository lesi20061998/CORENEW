@props(['product'])

@php
    $thumbnailUrl = $product->thumbnail_url ?: asset('theme/images/grocery/01.jpg');
    $categoryName = $product->categories->first()->name ?? 'Grocery';
    $discountPercent = $product->discount_percent;
@endphp

<div class="single-shopping-card-one vtm-product-card">
    <!-- image and action area start -->
    <div class="image-and-action-area-wrapper">
        <a href="{{ route('shop.show', ['slug' => $product->slug]) }}" class="thumbnail-preview">
            @if($product->on_sale)
                <div class="badge">
                    <span>{{ $discountPercent }}% <br> Off</span>
                    <i class="fa-solid fa-bookmark"></i>
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

    <!-- body content start -->
    <div class="body-content">
        <a href="{{ route('shop.show', ['slug' => $product->slug]) }}">
            <h4 class="title">{{ Str::limit($product->name, 50) }}</h4>
        </a>
        <span class="availability">{{ $product->unit ?? '500g Pack' }}</span>
        
        <div class="price-area">
            <span class="current">{{ $product->formatted_price }}</span>
            @if($product->compare_price > $product->price)
                <div class="previous">{{ $product->formatted_old_price }}</div>
            @endif
        </div>

        <div class="cart-counter-action">
            @if(!$product->has_contact_price)
                <div class="quantity-edit">
                    <input type="text" class="input qv-qty-input" value="01">
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
            @else
                <a href="tel:{{ setting('hotline') }}" class="rts-btn btn-primary radious-sm w-100 text-center">
                    <div class="btn-text">Liên hệ</div>
                </a>
            @endif
        </div>
    </div>
</div>