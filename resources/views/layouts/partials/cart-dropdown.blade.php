<h5 class="shopping-cart-number">Shopping Cart ({{ count(session('cart', [])) }})</h5>
@php 
    $cart = session('cart', []);
    $total = collect($cart)->sum(fn($i) => ($i['price'] ?? 0) * ($i['qty'] ?? 1));
@endphp

<div class="cart-items-mini-list" style="max-height: 300px; overflow-y: auto;">
    @foreach($cart as $key => $item)
    <div class="single-shop-list" style="padding: 10px 0; border-bottom: 1px dashed #eee; display: flex; gap: 10px;">
        <div class="left-area">
            <a href="{{ route('shop.show', ['slug' => $item['slug']]) }}" class="thumbnail">
                <img src="{{ asset($item['image']) }}" alt="{{ $item['name'] }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
            </a>
        </div>
        <div class="info" style="flex: 1;">
            <a href="{{ route('shop.show', ['slug' => $item['slug']]) }}" class="title" style="font-size: 13px; font-weight: 600; display: block; line-height: 1.2;">{{ $item['name'] }}</a>
            <span class="qty" style="font-size: 11px; color: #777;">{{ $item['qty'] }} x {{ number_format($item['price']) }}đ</span>
        </div>
        <div class="right-area">
            <button onclick="cart.remove('{{ $key }}')" class="remove-btn" style="border: none; background: none; color: #ccc;"><i class="fa-regular fa-times text-sm"></i></button>
        </div>
    </div>
    @endforeach
</div>

<div class="sub-total-cart-balance mt--20">
    <div class="top-wrapper d-flex justify-content-between mb--15">
        <span class="text" style="font-weight: 600;">Sub Total:</span>
        <span class="price" style="font-weight: 700; color: var(--color-primary);">{{ number_format($total) }}đ</span>
    </div>
    <div class="button-wrapper d-flex align-items-center justify-content-between gap-2">
        <a href="{{ route('cart.page') }}" class="rts-btn btn-primary" style="flex: 1; text-align: center; font-size: 12px; padding: 10px;">View Cart</a>
        <a href="{{ route('checkout.index') }}" class="rts-btn btn-primary border-only" style="flex: 1; text-align: center; font-size: 12px; padding: 10px;">Checkout</a>
    </div>
</div>
