@extends('layouts.app')

@section('title', 'Giỏ hàng - ' . setting('site_name', 'VietTinMart'))

@section('content')

{{-- Breadcrumb --}}
<div class="rts-breadcrumb-area breadcrumb-bg bg_image">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                <h1 class="title">Giỏ hàng</h1>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Trang chủ</a></li>
                        <li class="breadcrumb-item active">Giỏ hàng</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="rts-cart-area rts-section-gap bg_light-1">
    <div class="container">

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(empty($cart))
        <div class="row g-5">
            <div class="col-12 text-center py-5">
                <i class="fa-light fa-cart-shopping" style="font-size:5rem;color:#ccc;"></i>
                <h3 class="mt-4 mb-3">Giỏ hàng của bạn đang trống</h3>
                <p class="text-muted mb-4">Hãy thêm sản phẩm vào giỏ hàng để tiếp tục mua sắm.</p>
                <a href="{{ route('shop.index') }}" class="rts-btn btn-primary">
                    <i class="fa-regular fa-arrow-left me-2"></i>Tiếp tục mua sắm
                </a>
            </div>
        </div>
        @else
        <div class="row g-5">
            <div class="col-xl-9 col-lg-12 col-md-12 col-12 order-2 order-xl-1 order-lg-2 order-md-2 order-sm-2">
                <div class="cart-area-main-wrapper">
                    <div class="cart-top-area-note">
                        @php $remaining = max(0, 500000 - $total); @endphp
                        @if($remaining > 0)
                        <p>Thêm <span>{{ number_format($remaining) }}đ</span> để được miễn phí vận chuyển</p>
                        @else
                        <p>Bạn đã được <span>miễn phí vận chuyển</span></p>
                        @endif
                        <div class="bottom-content-deals mt--10">
                            <div class="single-progress-area-incard">
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar"
                                        style="width: {{ min(100, ($total / 500000) * 100) }}%"
                                        aria-valuenow="{{ min(100, ($total / 500000) * 100) }}"
                                        aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rts-cart-list-area">
                    <div class="single-cart-area-list head">
                        <div class="product-main">
                            <p>Sản phẩm</p>
                        </div>
                        <div class="price">
                            <p>Đơn giá</p>
                        </div>
                        <div class="quantity">
                            <p>Số lượng</p>
                        </div>
                        <div class="subtotal">
                            <p>Thành tiền</p>
                        </div>
                    </div>

                    @foreach($cart as $key => $item)
                    <div class="single-cart-area-list main item-parent cart-row" data-id="{{ $key }}">
                        <div class="product-main-cart">
                            <div class="close cart-remove-btn" data-id="{{ $key }}">
                                <i class="fa-regular fa-x"></i>
                            </div>
                            <div class="thumbnail">
                                <img src="{{ asset($item['image'] ?? 'theme/images/grocery/01.jpg') }}"
                                    alt="{{ $item['name'] }}">
                            </div>
                            <div class="information">
                                <a href="{{ route('slug.show', $item['slug'] ?? '#') }}">
                                    <h6 class="title">{{ $item['name'] }}</h6>
                                </a>
                            </div>
                        </div>
                        <div class="price">
                            <p class="cart-price">{{ number_format($item['price'], 0, ',', '.') }}đ</p>
                        </div>
                        <div class="quantity">
                            <div class="quantity-edit">
                                <input type="text" class="input qty-input" value="{{ $item['qty'] }}" data-id="{{ $key }}">
                                <div class="button-wrapper-action">
                                    <button class="button qty-btn minus" data-id="{{ $key }}">
                                        <i class="fa-regular fa-chevron-down"></i>
                                    </button>
                                    <button class="button plus qty-btn" data-id="{{ $key }}">
                                        +<i class="fa-regular fa-chevron-up"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="subtotal">
                            <p class="cart-row-total" data-id="{{ $key }}">
                                {{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}đ
                            </p>
                        </div>
                    </div>
                    @endforeach

                    <div class="bottom-cupon-code-cart-area">
                        <form action="#" id="coupon-form">
                            <input type="text" placeholder="Mã giảm giá">
                            <button type="submit" class="rts-btn btn-primary">Áp dụng</button>
                        </form>
                        <button id="clear-cart-btn" class="rts-btn btn-primary">Xóa giỏ hàng</button>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-12 col-md-12 col-12 order-1 order-xl-2 order-lg-1 order-md-1 order-sm-1">
                <div class="cart-total-area-start-right">
                    <h5 class="title">Tổng giỏ hàng</h5>
                    <div class="subtotal">
                        <span>Tạm tính</span>
                        <h6 class="price" id="cart-subtotal">{{ number_format($total, 0, ',', '.') }}đ</h6>
                    </div>
                    <div class="shipping">
                        <span>Vận chuyển</span>
                        <ul>
                            <li>
                                <input type="radio" id="f-option" name="selector" {{ $total >= 500000 ? 'checked' : '' }}>
                                <label for="f-option">Miễn phí vận chuyển</label>
                                <div class="check"></div>
                            </li>
                            <li>
                                <input type="radio" id="s-option" name="selector" {{ $total < 500000 ? 'checked' : '' }}>
                                <label for="s-option">Phí cố định: 30.000đ</label>
                                <div class="check"><div class="inside"></div></div>
                            </li>
                            <li>
                                <p>Miễn phí vận chuyển cho đơn từ 500.000đ</p>
                            </li>
                        </ul>
                    </div>
                    <div class="bottom">
                        <div class="wrapper">
                            <span>Tổng cộng</span>
                            <h6 class="price" id="cart-grand-total">
                                {{ number_format($total >= 500000 ? $total : $total + 30000, 0, ',', '.') }}đ
                            </h6>
                        </div>
                        <div class="button-area">
                            <a href="{{ route('checkout.index') }}" class="rts-btn btn-primary">Tiến hành thanh toán</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>

@endsection

@push('scripts')
<script>
(function () {
    const CART_REMOVE_URL = '{{ route("cart.remove") }}';
    const CART_UPDATE_URL = '{{ route("cart.update") }}';
    const CART_CLEAR_URL  = '{{ route("cart.clear") }}';
    const CSRF            = '{{ csrf_token() }}';

    function post(url, data) {
        return fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify(data),
        }).then(r => r.json());
    }

    function recalcTotals() {
        let subtotal = 0;
        document.querySelectorAll('.cart-row').forEach(row => {
            const id    = row.dataset.id;
            const qty   = parseInt(row.querySelector('.qty-input').value) || 1;
            const priceText = row.querySelector('.cart-price').textContent.replace(/[^\d]/g, '');
            const price = parseFloat(priceText);
            const rowTotal = price * qty;
            subtotal += rowTotal;
            row.querySelector('.cart-row-total').textContent = rowTotal.toLocaleString('vi-VN') + 'đ';
        });
        document.getElementById('cart-subtotal').textContent = subtotal.toLocaleString('vi-VN') + 'đ';
        const shipping = subtotal >= 500000 ? 0 : 30000;
        document.getElementById('cart-grand-total').textContent = (subtotal + shipping).toLocaleString('vi-VN') + 'đ';
    }

    // Qty buttons
    document.querySelectorAll('.qty-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const id    = this.dataset.id;
            const input = document.querySelector(`.qty-input[data-id="${id}"]`);
            let qty     = parseInt(input.value) || 1;
            if (this.classList.contains('plus')) qty++;
            else qty = Math.max(1, qty - 1);
            input.value = qty;
            post(CART_UPDATE_URL, { rowId: id, qty });
            recalcTotals();
        });
    });

    // Remove item
    document.querySelectorAll('.cart-remove-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const id  = this.dataset.id;
            const row = document.querySelector(`.cart-row[data-id="${id}"]`);
            post(CART_REMOVE_URL, { rowId: id }).then(() => {
                row.style.transition = 'opacity .3s';
                row.style.opacity = '0';
                setTimeout(() => { row.remove(); recalcTotals(); }, 300);
            });
        });
    });

    // Clear cart
    const clearBtn = document.getElementById('clear-cart-btn');
    if (clearBtn) {
        clearBtn.addEventListener('click', function () {
            if (!confirm('Bạn có chắc muốn xóa toàn bộ giỏ hàng?')) return;
            post(CART_CLEAR_URL, {}).then(() => location.reload());
        });
    }
})();
</script>
@endpush
