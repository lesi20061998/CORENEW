@extends('layouts.app')

@section('title', $product->name . ' - ' . setting('site_name', 'VietTinMart'))
@section('meta_description', $product->meta_description ?? Str::limit(strip_tags($product->description ?? ''), 160))
@section('meta_keywords', $product->meta_keywords ?? setting('seo_meta_keywords', ''))
@section('canonical', $product->canonical_url ?? url()->current())
@section('og_type', 'product')
@section('og_image', $product->image ? asset($product->image) : setting('seo_og_image', asset('theme/images/fav.png')))

@include('components.seo-schema', ['context' => 'product', 'model' => $product])

@section('content')

{{-- Breadcrumb --}}
<div class="rts-breadcrumb-area breadcrumb-bg bg_image">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                <h1 class="title">Chi tiết sản phẩm</h1>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Trang chủ</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('shop.index') }}">Cửa hàng</a></li>
                        @if($product->category)
                        <li class="breadcrumb-item">
                            <a href="{{ route('shop.index', ['category' => $product->category->slug]) }}">{{ $product->category->name }}</a>
                        </li>
                        @endif
                        <li class="breadcrumb-item active">{{ Str::limit($product->name, 40) }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

{{-- Product Detail --}}
<div class="rts-product-details-section rts-section-gap">
    <div class="container">
        <div class="row g-5">

            {{-- Gallery --}}
            <div class="col-lg-6 col-md-12">
                @php
                    $mainImage = $product->image;
                    if ($mainImage && !Str::startsWith($mainImage, ['http://', 'https://'])) {
                        $mainImage = Str::startsWith($mainImage, 'storage/') ? asset($mainImage) : asset('storage/' . $mainImage);
                    }
                    if (!$mainImage) $mainImage = asset('theme/images/grocery/01.jpg');

                    $gallery = $product->images ?? [];
                @endphp
                <div class="product-details-gallery">
                    <div class="main-image-wrapper">
                        <img src="{{ $mainImage }}" alt="{{ $product->name }}" id="main-product-image" class="img-fluid" style="width:100%;max-height:450px;object-fit:contain;">
                    </div>
                    @if(count($gallery) > 1)
                    <div class="thumbnail-gallery mt--20 d-flex gap-2 flex-wrap">
                        @foreach($gallery as $img)
                        @php
                            $gImg = $img;
                            if ($gImg && !Str::startsWith($gImg, ['http://', 'https://'])) {
                                $gImg = Str::startsWith($gImg, 'storage/') ? asset($gImg) : asset('storage/' . $gImg);
                            }
                        @endphp
                        <img src="{{ $gImg }}" alt="{{ $product->name }}"
                            class="thumb-img" onclick="changeMainImage(this)"
                            style="width:70px;height:70px;object-fit:cover;border-radius:6px;cursor:pointer;border:2px solid transparent;">
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>

            {{-- Info --}}
            <div class="col-lg-6 col-md-12">
                @php
                    $flashItem = app(\App\Services\FlashSaleService::class)->getActiveItemForProduct($product);
                    $price     = (float) ($flashItem ? $flashItem->calcFlashPrice((float)$product->price) : $product->price);
                    $oldPrice = $flashItem ? (float)$product->price : (($product->compare_price && $product->compare_price > $price) ? (float) $product->compare_price : null);
                    $discount = $flashItem ? $product->flash_discount_percent : ($oldPrice ? (int) round((1 - $price / $oldPrice) * 100) : 0);
                @endphp
                <div class="product-details-content">
                    @if($product->category)
                    <span class="category-badge">{{ $product->category->name }}</span>
                    @endif
                    <h1 class="product-title">{{ $product->name }}</h1>

                    {{-- Rating --}}
                    <div class="rating-area d-flex align-items-center gap-2 mb--15">
                        @for($i = 1; $i <= 5; $i++)
                        <i class="fa-solid fa-star" style="color:#f5a623;"></i>
                        @endfor
                        <span>(0 đánh giá)</span>
                    </div>

                    <div class="price-area mb--20">
                        <span class="current-price">{{ number_format($price, 0, ',', '.') }}đ</span>
                        @if($oldPrice)
                        <span class="old-price ms-2">{{ number_format($oldPrice, 0, ',', '.') }}đ</span>
                        <span class="discount-badge ms-2">-{{ $discount }}%</span>
                        @endif
                    </div>

                    @if($flashItem)
                    <div class="flash-sale-badge-wrapper mb--20 p-3 bg-danger text-white rounded-3 shadow-sm d-flex align-items-center justify-content-between">
                        <div>
                            <p class="mb-0 fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">
                                <i class="fa-solid fa-bolt-lightning me-1"></i> Flash Sale đang diễn ra
                            </p>
                            @if($flashItem->sale_limit)
                            <div class="mt-1 d-flex align-items-center gap-2">
                                <span class="bg-white text-danger px-2 py-0 rounded-pill fw-bold" style="font-size: 0.65rem;">
                                    Còn lại: {{ $flashItem->remaining }}
                                </span>
                                <div class="progress flex-grow-1" style="height: 6px; width: 80px; background: rgba(255,255,255,0.3);">
                                    @php $percent = round(($flashItem->sold_count / $flashItem->sale_limit) * 100); @endphp
                                    <div class="progress-bar bg-white" style="width: {{ $percent }}%"></div>
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="text-end">
                            <p class="mb-0 opacity-75" style="font-size: 0.6rem; font-weight: 800;">KẾT THÚC SAU:</p>
                            <span class="fw-black h6 mb-0">{{ $flashItem->campaign->ends_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    @endif

                    @if($product->short_description)
                    <p class="short-desc mb--20">{{ $product->short_description }}</p>
                    @endif

                    {{-- Stock --}}
                    <p class="availability mb--15">
                        <strong>Tình trạng:</strong>
                        @if($product->stock > 0)
                            <span class="text-success">Còn hàng ({{ $product->stock }})</span>
                        @else
                            <span class="text-danger">Hết hàng</span>
                        @endif
                    </p>

                    {{-- Variants --}}
                    @if($product->has_variants && $product->variants->count())
                    <div class="variants-wrapper mb--20">
                        <label class="variant-label fw-bold mb-2 d-block">Phân loại:</label>
                        <div class="variant-options d-flex flex-wrap gap-2">
                            @foreach($product->variants as $variant)
                            <button class="variant-btn {{ $loop->first ? 'active' : '' }}"
                                data-variant-id="{{ $variant->id }}"
                                data-price="{{ $variant->price ?? $price }}"
                                data-compare="{{ $variant->compare_price ?? $oldPrice }}"
                                data-stock="{{ $variant->stock }}"
                                onclick="selectVariant(this)">
                                {{ $variant->sku }}
                            </button>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Add to Cart --}}
                    <div class="cart-action-wrapper d-flex align-items-center gap-3 flex-wrap">
                        <div class="quantity-edit">
                            <input type="number" id="product-qty" class="input" value="1" min="1" max="{{ $product->stock ?: 99 }}">
                            <div class="button-wrapper-action">
                                <button class="button" onclick="changeQty(-1)"><i class="fa-regular fa-chevron-down"></i></button>
                                <button class="button plus" onclick="changeQty(1)">+<i class="fa-regular fa-chevron-up"></i></button>
                            </div>
                        </div>
                        <button class="rts-btn btn-primary radious-sm with-icon add-to-cart-btn"
                            data-product-id="{{ $product->id }}"
                            data-product-name="{{ $product->name }}"
                            data-product-price="{{ $price }}"
                            data-product-image="{{ $mainImage }}"
                            data-product-slug="{{ $product->slug }}"
                            id="add-to-cart-main">
                            <div class="btn-text">Thêm vào giỏ</div>
                            <div class="arrow-icon"><i class="fa-regular fa-cart-shopping"></i></div>
                            <div class="arrow-icon"><i class="fa-regular fa-cart-shopping"></i></div>
                        </button>
                        <button id="buy-now-btn" class="rts-btn btn-primary border-only">Mua ngay</button>
                    </div>

                    {{-- Meta --}}
                    <div class="product-meta mt--20">
                        @if($product->sku)
                        <p><strong>SKU:</strong> {{ $product->sku }}</p>
                        @endif
                        @if($product->category)
                        <p><strong>Danh mục:</strong>
                            <a href="{{ route('shop.index', ['category' => $product->category->slug]) }}">{{ $product->category->name }}</a>
                        </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Description Tabs --}}
        <div class="row mt--60">
            <div class="col-lg-12">
                <div class="product-tab-wrapper">
                    <ul class="nav nav-tabs product-details-tab" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-desc">Mô tả</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-reviews">Đánh giá</button>
                        </li>
                    </ul>
                    <div class="tab-content mt--30">
                        <div class="tab-pane fade show active" id="tab-desc">
                            {!! $product->description ?? '<p>Chưa có mô tả.</p>' !!}
                        </div>
                        <div class="tab-pane fade" id="tab-reviews">
                            <p>Chưa có đánh giá nào.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Related Products --}}
        @if($relatedProducts->count())
        <div class="row mt--60">
            <div class="col-lg-12">
                <h3 class="title-left mb--30">Sản phẩm liên quan</h3>
                <div class="row g-4">
                    @foreach($relatedProducts as $related)
                    <div class="col-xxl-3 col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12">
                        @include('widgets.partials.product-card', ['product' => $related])
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        @widgetArea('product_below')
    </div>
