@php 
            $cart = session('cart', []);
    $itemCount = count($cart);
    $total = collect($cart)->sum(fn($i) => ($i['price'] ?? 0) * ($i['qty'] ?? 1));
    $freeShippingLimit = (int) setting('free_shipping_threshold', 500000); // Mặc định 500k
    $percent = $freeShippingLimit > 0 ? min(100, round(($total / $freeShippingLimit) * 100)) : 100;
    $remaining = max(0, $freeShippingLimit - $total);
@endphp

<div class="category-sub-menu card-number-show">
    <h5 class="shopping-cart-number">Shopping Cart ({{ str_pad($itemCount, 2, '0', STR_PAD_LEFT) }})</h5>

    <div class="cart-items-mini-list" style="max-height: 400px; overflow-y: auto;">
        @forelse($cart as $key => $item)
            <div class="cart-item-1 {{ $loop->first ? 'border-top' : '' }}">
                <div class="img-name">
                    <div class="thumbanil">
                        @php
                            $imgPath = $item['image'];
                            if (!str_starts_with($imgPath, 'http')) {
                                if (str_starts_with($imgPath, 'storage/')) {
                                    $imgUrl = asset($imgPath);
                                } elseif (str_starts_with($imgPath, 'media/')) {
                                    $imgUrl = asset('storage/' . $imgPath);
                                } else {
                                    $imgUrl = asset($imgPath);
                                }
                            } else {
                                $imgUrl = $imgPath;
                            }
                        @endphp
                        <img src="{{ $imgUrl }}" alt="{{ $item['name'] }}" style="width: 70px; height: 70px; object-fit: cover; border-radius: 8px;">
                    </div>
                    <div class="details">
                        <a href="{{ route('shop.show', ['slug' => $item['slug']]) }}">
                            <h5 class="title">{{ $item['name'] }}</h5>
                        </a>
                        <div class="number">
                            {{ $item['qty'] }} <i class="fa-regular fa-x"></i>
                            <span>{{ number_format($item['price']) }}đ</span>
                        </div>
                    </div>
                </div>
                <div class="close-c1" onclick="if(typeof cart !== 'undefined') cart.remove('{{ $key }}')"
                    style="cursor: pointer;">
                    <i class="fa-regular fa-x"></i>
                </div>
            </div>
        @empty
            <div class="p-4 text-center">
                <div class="mb-2"><i class="fa-light fa-cart-shopping-slash fa-3x text-slate-200"></i></div>
                <div class="text-[12px] font-bold text-slate-400">Giỏ hàng trống</div>
            </div>
        @endforelse
    </div>

    @if($itemCount > 0)
        <div class="sub-total-cart-balance">
            <div class="bottom-content-deals mt--10">
                <div class="top">
                    <span>Sub Total:</span>
                    <span class="number-c">{{ number_format($total) }}đ</span>
                </div>
                <div class="single-progress-area-incard">
                    <div class="progress">
                        <div class="progress-bar wow fadeInLeft" role="progressbar" style="width: {{ $percent }}%"
                            aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                @if($remaining > 0)
                    <p>Mua thêm <span>{{ number_format($remaining) }}đ</span> để được <span>Miễn Phí Giao Hàng</span></p>
                @else
                    <p>Bạn đã đủ điều kiện được <span>Miễn Phí Giao Hàng</span>! 🎉</p>
                @endif
            </div>
            <div class="button-wrapper d-flex align-items-center justify-content-between">
                <a href="{{ route('cart.page') }}" class="rts-btn btn-primary">View Cart</a>
                <a href="{{ route('checkout.index') }}" class="rts-btn btn-primary border-only">CheckOut</a>
            </div>
        </div>
    @endif
</div>