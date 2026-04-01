@extends('layouts.app')

@section('title', 'Product Details - Ekomart-Grocery-Store')

@section('content')
<!-- rts navigation bar area start -->
<div class="rts-navigation-area-breadcrumb">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="navigator-breadcrumb-wrapper">
                    <a href="{{ route('home') }}">Trang chủ</a>
                    <i class="fa-regular fa-chevron-right"></i>
                    <a href="{{ route('shop.index') }}">Cửa hàng</a>
                    @if($product->categories->isNotEmpty())
                        <i class="fa-regular fa-chevron-right"></i>
                        <a href="{{ route('shop.category', ['category_slug' => $product->categories->first()->slug]) }}">
                            {{ $product->categories->first()->name }}
                        </a>
                    @endif
                    <i class="fa-regular fa-chevron-right"></i>
                    <a class="current" href="#">{{ $product->name }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- rts navigation bar area end -->

<div class="section-seperator">
    <div class="container">
        <hr class="section-seperator">
    </div>
</div>

<div class="rts-shop-details-area rts-section-gap">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-5 col-md-12 col-sm-12 col-12 sticky-colum-item">
                <div class="product-details-gallery">
                    <div class="main-image mb--20">
                        <img id="main-product-image" src="{{ $product->image ? asset($product->image) : asset('theme/images/grocery/01.jpg') }}" alt="{{ $product->name }}" class="img-fluid rounded border w-100 shadow-sm">
                    </div>
                    @if($product->images && count($product->images) > 0)
                        <div class="product-thumb-images d-flex gap-3 overflow-auto pb--10">
                            @foreach($product->images as $img)
                                <div class="thumb-item border rounded p-1 cursor-pointer hover:border-primary transition-all shadow-sm" style="width: 80px; flex-shrink: 0;" onclick="document.getElementById('main-product-image').src = '{{ asset($img) }}'">
                                    <img src="{{ asset($img) }}" alt="thumb" class="img-fluid rounded">
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Mua thêm Combo (Upsell) Section -->
                @if($product->activeCombos->isNotEmpty())
                <div class="product-combo-upsell mt--40 p-4 rounded-3xl bg-emerald-50/30 border border-emerald-100 shadow-sm" 
                     x-data="comboUpsell({
                         productId: {{ $product->id }},
                         mainPrice: {{ (float)$product->price }},
                         combos: {{ $product->activeCombos->map(fn($c) => ['id' => $c->id, 'price' => (float)$c->pivot->combo_price, 'checked' => true])->toJson() }},
                         origins: {{ $product->activeCombos->mapWithKeys(fn($c) => [$c->id => (float)$c->price])->toJson() }}
                     })">
                    <div class="d-flex align-items-center gap-3 mb--20">
                        <i class="fa-solid fa-layer-group text-emerald-500 h4 m-0"></i>
                        <div>
                            <h4 class="m-0 font-black text-slate-800" style="font-size: 16px;">Thường mua cùng nhau</h4>
                            <p class="m-0 text-[11px] text-emerald-600 font-bold uppercase tracking-widest">Tiết kiệm hơn khi mua theo bộ sản phẩm</p>
                        </div>
                    </div>

                    <div class="combo-items-list space-y-3 mb--25">
                        @foreach($product->activeCombos as $combo)
                        <div class="combo-item d-flex align-items-center gap-3 p-3 bg-white rounded-2xl border border-white shadow-sm hover:border-emerald-200 transition-all">
                            <div class="form-check m-0">
                                <input class="form-check-input accent-emerald-500" type="checkbox" 
                                       @change="combos.find(c => c.id == {{ $combo->id }}).checked = $el.checked" checked style="width: 20px; height: 20px;">
                            </div>
                            <div class="thumb w-[60px] h-[60px] rounded-xl overflow-hidden border border-slate-50 flex-shrink-0">
                                <img src="{{ $combo->image ? asset($combo->image) : asset('theme/images/grocery/01.jpg') }}" alt="{{ $combo->name }}" class="w-full h-full object-cover">
                            </div>
                            <div class="info flex-grow-1">
                                <h5 class="m-0 text-sm font-bold text-slate-700 line-clamp-1">{{ $combo->name }}</h5>
                                <div class="price-combo d-flex align-items-center gap-2 mt-1">
                                    <span class="text-emerald-500 font-black text-sm">{{ number_format((float)$combo->pivot->combo_price, 0, ',', '.') }}₫</span>
                                    <span class="text-muted text-xs text-decoration-line-through">{{ number_format((float)$combo->price, 0, ',', '.') }}₫</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="combo-summary p-4 bg-emerald-500 rounded-3xl text-white shadow-lg shadow-emerald-500/20">
                        <div class="d-flex align-items-center justify-content-between mb--15">
                            <div class="text">
                                <div class="text-[11px] font-black uppercase tracking-widest opacity-80 decoration-slate-900 line-through decoration-2" x-show="totalOriginal > totalPrice" x-text="new Intl.NumberFormat('vi-VN').format(totalOriginal) + '₫'"></div>
                                <div class="text-xl font-black" x-text="totalPrice <= 0 ? 'Giá liên hệ' : new Intl.NumberFormat('vi-VN').format(totalPrice) + '₫'"></div>
                            </div>
                            <button type="button" @if($product->has_contact_price) style="display:none;" @endif @click="addBundleToCart()" class="btn btn-light rounded-pill px-4 py-3 font-black text-[12px] uppercase tracking-widest hover:scale-105 active:scale-95 transition-all">
                                MUA <span x-text="combos.filter(c => c.checked).length + 1"></span> MÓN
                            </button>
                        </div>
                        <div class="save-tag text-[10px] font-black uppercase tracking-widest bg-white/20 px-3 py-1.5 rounded-full inline-block" @if($product->has_contact_price) style="display:none;" @endif x-show="totalOriginal > totalPrice">
                            Tiết kiệm <span x-text="new Intl.NumberFormat('vi-VN').format(totalOriginal - totalPrice) + '₫'"></span>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            
            <div class="col-lg-7 col-md-12 col-sm-12 col-12">
                <div class="product-details-content-v2">
                    <div class="category-area d-flex align-items-center gap-2 mb--15">
                        <span class="badge bg-primary px-3 py-2 text-white">Chính hãng</span>
                        @foreach($product->categories as $category)
                            <a href="{{ route('shop.category', ['category_slug' => $category->slug]) }}" class="text-secondary font-medium">
                                {{ $category->name }}@if(!$loop->last), @endif
                            </a>
                        @endforeach
                    </div>
                    <h2 class="title mt--10 font-bold" style="font-size: 28px; color: #2c3e50;">{{ $product->name }}</h2>
                    
                    <div class="rating-area mt--10 d-flex align-items-center gap-3">
                        <div class="stars text-warning">
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star-half-alt"></i>
                        </div>
                        <span class="review-count text-muted">(25 Đánh giá)</span>
                        <div class="v-line" style="width: 1px; height: 15px; background: #ddd;"></div>
                        <span class="stock-status font-bold {{ $product->stock_status == 'out_of_stock' ? 'text-danger' : 'text-success' }}">
                            {{ $product->stock_status == 'out_of_stock' ? 'Hết hàng' : 'Còn hàng' }}
                        </span>
                    </div>

                    <div class="price-area mt--20 p-4 rounded-xl bg-light d-flex align-items-center gap-4">
                        <span class="current text-primary font-black" style="font-size: 32px;">{{ $product->formatted_price }}</span>
                        @if(!$product->has_contact_price && $product->compare_price && $product->compare_price > $product->price)
                            <span class="previous text-muted text-decoration-line-through" style="font-size: 18px;">{{ number_format((float)$product->compare_price, 0, ',', '.') . ' ₫' }}</span>
                            <span class="discount badge bg-danger text-uppercase px-3 py-2">-{{ $product->discount_percent }}% GIẢM</span>
                        @endif
                    </div>

                    <div class="short-description mt--25 text-muted" style="line-height: 1.8;">
                        {!! $product->short_description ?: $product->description !!}
                    </div>

                    @if(!$product->has_contact_price)
                        <div class="product-action-wrapper mt--30 d-flex align-items-center gap-4 border-top pt--30">
                            <div class="quantity-edit d-flex align-items-center border rounded-pill px-3 py-2" style="background: #fff;">
                                <button class="btn btn-sm border-0" onclick="let inp = this.parentElement.querySelector('input'); if(inp.value > 1) inp.value--"><i class="fa-solid fa-minus"></i></button>
                                <input type="text" class="input border-0 text-center font-bold" value="1" style="width: 40px;" readonly>
                                <button class="btn btn-sm border-0" onclick="let inp = this.parentElement.querySelector('input'); inp.value++"><i class="fa-solid fa-plus"></i></button>
                            </div>
                            <a href="javascript:void(0)" 
                               onclick="cart.add({{ $product->id }}, this)"
                               class="rts-btn btn-primary with-icon px-5 py-4 rounded-pill shadow-lg hover:shadow-xl transition-all">
                                <div class="btn-text font-bold">Thêm vào giỏ hàng</div>
                                <div class="arrow-icon ml--10"><i class="fa-regular fa-cart-shopping"></i></div>
                            </a>
                            <div class="wishlist-action btn btn-outline-light rounded-circle p-3 shadow-sm hover:text-danger">
                                <i class="fa-light fa-heart h4 m-0"></i>
                            </div>
                        </div>
                    @else
                        <div class="product-action-wrapper mt--30 d-flex align-items-center gap-4 border-top pt--30">
                            <a href="tel:{{ setting('site_phone', '0123456789') }}" 
                               class="rts-btn btn-primary with-icon px-5 py-4 rounded-pill shadow-lg hover:shadow-xl transition-all w-100 justify-content-center">
                                <div class="btn-text font-bold">LIÊN HỆ NGAY: {{ setting('site_phone', '0123456789') }}</div>
                                <div class="arrow-icon ml--10"><i class="fa-solid fa-phone"></i></div>
                            </a>
                        </div>
                    @endif

                    <!-- VTM Commitments -->
                    <div class="row mt--40 g-3">
                        <div class="col-md-4">
                            <div class="commitment-item d-flex align-items-center gap-3 p-3 border rounded shadow-sm bg-white">
                                <i class="fa-solid fa-truck-fast text-primary h3 m-0"></i>
                                <div class="info"><div class="title font-bold text-sm">Giao nhanh</div><div class="desc text-xs text-muted">Trong 2 giờ</div></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="commitment-item d-flex align-items-center gap-3 p-3 border rounded shadow-sm bg-white">
                                <i class="fa-solid fa-rotate text-primary h3 m-0"></i>
                                <div class="info"><div class="title font-bold text-sm">Đổi trả tận tâm</div><div class="desc text-xs text-muted">Lỗi là đổi</div></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="commitment-item d-flex align-items-center gap-3 p-3 border rounded shadow-sm bg-white">
                                <i class="fa-solid fa-shield-halved text-primary h3 m-0"></i>
                                <div class="info"><div class="title font-bold text-sm">Chính hãng</div><div class="desc text-xs text-muted">Cam kết 100%</div></div>
                            </div>
                        </div>
                    </div>

                    <div class="product-meta mt--40 pt--30 border-top">
                        <div class="meta-item mb--5 font-medium"><span>Mã sản phẩm:</span> <span class="text-primary">#{{ $product->sku ?: 'VTM-'.date('Y').'-'.str_pad($product->id, 4, '0', STR_PAD_LEFT) }}</span></div>
                        <div class="meta-item mb--5 font-medium"><span>Danh mục:</span> 
                            @foreach($product->categories as $category)
                                <a href="{{ route('shop.category', ['category_slug' => $category->slug]) }}" class="text-secondary">{{ $category->name }}</a>@if(!$loop->last), @endif
                            @endforeach
                        </div>
                        @if($product->weight)
                             <div class="meta-item font-medium"><span>Khối lượng:</span> <span class="text-muted">{{ $product->weight }} kg</span></div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt--80">
            <div class="col-lg-12">
                <div class="product-details-tab-wrapper">
                    <ul class="nav nav-tabs border-bottom" role="tablist">
                        <li class="nav-item"><button class="nav-link active font-bold py-3 px-5 border-0" data-bs-toggle="tab" data-bs-target="#tab-description" type="button">Chi tiết sản phẩm</button></li>
                        @if($product->content)
                            <li class="nav-item"><button class="nav-link font-bold py-3 px-5 border-0" data-bs-toggle="tab" data-bs-target="#tab-additional" type="button">Thông tin bổ sung</button></li>
                        @endif
                        <li class="nav-item"><button class="nav-link font-bold py-3 px-5 border-0" data-bs-toggle="tab" data-bs-target="#tab-reviews" type="button">Đánh giá (25)</button></li>
                    </ul>
                    <div class="tab-content mt--40 p-1">
                        <div class="tab-pane fade show active" id="tab-description">
                            <div class="content-vtm" style="line-height: 2;">
                                {!! $product->description !!}
                            </div>
                        </div>
                        @if($product->content)
                            <div class="tab-pane fade" id="tab-additional">
                                <div class="content-vtm" style="line-height: 2;">
                                    {!! $product->content !!}
                                </div>
                            </div>
                        @endif
                        <div class="tab-pane fade" id="tab-reviews">
                             <div class="text-center py-5">
                                 <i class="fa-light fa-comment-dots h1 text-muted opacity-20"></i>
                                 <p class="mt--20 text-muted">Chưa có đánh giá nào. Hãy là người đầu tiên đánh giá sản phẩm này!</p>
                                 <button class="rts-btn btn-primary mt--10">Viết đánh giá</button>
                             </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Related Products Section -->
