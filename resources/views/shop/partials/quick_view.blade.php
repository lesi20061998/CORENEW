<div class="qv-modal-wrapper" style="min-height: 400px; background: rgba(0,0,0,0.85);">
    <div class="product-details-popup qv-modal-container" style="display: block !important; opacity: 1 !important; visibility: visible !important;">
        <button class="product-details-close-btn qv-close-btn"><i class="fal fa-times"></i></button>
        <div class="details-product-area">
            <div class="product-thumb-area">
                <div class="cursor"></div>
                @php
                    $allImages = [];
                    if ($product->thumbnail_url) {
                        $allImages[] = $product->thumbnail_url;
                    }
                    if ($product->images_urls && is_array($product->images_urls)) {
                        foreach ($product->images_urls as $imgUrl) {
                            if ($imgUrl) $allImages[] = $imgUrl;
                        }
                    }
                    if (empty($allImages)) {
                        $allImages[] = asset('theme/images/grocery/01.jpg');
                    }
                    $allImages = array_unique(array_slice($allImages, 0, 4));
                    $numberClasses = ['one', 'two', 'three', 'four'];
                @endphp

                @foreach($allImages as $index => $imgUrl)
                    @php $class = $numberClasses[$index] ?? 'more'; @endphp
                    <div class="thumb-wrapper {{ $class }} filterd-items {{ $index === 0 ? 'figure' : 'hide' }}">
                        <div class="product-thumb zoom" onmousemove="zoom(event)"
                            style="background-image: url('{{ $imgUrl }}');">
                            <img src="{{ $imgUrl }}" alt="{{ $product->name }}">
                        </div>
                    </div>
                @endforeach

                <div class="product-thumb-filter-group">
                    @foreach($allImages as $index => $imgUrl)
                        @php $class = $numberClasses[$index] ?? 'more'; @endphp
                        <div class="thumb-filter filter-btn {{ $index === 0 ? 'active' : '' }}" data-show=".{{ $class }}">
                            <img src="{{ $imgUrl }}" alt="product-thumb-filter">
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="contents">
                <div class="product-status">
                    <span class="product-catagory">{{ $product->categories->first()->name ?? 'Danh mục' }}</span>
                    <div class="rating-stars-group">
                        @php 
                            $avgRating = $product->approvedReviews->avg('rating') ?? 5;
                            $reviewCount = $product->approvedReviews->count();
                        @endphp
                        @for($i = 1; $i <= 5; $i++)
                            <div class="rating-star"><i class="{{ $i <= $avgRating ? 'fas' : 'far' }} fa-star"></i></div>
                        @endfor
                        <span>{{ $reviewCount }} Reviews</span>
                    </div>
                </div>
                <h1 class="product-title">{{ $product->name }} <span class="stock">@if($product->stock > 0) In Stock @else Out of Stock @endif</span></h1>
                <span class="product-price">
                    @if($product->compare_price > $product->price)
                        <span class="old-price">{{ number_format($product->compare_price) }}đ</span>
                    @endif
                    <span class="current-price">{{ $product->formatted_price }}</span>
                </span>
                <div class="qv-description-text mb--20">
                    {!! \Illuminate\Support\Str::limit(strip_tags($product->short_description ?: $product->description), 150) !!}
                </div>
                <div class="product-bottom-action">
                    @if(!$product->has_contact_price)
                        <div class="cart-edit">
                            <div class="quantity-edit action-item">
                                <button class="button qv-qty-btn qv-minus">-</button>
                                <input type="text" class="input qv-qty-input" value="1">
                                <button class="button plus qv-qty-btn qv-plus">+</button>
                            </div>
                        </div>
                        <a href="javascript:void(0);" onclick="cart.add({{ $product->id }}, this)" class="rts-btn btn-primary radious-sm with-icon">
                            <div class="btn-text">Thêm vào giỏ</div>
                            <div class="arrow-icon"><i class="fa-regular fa-cart-shopping"></i></div>
                            <div class="arrow-icon"><i class="fa-regular fa-cart-shopping"></i></div>
                        </a>
                    @else
                        <a href="tel:{{ setting('site_phone') }}" class="rts-btn btn-primary radious-sm">Liên hệ: {{ setting('site_phone') }}</a>
                    @endif
                    <a href="javascript:void(0);" onclick="cwAction.addWishlist({{ $product->id }}, this)" class="rts-btn btn-primary ml--20"><i
                            class="fa-light fa-heart"></i></a>
                </div>
                <div class="product-uniques">
                    <span class="sku product-unipue"><span>SKU: </span> {{ $product->sku ?? 'Đang cập nhật' }}</span>
                    @if($product->categories->isNotEmpty())
                        <span class="catagorys product-unipue"><span>Categories: </span> {{ $product->categories->pluck('name')->join(', ') }}</span>
                    @endif
                    @if($product->meta_keywords)
                        <span class="tags product-unipue"><span>Tags: </span> {{ $product->meta_keywords }}</span>
                    @endif
                </div>
                <div class="share-social">
                    <span>Share:</span>
                    <a class="platform" href="http://facebook.com" target="_blank"><i class="fab fa-facebook-f"></i></a>
                    <a class="platform" href="http://twitter.com" target="_blank"><i class="fab fa-twitter"></i></a>
                    <a class="platform" href="http://youtube.com" target="_blank"><i class="fab fa-youtube"></i></a>
                    <a class="platform" href="http://linkedin.com" target="_blank"><i class="fab fa-linkedin"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>