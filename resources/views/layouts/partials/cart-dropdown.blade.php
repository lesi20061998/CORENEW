@php
    $cartItems   = session('cart', []);
    $cartCount   = array_sum(array_column($cartItems, 'qty'));
    $cartTotal   = collect($cartItems)->sum(fn($i) => ($i['price'] ?? 0) * ($i['qty'] ?? 1));
    $freeShipMin = 500000;
    $remaining   = max(0, $freeShipMin - $cartTotal);
    $progress    = $freeShipMin > 0 ? min(100, round($cartTotal / $freeShipMin * 100)) : 0;
@endphp

<h5 class="shopping-cart-number">Giỏ hàng ({{ $cartCount }})</h5>

@forelse($cartItems as $key => $item)
<div class="cart-item-1 {{ $loop->first ? 'border-top' : '' }}">
    <div class="img-name">
        <div class="thumbanil">
            <img src="{{ asset($item['image'] ?? 'theme/images/shop/cart-1.png') }}" alt="{{ $item['name'] ?? '' }}">
        </div>
        <div class="details">
            <a href="{{ route('slug.show', $item['slug'] ?? '#') }}">
                <h5 class="title">{{ $item['name'] ?? '' }}</h5>
            </a>
            <div class="number">
                {{ $item['qty'] }} <i class="fa-regular fa-x"></i>
                <span>{{ number_format($item['price'] ?? 0) }}đ</span>
            </div>
        </div>
    </div>
    <div class="close-c1 dropdown-cart-remove" data-id="{{ $key }}" style="cursor:pointer;">
        <i class="fa-regular fa-x"></i>
    </div>
</div>
@empty
<p class="text-center py-3 text-muted">Giỏ hàng trống</p>
@endforelse

<div class="sub-total-cart-balance">
    <div class="bottom-content-deals mt--10">
        <div class="top">
            <span>Tạm tính:</span>
            <span class="number-c">{{ number_format($cartTotal) }}đ</span>
        </div>
        <div class="single-progress-area-incard">
            <div class="progress">
                <div class="progress-bar wow fadeInLeft" role="progressbar"
                    style="width: {{ $progress }}%"
                    aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>
        @if($remaining > 0)
        <p>Mua thêm <span>{{ number_format($remaining) }}đ</span> để được <span>Miễn phí giao hàng</span></p>
        @else
        <p class="text-success">Bạn được <span>Miễn phí giao hàng</span>!</p>
        @endif
    </div>
    <div class="button-wrapper d-flex align-items-center justify-content-between">
        <a href="{{ route('cart.page') }}" class="rts-btn btn-primary">Xem giỏ hàng</a>
        <a href="{{ route('checkout.index') }}" class="rts-btn btn-primary border-only">Thanh toán</a>
    </div>
</div>
