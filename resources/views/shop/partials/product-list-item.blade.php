@php
    $image    = $product->image ? asset($product->image) : asset('theme/images/grocery/01.jpg');
    $price    = (float) $product->price;
    $oldPrice = ($product->compare_price && $product->compare_price > $price) ? (float) $product->compare_price : null;
    $discount = $oldPrice ? (int) round((1 - $price / $oldPrice) * 100) : 0;
@endphp

<div class="single-product-list-item d-flex gap-4 mb--20 p-3 bg-white rounded">
    <div class="thumbnail" style="min-width:120px;">
        <a href="{{ route('slug.show', $product->slug) }}">
            <img src="{{ $image }}" alt="{{ $product->name }}" style="width:120px;height:120px;object-fit:cover;border-radius:6px;">
        </a>
    </div>
    <div class="content flex-grow-1">
        <a href="{{ route('slug.show', $product->slug) }}">
            <h4 class="title">{{ $product->name }}</h4>
        </a>
        @if($product->short_description)
        <p class="disc mt-1">{{ Str::limit($product->short_description, 120) }}</p>
        @endif
        <div class="price-area mt-2">
            <span class="current">{{ number_format($price, 0, ',', '.') }}đ</span>
            @if($oldPrice)
            <span class="previous ms-2">{{ number_format($oldPrice, 0, ',', '.') }}đ</span>
            @endif
            @if($discount > 0)
            <span class="badge bg-danger ms-2">-{{ $discount }}%</span>
            @endif
        </div>
        <p class="mt-1 small {{ $product->stock > 0 ? 'text-success' : 'text-danger' }}">
            {{ $product->stock > 0 ? 'Còn hàng' : 'Hết hàng' }}
        </p>
    </div>
    <div class="action d-flex flex-column justify-content-center gap-2">
        <button class="rts-btn btn-primary radious-sm add-to-cart-btn"
            data-product-id="{{ $product->id }}"
            data-product-name="{{ $product->name }}"
            data-product-price="{{ $price }}"
            data-product-image="{{ $image }}"
            data-product-slug="{{ $product->slug }}">
            Thêm vào giỏ
        </button>
        <a href="{{ route('slug.show', $product->slug) }}" class="rts-btn btn-primary border-only">Xem chi tiết</a>
    </div>
</div>