</div>

@endsection

@push('scripts')
<script>
function changeMainImage(el) {
    document.getElementById('main-product-image').src = el.src;
    document.querySelectorAll('.thumb-img').forEach(t => t.style.borderColor = 'transparent');
    el.style.borderColor = '#629D23';
}
function changeQty(delta) {
    const input = document.getElementById('product-qty');
    input.value = Math.max(1, parseInt(input.value) + delta);
}
function selectVariant(btn) {
    document.querySelectorAll('.variant-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    
    const price = btn.dataset.price;
    const variantId = btn.dataset.variantId;
    
    const addToCartBtn = document.getElementById('add-to-cart-main');
    addToCartBtn.dataset.productPrice = price;
    addToCartBtn.dataset.variantId = variantId;
    
    // Update price display
    document.querySelector('.current-price').textContent = new Intl.NumberFormat('vi-VN').format(price) + 'đ';
}

// Update add to cart logic (assuming it's in a global main.js or similar)
// Let's add the event listener here to be safe and ensure it sends variant_id
document.getElementById('add-to-cart-main')?.addEventListener('click', function() {
    const data = {
        product_id: this.dataset.productId,
        variant_id: this.dataset.variantId || '',
        qty: parseInt(document.getElementById('product-qty').value) || 1
    };
    
    fetch('{{ route("cart.add") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(data)
    })
    .then(r => r.json())
    .then(res => {
        if(res.success) {
            alert('Đã thêm vào giỏ hàng!');
            location.reload(); 
        }
    });
});

document.getElementById('buy-now-btn')?.addEventListener('click', function() {
    const mainBtn = document.getElementById('add-to-cart-main');
    const data = {
        product_id: mainBtn.dataset.productId,
        variant_id: mainBtn.dataset.variantId || '',
        qty: parseInt(document.getElementById('product-qty').value) || 1
    };
    
    fetch('{{ route("cart.add") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(data)
    })
    .then(r => r.json())
    .then(res => {
        if(res.success) {
            window.location.href = '{{ route("checkout.index") }}';
        }
    });
});
</script>
@endpush