<div class="rts-grocery-feature-area rts-section-gapBottom">
    <div class="container">
        <div class="row">
            <div class="col-lg-12"><h2 class="title-left mb--30 font-black" style="font-size: 24px;">Sản phẩm liên quan</h2></div>
        </div>
        <div class="row g-4">
            @foreach($relatedProducts as $related)
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="single-shopping-card-one border rounded p-3 shadow-sm hover:shadow-lg transition-all h-100 bg-white">
                    <div class="image-and-action-area-wrapper">
                        <a href="{{ route('shop.show', ['slug' => $related->slug]) }}" class="thumbnail-preview d-block mb--15">
                            <img src="{{ $related->image ? asset($related->image) : asset('theme/images/grocery/01.jpg') }}" alt="{{ $related->name }}" class="img-fluid rounded w-100" style="aspect-ratio: 1; object-fit: cover;">
                        </a>
                    </div>
                    <div class="body-content">
                        <a href="{{ route('shop.show', ['slug' => $related->slug]) }}"><h4 class="title text-sm font-bold mb--10 overflow-hidden" style="height: 40px;">{{ $related->name }}</h4></a>
                        <div class="price-area d-flex align-items-center justify-content-between">
                            <span class="current text-primary font-black">{{ $related->formatted_price }}</span>
                            @if($related->compare_price)
                                <span class="previous text-muted text-xs text-decoration-line-through">{{ number_format((float)$related->compare_price, 0, ',', '.') . ' ₫' }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@push('scripts')
<script>
    function comboUpsell(config) {
        return {
            productId: config.productId,
            mainPrice: config.mainPrice,
            combos: config.combos,
            origins: config.origins,
            get totalPrice() {
                return this.mainPrice + this.combos.filter(c => c.checked).reduce((sum, c) => sum + c.price, 0);
            },
            get totalOriginal() {
                return this.mainPrice + this.combos.filter(c => c.checked).reduce((sum, c) => sum + (this.origins[c.id] || 0), 0);
            },
            addBundleToCart() {
                if (typeof cart === 'undefined') {
                    alert('Hệ thống giỏ hàng chưa sẵn sàng, vui lòng thử lại sau giây lát!');
                    return;
                }
                const ids = [this.productId, ...this.combos.filter(c => c.checked).map(c => c.id)];
                ids.forEach((id, index) => {
                    setTimeout(() => cart.add(id), index * 250);
                });
            }
        }
    }
</script>
@endpush
@endsection
