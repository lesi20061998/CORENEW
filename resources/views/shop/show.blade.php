@extends('layouts.app')

@section('title', ($product->meta_title ?: $product->name) . ' - ' . setting('site_name', 'VietTin Mart'))
@section('meta_description', $product->meta_description ?: \Illuminate\Support\Str::limit(strip_tags($product->description), 160))
@section('meta_keywords', $product->meta_keywords)
@section('canonical', url('cua-hang/' . $product->slug))
@section('og_type', 'product')
@section('og_image', $product->image ? asset($product->image) : asset(setting('site_og_image')))



@section('content')


    <div class="rts-chop-details-area rts-section-gap bg_light-1">
        <div class="container">
            <div class="shopdetails-style-1-wrapper">
                <div class="row g-5">

                    <!-- Left: Main Content (Images, Info, Tabs) -->
                    <div class="col-xl-9 col-lg-8 col-md-12">
                        <!-- Top: Product Images & Info -->
                        <div class="product-details-popup-wrapper in-shopdetails mb-5">
                            <div class="rts-product-details-section rts-product-details-section2 product-details-popup-section">
                                <div class="product-details-popup">
                                    <div class="details-product-area">
                                        <div class="row g-4">
                                            <!-- Columns for image and content -->
                                            <div class="col-lg-5 col-md-12">
                                                <div class="product-thumb-area">
                                                    <div class="cursor"></div>
                                                    @php
                                                        $numberClasses = ['one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten'];
                                                        $allImages = [];
                                                        if ($product->image) {
                                                            $allImages[] = \Illuminate\Support\Str::contains($product->image, 'http') ? $product->image : asset($product->image);
                                                        }
                                                        if ($product->images && is_array($product->images)) {
                                                            foreach ($product->images as $img) {
                                                                $allImages[] = \Illuminate\Support\Str::contains($img, 'http') ? $img : asset($img);
                                                            }
                                                        }
                                                        if (empty($allImages)) {
                                                            $allImages[] = asset('theme/images/grocery/01.jpg');
                                                        }
                                                    @endphp

                                                    @foreach($allImages as $index => $imgUrl)
                                                        @php $class = $numberClasses[$index] ?? 'more'; @endphp
                                                        <div class="thumb-wrapper {{ $class }} filterd-items {{ $index > 0 ? 'hide' : 'figure' }}">
                                                            <div class="product-thumb zoom" onmousemove="zoom(event)"
                                                                style="background-image: url('{{ $imgUrl }}'); background-position: 50% 50%;">
                                                                <img src="{{ $imgUrl }}" alt="{{ $product->name }}">
                                                            </div>
                                                        </div>
                                                    @endforeach

                                                    <div class="product-thumb-filter-group mt-3">
                                                        @foreach($allImages as $index => $imgUrl)
                                                            @php $class = $numberClasses[$index] ?? 'more'; @endphp
                                                            <div class="thumb-filter filter-btn {{ $index === 0 ? 'active' : '' }}" data-show=".{{ $class }}">
                                                                <img src="{{ $imgUrl }}" alt="product-thumb-filter">
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-7 col-md-12">
                                                <div class="contents" x-data="{ qty: 1 }">
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
                                                            <span>{{ $reviewCount }} Đánh giá</span>
                                                        </div>
                                                    </div>
                                                    <h2 class="product-title">{{ $product->name }}</h2>
                                                    <p class="mt--20 mb--20">
                                                        {!! $product->short_description ?: \Illuminate\Support\Str::limit(strip_tags($product->description), 150) !!}
                                                    </p>
                                                    <span class="product-price mb--15 d-block"
                                                        style="color: #DC2626; font-weight: 600;"> {{ $product->formatted_price }}
                                                        @if(!$product->has_contact_price && $product->compare_price > $product->price)
                                                            <span class="old-price ml--15">{{ $product->formatted_compare_price }}</span>
                                                        @endif
                                                    </span>

                                                    <div class="product-bottom-action">
                                                        @if(!$product->has_contact_price)
                                                            <div class="cart-edits">
                                                                <div class="quantity-edit action-item">
                                                                    <button class="button" @click="qty > 1 ? qty-- : 1"><i class="fal fa-minus minus"></i></button>
                                                                    <input type="text" class="input" x-model="qty" readonly style="pointer-events: none;">
                                                                    <button class="button plus" @click="qty++">+<i
                                                                            class="fal fa-plus plus"></i></button>
                                                                </div>
                                                            </div>
                                                            <a href="javascript:void(0);" @click="typeof cart !== 'undefined' ? cart.add({{ $product->id }}, $event.target, qty) : null" class="rts-btn btn-primary radious-sm with-icon">
                                                                <div class="btn-text">Thêm vào giỏ</div>
                                                                <div class="arrow-icon"><i class="far fa-shopping-cart"></i></div>
                                                                <div class="arrow-icon"><i class="far fa-shopping-cart"></i></div>
                                                            </a>
                                                        @else
                                                            <a href="tel:{{ setting('site_phone') }}" class="rts-btn btn-primary radious-sm">Liên hệ: {{ setting('site_phone') }}</a>
                                                        @endif
                                                        <a href="javascript:void(0);" class="rts-btn btn-primary ml--20"><i class="fa-light fa-heart"></i></a>
                                                    </div>

                                                    <div class="product-uniques">
                                                        <span class="sku product-unipue mb--10"><span style="font-weight: 400; margin-right: 10px;">SKU: </span> {{ $product->sku ?? 'Đang cập nhật' }}</span>
                                                        @if($product->categories->isNotEmpty())
                                                            <span class="catagorys product-unipue mb--10"><span style="font-weight: 400; margin-right: 10px;">Danh mục: </span>
                                                                {{ $product->categories->pluck('name')->join(', ') }}
                                                            </span>
                                                        @endif
                                                        @if($product->brand)
                                                            <span class="tags product-unipue mb--10"><span style="font-weight: 400; margin-right: 10px;">Thương hiệu: </span> {{ $product->brand->name }}</span>
                                                        @endif
                                                    </div>
                                                    @if($product->activeCombos && $product->activeCombos->isNotEmpty())
                                                        <div class="product-combo-section mt--20 mb--20" x-data="comboManager()">
                                                            <h6 class="title mb--15" style="font-size: 11px; font-weight: 800; text-transform: uppercase; color: #444; letter-spacing: 0.8px; border-bottom: 1px solid #efefef; padding-bottom: 8px;">THƯỜNG ĐƯỢC MUA CÙNG</h6>
                                                            
                                                            <div class="combo-list d-flex flex-column gap-2 mt-1">
                                                                @foreach($product->activeCombos as $combo)
                                                                    <div class="single-combo-item d-flex align-items-center gap-3 p-1 rounded-2 hover-shadow transition-all" style="border: 1px solid transparent;">
                                                                        <div class="checkbox-area">
                                                                            <input type="checkbox" 
                                                                                   id="combo-{{ $combo->id }}" 
                                                                                   value="{{ $combo->id }}"
                                                                                   x-model="selectedCombos"
                                                                                   class="form-check-input"
                                                                                   style="width: 15px; height: 15px; cursor: pointer; border-color: #eee;"
                                                                                   @change="updateSelectedItems()">
                                                                        </div>
                                                                        <div class="combo-img flex-shrink-0" style="width: 50px; height: 50px; border: 1px solid #f5f5f5; border-radius: 6px; overflow: hidden; background: #fff;">
                                                                            <img src="{{ $combo->thumbnail_url }}" alt="{{ $combo->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                                                        </div>
                                                                        <div class="combo-info d-flex align-items-center justify-content-between flex-grow-1 gap-3">
                                                                            <label for="combo-{{ $combo->id }}" class="m-0" style="cursor: pointer; max-width: 250px;">
                                                                                <h6 class="name m-0" style="font-size: 12px; font-weight: 600; line-height: 1.3; color: #333; height: 1.3em; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">{{ $combo->name }}</h6>
                                                                                <div class="pricing-area d-flex align-items-center gap-2">
                                                                                    <span class="original-price text-decoration-line-through" style="font-size: 11px; color: #999;" x-text="getItemOriginalPriceDisplay({{ $combo->id }})"></span>
                                                                                    <span class="combo-price" style="font-size: 12px; font-weight: 700; color: #d32f2f;" x-text="getItemDiscountPriceDisplay({{ $combo->id }})"></span>
                                                                                </div>
                                                                            </label>

                                                                            @if($combo->activeVariants->isNotEmpty())
                                                                                <select class="form-select form-select-sm py-0 px-2" 
                                                                                        style="font-size: 11px; height: 26px; border-radius: 4px; border: 1px solid #ececec; background-color: #fcfcfc; width: auto; min-width: 120px;"
                                                                                        x-model="itemVariants[{{ $combo->id }}]"
                                                                                        @change="updateSelectedItems()">
                                                                                    @foreach($combo->activeVariants as $v)
                                                                                        <option value="{{ $v->id }}">
                                                                                            {{ $v->label ?: ($v->sku ?: 'Biến thể') }} - {{ number_format($v->price, 0, ',', '.') }}₫
                                                                                        </option>
                                                                                    @endforeach
                                                                                </select>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>

                                                            <div class="combo-footer d-flex align-items-center justify-content-between mt--20 pt--15" style="border-top: 1px dashed #f0f0f0;">
                                                                <div class="total-price-area">
                                                                    <div class="mb-1" style="font-size: 11px; color: #999;">Đã chọn: <span class="fw-bold" x-text="selectedCombos.length + 1">1</span> sản phẩm</div>
                                                                    <span style="font-size: 12px; color: #333; font-weight: 600;">Tổng cộng: </span>
                                                                    <span class="total-val" style="font-size: 16px; font-weight: 800; color: #d32f2f; margin-left: 5px;" x-text="formatPrice(totalPrice)">0 đ</span>
                                                                </div>
                                                                <button type="button" 
                                                                        class="btn btn-primary d-flex align-items-center gap-2" 
                                                                        style="background: #e74c3c; border: none; border-radius: 4px; font-size: 12px; font-weight: 700; padding: 10px 20px !important; transition: all 0.2s;"
                                                                        :class="adding ? 'opacity-70' : ''"
                                                                        :disabled="adding"
                                                                        @click="addCombosToCart()">
                                                                    <i class="fa-solid fa-cart-shopping" style="font-size: 11px;" x-show="!adding"></i>
                                                                    <i class="fa-solid fa-spinner fa-spin" style="font-size: 11px;" x-show="adding" x-cloak></i>
                                                                    <span>MUA NGAY <span x-text="selectedCombos.length + 1">1</span> SP</span>
                                                                </button>
                                                            </div>
                                                        </div>

                                                        <style>
                                                            .hover-shadow:hover { border-color: #f0f0f0 !important; background: #fafafa; }
                                                            [x-cloak] { display: none !important; }
                                                        </style>

                                                        <script>
                                                            function comboManager() {
                                                                return {
                                                                    selectedCombos: [],
                                                                    adding: false,
                                                                    // Main product context
                                                                    mainProductId: {{ $product->id }},
                                                                    // Helper: get current main price (accounting for variant if selected in top part - but here we assume base price or current price)
                                                                    // In a real app we'd sync this with the main variant selector. 
                                                                    // For simplicity, we use the price passed from controller.
                                                                    mainPrice: {{ (float)$product->price }},
                                                                    
                                                                    itemVariants: {
                                                                        @foreach($product->activeCombos as $combo)
                                                                            {{ $combo->id }}: '{{ $combo->activeVariants->first()?->id ?? "" }}',
                                                                        @endforeach
                                                                    },
                                                                    data: {
                                                                        @foreach($product->activeCombos as $combo)
                                                                            {{ $combo->id }}: {
                                                                                base: {{ $combo->price }},
                                                                                discount_type: '{{ $combo->pivot->discount_type }}',
                                                                                discount_value: {{ $combo->pivot->discount_value }},
                                                                                variants: {
                                                                                    @foreach($combo->activeVariants as $v)
                                                                                        {{ $v->id }}: {{ $v->price }},
                                                                                    @endforeach
                                                                                }
                                                                            },
                                                                        @endforeach
                                                                    },
                                                                    totalPrice: 0,

                                                                    init() {
                                                                        this.updateSelectedItems();
                                                                    },

                                                                    getItemOriginalPriceDisplay(id) {
                                                                        const vId = this.itemVariants[id];
                                                                        const price = vId && this.data[id].variants[vId] ? this.data[id].variants[vId] : this.data[id].base;
                                                                        return this.formatPrice(price);
                                                                    },

                                                                    getItemDiscountPriceDisplay(id) {
                                                                        const vId = this.itemVariants[id];
                                                                        const basePrice = vId && this.data[id].variants[vId] ? this.data[id].variants[vId] : this.data[id].base;
                                                                        const { discount_type, discount_value } = this.data[id];
                                                                        
                                                                        let final = basePrice;
                                                                        if (discount_type === 'percent') {
                                                                            final = basePrice * (1 - (discount_value / 100));
                                                                        } else {
                                                                            final = Math.max(0, basePrice - discount_value);
                                                                        }
                                                                        return this.formatPrice(final);
                                                                    },

                                                                    updateSelectedItems() {
                                                                        // Get current main variant if available (sync from page or use base)
                                                                        let currentMainPrice = parseFloat(this.mainPrice);
                                                                        
                                                                        // Try to sync with main product selector if possible
                                                                        const mainVar = document.querySelector('input[name="variant_id"]:checked');
                                                                        if (mainVar) {
                                                                            // This would require more complex sync, but let's assume base for now 
                                                                            // or use a global variable if the theme provides one.
                                                                        }

                                                                        let total = currentMainPrice;
                                                                        this.selectedCombos.forEach(id => {
                                                                            const vId = this.itemVariants[id];
                                                                            const basePrice = vId && this.data[id].variants[vId] ? this.data[id].variants[vId] : this.data[id].base;
                                                                            const { discount_type, discount_value } = this.data[id];
                                                                            
                                                                            let final = basePrice;
                                                                            if (discount_type === 'percent') {
                                                                                final = basePrice * (1 - (discount_value / 100));
                                                                            } else {
                                                                                final = Math.max(0, basePrice - discount_value);
                                                                            }
                                                                            total += final;
                                                                        });
                                                                        this.totalPrice = total;
                                                                    },

                                                                    formatPrice(val) {
                                                                        return new Intl.NumberFormat('vi-VN').format(Math.round(val)) + '₫';
                                                                    },

                                                                    async addCombosToCart() {
                                                                        this.adding = true;
                                                                        
                                                                        // 1. Prepare items starting with main product
                                                                        const mainVariantId = document.querySelector('input[name="variant_id"]:checked')?.value || null;
                                                                        const mainQty = parseInt(document.querySelector('.quantity-edit .input')?.value) || 1;

                                                                        const items = [
                                                                            { id: this.mainProductId, variant_id: mainVariantId, qty: mainQty }
                                                                        ];

                                                                        // 2. Add selected combo items
                                                                        this.selectedCombos.forEach(id => {
                                                                            items.push({
                                                                                id: id,
                                                                                variant_id: this.itemVariants[id] || null,
                                                                                qty: 1
                                                                            });
                                                                        });
                                                                        
                                                                        for(const item of items) {
                                                                            try {
                                                                                await fetch('{{ route("cart.add") }}', {
                                                                                    method: 'POST',
                                                                                    headers: {
                                                                                        'Content-Type': 'application/json',
                                                                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                                                                    },
                                                                                    body: JSON.stringify({
                                                                                        product_id: item.id,
                                                                                        variant_id: item.variant_id,
                                                                                        qty: item.qty,
                                                                                        main_product_id: this.mainProductId
                                                                                    })
                                                                                });
                                                                            } catch(e) {
                                                                                console.error('Error adding to cart', e);
                                                                            }
                                                                        }
                                                                        window.location.href = '{{ route("cart.page") }}';
                                                                    }
                                                                }
                                                            }
                                                        </script>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Bottom: Description Tabs -->
                        <div class="product-discription-tab-shop">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="home-tab" data-bs-toggle="tab"
                                        data-bs-target="#home-tab-pane" type="button" role="tab"
                                        aria-controls="home-tab-pane" aria-selected="true">Chi tiết sản phẩm</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="profile-tab" data-bs-toggle="tab"
                                        data-bs-target="#profile-tab-pane" type="button" role="tab"
                                        aria-controls="profile-tab-pane" aria-selected="false">Thông tin bổ sung</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="profile-tabt" data-bs-toggle="tab"
                                        data-bs-target="#profile-tab-panes" type="button" role="tab"
                                        aria-controls="profile-tab-panes" aria-selected="false">Đánh Giá ({{ $reviewCount }})</button>
                                </li>
                            </ul>

                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel"
                                    aria-labelledby="home-tab" tabindex="0">
                                    <div class="single-tab-content-shop-details">
                                        <div class="disc">
                                            {!! $product->description !!}
                                        </div>
                                        @if($product->content)
                                            <div class="mt--40">
                                                <h4 class="title mb--20">Thông tin chi tiết</h4>
                                                {!! $product->content !!}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel"
                                    aria-labelledby="profile-tab" tabindex="0">
                                    <div class="single-tab-content-shop-details">
                                        @if($product->additional_info)
                                            <div class="additional-info-content mb--30">
                                                {!! $product->additional_info !!}
                                            </div>
                                        @endif

                                        @if($product->productAttributes && $product->productAttributes->isNotEmpty())
                                            <h4 class="title mb--20">Thông số kỹ thuật</h4>
                                            <div class="table-responsive table-shop-details-pd">
                                                <table class="table">
                                                    <tbody>
                                                        @foreach($product->productAttributes as $pa)
                                                            <tr>
                                                                <td style="width: 30%; font-weight: 600;">{{ $pa->attribute->name }}</td>
                                                                <td>{{ $pa->attributeValue->value }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @endif

                                        @if(!$product->additional_info && (!$product->productAttributes || $product->productAttributes->isEmpty()))
                                            <p class="disc">Đang cập nhật...</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="profile-tab-panes" role="tabpanel"
                                    aria-labelledby="profile-tabt" tabindex="0">
                                    <div class="single-tab-content-shop-details">
                                        <div class="product-details-review-product-style" x-data="{ showReviewForm: false, rating: 0, hoverRating: 0 }" style="gap: 0;">
                                            @php
                                                $approvedReviews = $product->approvedReviews;
                                                $reviewCount = $approvedReviews->count();
                                                $avgRating = $reviewCount > 0 ? round($approvedReviews->avg('rating'), 1) : 5.0;

                                                $ratingStats = [];
                                                for ($i = 5; $i >= 1; $i--) {
                                                    $count = $approvedReviews->where('rating', $i)->count();
                                                    $percent = $reviewCount > 0 ? round(($count / $reviewCount) * 100) : 0;
                                                    $ratingStats[$i] = $percent;
                                                }
                                            @endphp

                                            <div class="review-summary-block w-100" style="background: #fdfdfd; padding: 30px; border-radius: 8px; border: 1px solid #f1f1f1; margin-bottom: 30px;">
                                                <div class="row align-items-center">
                                                    <div class="col-lg-6 col-md-12 pe-lg-4" style="border-right: 1px solid #f1f1f1;">
                                                        <div class="d-flex align-items-center mb-4">
                                                            <h2 class="m-0 me-3" style="font-size: 52px; font-weight: 700; color: #333;">{{ number_format($avgRating, 1) }}</h2>
                                                            <div>
                                                                <div class="stars mb-1" style="color: #FFB800; font-size: 20px;">
                                                                    @for($i = 1; $i <= 5; $i++)
                                                                        <i class="{{ $i <= round($avgRating) ? 'fa-solid' : 'fa-regular' }} fa-star"></i>
                                                                    @endfor
                                                                    <span style="color: #666; font-size: 14px; margin-left: 10px; font-weight: normal;">{{ $reviewCount }} đánh giá</span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="review-charts-details">
                                                            @for ($star = 5; $star >= 1; $star--)
                                                                @php
                                                                    $count = $approvedReviews->where('rating', $star)->count();
                                                                @endphp
                                                                <div class="single-review d-flex align-items-center mb-2">
                                                                    <div class="stars" style="color: #FFB800; font-size: 13px; width: 85px;">
                                                                        @for($i = 1; $i <= 5; $i++)
                                                                            <i class="{{ $i <= $star ? 'fa-solid' : 'fa-regular' }} fa-star"></i>
                                                                        @endfor
                                                                    </div>
                                                                    <div class="single-progress-area-incard flex-grow-1 mx-3" style="margin: 0; padding: 0;">
                                                                        <div class="progress" style="height: 8px; background: #e9ecef; border-radius: 10px; overflow: hidden; box-shadow: none;">
                                                                            <div class="progress-bar" role="progressbar"
                                                                                style="width: {{ $ratingStats[$star] }}%; background-color: #f15922;" aria-valuenow="{{ $ratingStats[$star] }}" aria-valuemin="0"
                                                                                aria-valuemax="100"></div>
                                                                        </div>
                                                                    </div>
                                                                    <span class="pac" style="font-size: 14px; font-weight: 600; min-width: 60px; color: #333; text-align: left;">{{ $ratingStats[$star] }}% <span style="color: #888; font-weight: 400;">|</span> {{ $count }}</span>
                                                                </div>
                                                            @endfor
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-6 col-md-12 mt-4 mt-lg-0 ps-lg-4">
                                                        <div id="review-form-section" class="submit-review-area" style="background-color: #efefef; padding: 20px; border-radius: 8px;">
                                                            <form action="{{ route('review.submit') }}" method="POST" enctype="multipart/form-data" 
                                                                  @submit.prevent="
                                                                      if(rating < 1) { alert('Vui lòng chọn số sao đánh giá!'); return; }
                                                                      let btn = $event.target.querySelector('button[type=submit]');
                                                                      let originalText = btn.innerHTML;
                                                                      btn.innerHTML = 'ĐANG GỬI...'; btn.disabled = true;
                                                                      let formData = new FormData($event.target);
                                                                      fetch($event.target.action, { method: 'POST', body: formData, headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
                                                                      .then(res => res.json())
                                                                      .then(data => {
                                                                          alert(data.message);
                                                                          if(data.success) {
                                                                              $event.target.reset();
                                                                              rating = 0; hoverRating = 0;
                                                                          }
                                                                      })
                                                                      .catch(err => alert('Đã có lỗi xảy ra phía máy chủ, vui lòng thử lại!'))
                                                                      .finally(() => { btn.innerHTML = originalText; btn.disabled = false; });
                                                                  ">
                                                                @csrf
                                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                                <input type="hidden" name="rating" x-model="rating">

                                                                <div class="text-center mb-3">
                                                                    <div class="stars d-flex justify-content-center gap-1" style="font-size: 28px;" @mouseleave="hoverRating = 0">
                                                                        @for($i = 1; $i <= 5; $i++)
                                                                            <i class="fa-star" 
                                                                               :class="(hoverRating >= {{ $i }} || (hoverRating == 0 && rating >= {{ $i }})) ? 'fa-solid' : 'fa-regular'" 
                                                                               :style="(hoverRating >= {{ $i }} || (hoverRating == 0 && rating >= {{ $i }})) ? 'color: #FFB800; transform: scale(1.15);' : 'color: #dcdcdc; transform: scale(1);'"
                                                                               @click="rating = {{ $i }}" 
                                                                               @mouseenter="hoverRating = {{ $i }}"
                                                                               style="cursor: pointer; margin: 0 4px; transition: all 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275);"></i>
                                                                        @endfor
                                                                    </div>
                                                                </div>

                                                                <div class="row g-2 mb-2 d-flex align-items-center">
                                                                    <div class="col-md-6 ">
                                                                        <input type="text" name="customer_name" class="form-control" placeholder="Họ tên của bạn" required style="border: 1px solid #e0e0e0; border-radius: 4px; padding: 10px; font-size: 14px; width: 100%;">
                                                                    </div>
                                                                    <div class="col-md-6 ">
                                                                        <input type="email" name="customer_email" class="form-control" placeholder="Email của bạn" required style="border: 1px solid #e0e0e0; border-radius: 4px; padding: 10px; font-size: 14px; width: 100%;">
                                                                    </div>
                                                                </div>

                                                                <div class="mb-2 mt-2">
                                                                    <textarea name="comment" class="form-control" minlength="10" rows="3" placeholder="Hãy chia sẻ những điều bạn thích về sản phẩm này nhé (tối thiểu 10 ký tự)" required style="border: 1px solid #e0e0e0; border-radius: 4px; padding: 10px; font-size: 14px; width: 100%; resize: vertical;"></textarea>
                                                                </div>

                                                                <div class="d-flex flex-column align-items-start mt-2">
                                                                    <button type="submit" class="btn w-100" style="background-color: #0b5a96; color: white; padding: 12px; font-weight: bold; font-size: 15px; border-radius: 4px; border: none; text-transform: uppercase;">GỬI</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="product-reviews-list mt-5 w-100" x-data="{ activeStar: 'all' }">
                                                <div class="review-filter d-flex align-items-center mb-4 pb-4" style="border-bottom: 1px solid #eee;">
                                                    <span class="me-3" style="color: #555; font-size: 14px;">Lọc theo:</span>
                                                    <div class="d-flex flex-wrap gap-2">
                                                        <a href="javascript:void(0)" 
                                                           class="btn btn-sm px-3 py-1" 
                                                           @click="activeStar = 'all'"
                                                           :style="activeStar === 'all' ? 'background: #0b5a96; color: white; border-radius: 20px; font-weight: 500;' : 'background: #eef5fa; color: #0b5a96; border-radius: 20px; font-weight: 500;'">Tất cả</a>
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <a href="javascript:void(0)" 
                                                               class="btn btn-sm px-3 py-1 shadow-none" 
                                                               @click="activeStar = {{ $i }}"
                                                               :style="activeStar === {{ $i }} ? 'background: #0b5a96; color: white; border-radius: 20px;' : 'background: #f4f4f4; color: #666; border-radius: 20px; border: none;'">
                                                                {{$i}} <i class="fa-solid fa-star" :style="activeStar === {{ $i }} ? 'color: #FFB800; font-size: 10px;' : 'color: #ccc; font-size: 10px;'"></i>
                                                            </a>
                                                        @endfor
                                                    </div>
                                                </div>

                                                <div class="reviews-list-area">
                                                    @forelse($approvedReviews as $review)
                                                        <div class="single-review-item mb-4 pb-4" 
                                                             style="border-bottom: 1px solid #eee;" 
                                                             x-show="activeStar === 'all' || activeStar == {{ $review->rating }}"
                                                             x-transition:enter="transition ease-out duration-300"
                                                             x-transition:enter-start="opacity-0 transform -translate-y-2"
                                                             x-transition:enter-end="opacity-100 transform translate-y-0"
                                                             x-data="{ liked: false, count: {{ rand(0, 10) }} }">
                                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                                <div class="d-flex align-items-center">
                                                                    @php
                                                                        $nameParts = explode(' ', trim($review->customer_name ?? 'Khách Hàng'));
                                                                        $firstChar = mb_substr($nameParts[0], 0, 1, 'UTF-8');
                                                                        $lastChar = count($nameParts) > 1 ? mb_substr(end($nameParts), 0, 1, 'UTF-8') : '';
                                                                        $initials = mb_strtoupper($firstChar . $lastChar, 'UTF-8');

                                                                        $title = 'Đánh giá sản phẩm';
                                                                        if ($review->rating == 5)
                                                                            $title = 'Cực kì hài lòng';
                                                                        elseif ($review->rating == 4)
                                                                            $title = 'Hài lòng';
                                                                        elseif ($review->rating == 3)
                                                                            $title = 'Bình thường';
                                                                        elseif ($review->rating == 2)
                                                                            $title = 'Không hài lòng';
                                                                        elseif ($review->rating == 1)
                                                                            $title = 'Rất tệ';
                                                                    @endphp
                                                                    <div class="avatar-circle me-3 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; background: #0b5a96; color: white; border-radius: 50%; font-weight: bold; font-size: 16px;">
                                                                        {{ $initials }}
                                                                    </div>
                                                                    <div>
                                                                        <h6 class="m-0" style="font-weight: 700; color: #0b5a96; font-size: 15px;">{{ $review->customer_name }}</h6>
                                                                        <div class="verified-purchase text-success mt-1" style="font-size: 12px; font-weight: 500;">
                                                                            <i class="fa-solid fa-circle-check"></i> Đã Mua Hàng Tại VIETTINMART
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="stars d-flex gap-1" style="color: #FFB800; font-size: 12px;">
                                                                    @for($i = 1; $i <= 5; $i++)
                                                                        <i class="{{ $i <= $review->rating ? 'fa-solid' : 'fa-regular' }} fa-star"></i>
                                                                    @endfor
                                                                </div>
                                                            </div>

                                                            <div class="review-content-box ms-5 p-3" style="background: #f8f9fa; border-radius: 8px;">
                                                                <h6 style="font-size: 14px; font-weight: 700; margin-bottom: 8px;">{{ $title }}</h6>
                                                                <p style="font-size: 14px; color: #444; margin-bottom: 15px;">{{ $review->comment }}</p>
                                                                <span style="font-size: 12px; color: #999;"><i class="fa-regular fa-clock me-1"></i> {{ $review->created_at ? $review->created_at->diffForHumans() : 'Vài ngày trước' }}</span>
                                                            </div>

                                                            <div class="review-actions ms-5 mt-3 d-flex align-items-center">
                                                                <button class="btn btn-sm px-2 py-0 me-3 d-flex align-items-center" 
                                                                        :style="liked ? 'background: #0b5a96; color: white; border: 1px solid #0b5a96;' : 'background: transparent; color: #0b5a96; border: 1px solid #0b5a96;'"
                                                                        @click="liked = !liked; liked ? count++ : count--"
                                                                        style="border-radius: 4px; font-size: 11px; font-weight: 500; height: 24px; transition: all 0.2s;">
                                                                    <i :class="liked ? 'fa-solid' : 'fa-regular'" class="fa-thumbs-up me-1"></i> Like <span class="ms-1" x-text="count"></span>
                                                                </button>
                                                               
                                                            </div>
                                                        </div>
                                                    @empty
                                                        <p class="text-center text-muted py-4">Chưa có đánh giá nào. Hãy là người đầu tiên đánh giá sản phẩm này!</p>
                                                    @endforelse

                                                    @if($reviewCount > 10)
                                                        <div class="d-flex justify-content-center mt-5 mb-4">
                                                            <div class="pagination-mock d-flex gap-2">
                                                                 <button class="btn btn-sm btn-light" disabled style="border: 1px solid #ddd; background: #fafafa;"><i class="fa-solid fa-angles-left" style="color: #999;"></i></button>
                                                                 <button class="btn btn-sm btn-light" disabled style="border: 1px solid #ddd; background: #fafafa; color: #999; font-weight: 500;">Prev</button>
                                                                 <button class="btn btn-sm" style="background: #0b5a96; color: white; border: 1px solid #0b5a96; font-weight: bold; width: 32px;">1</button>
                                                                 <button class="btn btn-sm btn-light" style="border: 1px solid #ddd; background: #fafafa; color: #555; width: 32px;">2</button>
                                                                 <button class="btn btn-sm btn-light" style="border: 1px solid #ddd; background: #fafafa; color: #555; width: 32px;">3</button>
                                                                 <button class="btn btn-sm btn-light" style="border: 1px solid #ddd; background: #fafafa; color: #555; font-weight: 500;">Next</button>
                                                                 <button class="btn btn-sm btn-light" style="border: 1px solid #ddd; background: #fafafa;"><i class="fa-solid fa-angles-right" style="color: #666;"></i></button>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Sidebar (Offers, Payment) -->
                    <div class="col-xl-3 col-lg-4 col-md-12 rts-sticky-column-item">
                        <div class="theiaStickySidebar sticky-top" style="top: 120px; z-index: 10;">
                            <div class="shop-sight-sticky-sidevbar mb--20">
                                <h6 class="title">Ưu đãi nổi bật</h6>
                                <div class="single-offer-area">
                                    <div class="icon">
                                        <img src="{{ asset('theme/images/shop/01.svg') }}" alt="icon">
                                    </div>
                                    <div class="details">
                                        <p>Giảm ngay 5% cho đơn hàng đầu tiên thanh toán qua chuyển khoản ngân hàng</p>
                                    </div>
                                </div>
                                <div class="single-offer-area">
                                    <div class="icon">
                                        <img src="{{ asset('theme/images/shop/02.svg') }}" alt="icon">
                                    </div>
                                    <div class="details">
                                        <p>Trả góp 0% khi mua hàng với thẻ tín dụng cho đơn hàng trên 3,000,000đ</p>
                                    </div>
                                </div>
                                <div class="single-offer-area">
                                    <div class="icon">
                                        <img src="{{ asset('theme/images/shop/03.svg') }}" alt="icon">
                                    </div>
                                    <div class="details">
                                        <p>Miễn phí giao hàng trên toàn quốc cho mọi đơn hàng giá trị trên 500,000đ</p>
                                    </div>
                                </div>
                            </div>
                            <div class="our-payment-method">
                                <h5 class="title">Thanh toán an toàn bảo mật</h5>
                                <img src="{{ asset('theme/images/shop/03.png') }}" alt="payment">
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
