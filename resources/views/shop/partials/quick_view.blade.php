<div class="qv-modal-wrapper">
    <div class="qv-modal-container">
        <div class="qv-modal-content">
            <button class="qv-close-btn"><i class="fal fa-times"></i></button>

            <div class="qv-main-layout">
                <!-- Left: Gallery Section (Single Image Engine) -->
                <div class="qv-gallery-section">
                    @php
                        $mainImgUrl = $product->thumbnail_url ?: asset('theme/images/grocery/01.jpg');

                        $allImages = $product->images ?: [];
                        if ($product->image) {
                            array_unshift($allImages, $product->image);
                        }
                        $allImages = array_unique(array_slice($allImages, 0, 3));
                    @endphp

                    <div class="qv-image-preview-container">
                        <!-- ONLY ONE IMAGE CONTAINER - ZERO GHOSTING -->
                        <div class="qv-main-image">
                            <div id="qv-main-zoom" class="qv-image-zoom" onmousemove="zoom(event)"
                                style="background-image: url('{{ $mainImgUrl }}');">

                            </div>
                        </div>
                    </div>

                    @if(count($allImages) > 1)
                        <div class="qv-thumbnails-group">
                            @foreach($allImages as $index => $img)
                                @php
                                    $imgUrl = str_starts_with($img, 'http') ? $img : (str_starts_with($img, 'media/') ? asset('storage/' . $img) : asset($img));
                                    $active = $index === 0 ? 'active' : '';
                                @endphp
                                <div class="qv-thumb-item {{ $active }}" data-img="{{ $imgUrl }}">
                                    <img src="{{ $imgUrl }}" alt="thumb">
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Right: Information Section -->
                <div class="qv-info-section">
                    <div class="qv-status-meta">
                        <span class="qv-badge-cat">{{ $product->categories->first()->name ?? 'VEG' }}</span>
                        <div class="qv-rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star" style="color: #eee;"></i>
                            <span class="qv-review-count">10 Reviews</span>
                        </div>
                    </div>

                    <h2 class="qv-product-title">{{ $product->name }} <span class="qv-stock-tag">In Stock</span></h2>

                    <div class="qv-price-row">
                        <span class="qv-price-current">{{ $product->formatted_price }}</span>
                        @if($product->compare_price > $product->price)
                            <span class="qv-price-old">{{ $product->formatted_old_price }}</span>
                        @endif
                    </div>

                    <div class="qv-description">
                        <p>{{ Str::limit(strip_tags($product->description), 220) }}</p>
                    </div>

                    <div class="qv-cta-group">
                        <div class="qv-quantity-control">
                            <button class="qv-qty-btn qv-minus">-</button>
                            <input type="text" class="qv-qty-input" value="01">
                            <button class="qv-qty-btn qv-plus">+</button>
                        </div>

                        @if(!$product->has_contact_price)
                            <button onclick="cart.add({{ $product->id }}, this)" class="qv-add-to-cart">
                                <i class="far fa-shopping-cart"></i> Add To Cart
                            </button>
                        @else
                            <a href="tel:{{ setting('hotline') }}" class="qv-add-to-cart qv-contact">
                                <i class="fa-regular fa-phone"></i> Contact Now
                            </a>
                        @endif

                        <button onclick="cwAction.addWishlist({{ $product->id }}, this)" class="qv-wishlist-btn">
                            <i class="fa-light fa-heart"></i>
                        </button>
                    </div>

                    <div class="qv-meta-info">
                        <div class="qv-meta-row"><b>SKU:</b> <span>{{ $product->sku ?? 'VEG-001' }}</span></div>
                        <div class="qv-meta-row">
                            <b>Categories:</b>
                            <span>
                                @foreach($product->categories as $cat)
                                    {{ $cat->name }}{{ !$loop->last ? ', ' : '' }}
                                @endforeach
                            </span>
                        </div>
                        @if($product->meta_keywords)
                            <div class="qv-meta-row"><b>Tags:</b> <span>{{ $product->meta_keywords }}</span></div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>