@extends('layouts.app')

@section('title', 'Thanh toán - ' . setting('site_name', 'VietTinMart'))

@section('content')

    <div class="rts-breadcrumb-area breadcrumb-bg bg_image">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    <h1 class="title">Thanh toán</h1>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Trang chủ</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('cart.page') }}">Giỏ hàng</a></li>
                            <li class="breadcrumb-item active">Thanh toán</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="checkout-area rts-section-gap">
        <div class="container">
            <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form">
                @csrf
                <div class="row">

                    {{-- ===== LEFT: Billing Form ===== --}}
                    <div
                        class="col-lg-8 pr--40 pr_md--5 pr_sm--5 order-2 order-xl-1 order-lg-2 order-md-2 order-sm-2 mt_md--30 mt_sm--30">

                        {{-- Login hint --}}
                        @guest
                            <div class="coupon-input-area-1 login-form mb--20">
                                <div class="coupon-area">
                                    <div class="coupon-ask">
                                        <span>Đã có tài khoản?</span>
                                        <button type="button" class="coupon-click" id="toggle-login">Nhấn đây để đăng
                                            nhập</button>
                                    </div>
                                    <div class="coupon-input-area" id="login-collapse" style="display:none;">
                                        <div class="inner">
                                            <p>Nếu bạn đã mua hàng trước đây, hãy nhập thông tin đăng nhập bên dưới.</p>
                                            <div class="form-area">
                                                <input type="email" id="login-email" placeholder="Email...">
                                                <input type="password" id="login-password" placeholder="Mật khẩu...">
                                                <button type="button" id="do-login-btn" class="btn-primary rts-btn">Đăng
                                                    nhập</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endguest

                        {{-- Coupon hint --}}
                        <div class="coupon-input-area-1 mb--20">
                            <div class="coupon-area">
                                <div class="coupon-ask cupon-wrapper-1">
                                    <button type="button" class="coupon-click" id="toggle-coupon">Có mã giảm giá? Nhấn đây
                                        để nhập mã</button>
                                </div>
                                <div class="coupon-input-area cupon1" id="coupon-collapse" style="display:none;">
                                    <div class="inner">
                                        <p class="mt--0 mb--20">Nếu bạn có mã giảm giá, hãy nhập vào bên dưới.</p>
                                        <div class="form-area">
                                            <input type="text" name="coupon_code" placeholder="Nhập mã giảm giá...">
                                            <button type="button" class="btn-primary rts-btn">Áp dụng</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Billing Details --}}
                        <div class="rts-billing-details-area">


                            <div class="single-input">
                                <label for="co-email">Địa chỉ Email *</label>
                                <input id="co-email" type="email" name="email"
                                    value="{{ old('email', auth()->user()?->email) }}" required>
                                @error('email')<span class="checkout-error">{{ $message }}</span>@enderror
                            </div>

                            <div class="half-input-wrapper">
                                <div class="single-input">
                                    <label for="co-name">Họ và tên *</label>
                                    <input id="co-name" type="text" name="name"
                                        value="{{ old('name', auth()->user()?->name) }}" required>
                                    @error('name')<span class="checkout-error">{{ $message }}</span>@enderror
                                </div>
                                <div class="single-input">
                                    <label for="co-phone">Số điện thoại *</label>
                                    <input id="co-phone" type="tel" name="phone" value="{{ old('phone') }}" required>
                                    @error('phone')<span class="checkout-error">{{ $message }}</span>@enderror
                                </div>
                            </div>

                            <x-location-selector :old-province="old('province_code')" :old-district="old('district_code')"
                                :old-ward="old('ward_code')" />

                            {{-- Street address --}}
                            <div class="single-input">
                                <label for="co-street">Số nhà, tên đường *</label>
                                <input id="co-street" type="text" name="street_address" value="{{ old('street_address') }}"
                                    placeholder="VD: 123 Nguyễn Huệ" required>
                                @error('street_address')<span class="checkout-error">{{ $message }}</span>@enderror
                            </div>

                            {{-- Notes --}}
                            <div class="single-input">
                                <label for="co-notes">Ghi chú đơn hàng</label>
                                <textarea id="co-notes" name="notes"
                                    placeholder="Ghi chú thêm về đơn hàng, ví dụ: thời gian hay chỉ dẫn địa điểm giao hàng...">{{ old('notes') }}</textarea>
                            </div>
                        </div>

                    </div>

                    {{-- ===== RIGHT: Order Summary ===== --}}
                    <div class="col-lg-4 order-1 order-xl-2 order-lg-1 order-md-1 order-sm-1">
                        <h3 class="title-checkout">Đơn hàng của bạn</h3>
                        <div class="right-card-sidebar-checkout">
                            <div class="top-wrapper">
                                <div class="product">Sản phẩm</div>
                                <div class="price">Giá</div>
                            </div>

                            @foreach($cart as $item)
                                <div class="single-shop-list">
                                    <div class="left-area">
                                        <a href="#" class="thumbnail">
                                            <img src="{{ asset($item['image'] ?? 'theme/images/grocery/01.jpg') }}"
                                                alt="{{ $item['name'] }}">
                                        </a>
                                        <a href="{{ route('slug.show', $item['slug'] ?? '#') }}" class="title">
                                            {{ $item['name'] }} × {{ $item['qty'] }}
                                        </a>
                                    </div>
                                    <span class="price">{{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}đ</span>
                                </div>
                            @endforeach

                            <div class="single-shop-list">
                                <div class="left-area"><span>Tạm tính</span></div>
                                <span class="price">{{ number_format($total, 0, ',', '.') }}đ</span>
                            </div>
                            <div class="single-shop-list">
                                <div class="left-area"><span>Vận chuyển</span></div>
                                <span class="price" id="co-shipping-display">
                                    {{ $total >= 500000 ? 'Miễn phí' : number_format(30000, 0, ',', '.') . 'đ' }}
                                </span>
                            </div>
                            <div class="single-shop-list">
                                <div class="left-area">
                                    <span style="font-weight:600;color:#2C3C28;">Tổng cộng:</span>
                                </div>
                                <span class="price" style="color:#629D23;" id="co-total-display">
                                    {{ number_format($total >= 500000 ? $total : $total + 30000, 0, ',', '.') }}đ
                                </span>
                            </div>

                            {{-- Payment methods --}}
                            <div class="cottom-cart-right-area">
                                <ul>
                                    <li>
                                        <input type="radio" id="pay-cod" name="payment_method" value="cod" checked>
                                        <label for="pay-cod">Thanh toán khi nhận hàng (COD)</label>
                                        <div class="check">
                                            <div class="inside"></div>
                                        </div>
                                    </li>
                                    <li>
                                        <input type="radio" id="pay-bank" name="payment_method" value="bank_transfer">
                                        <label for="pay-bank">Chuyển khoản ngân hàng</label>
                                        <div class="check"></div>
                                    </li>
                                    <li>
                                        <input type="radio" id="pay-wallet" name="payment_method" value="e_wallet">
                                        <label for="pay-wallet">Ví điện tử</label>
                                        <div class="check">
                                            <div class="inside"></div>
                                        </div>
                                    </li>
                                </ul>
                                <p class="mb--20 mt--15">Thông tin cá nhân của bạn sẽ được dùng để xử lý đơn hàng và hỗ trợ
                                    trải nghiệm mua sắm.</p>
                                <div class="single-category mb--30">
                                    <input id="agree-terms" name="agree_terms" type="checkbox" required>
                                    <label for="agree-terms">Tôi đã đọc và đồng ý với <a href="#">điều khoản dịch vụ</a>
                                        *</label>
                                </div>
                                <button type="submit" class="rts-btn btn-primary w-100" id="place-order-btn">Đặt hàng
                                    ngay</button>
                            </div>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>

@endsection

@push('styles')
    <style>
        .checkout-select {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            font-size: 15px;
            color: #333;
            background: #fff;
            appearance: auto;
            -webkit-appearance: auto;
            outline: none;
            transition: border-color .2s;
            cursor: pointer;
        }

        .checkout-select:focus {
            border-color: #629D23;
        }

        .checkout-select.is-invalid {
            border-color: #e74c3c;
        }

        .checkout-select:disabled {
            background: #f5f5f5;
            cursor: not-allowed;
        }

        .checkout-error {
            color: #e74c3c;
            font-size: 13px;
            display: block;
            margin-top: 4px;
        }
    </style>
@endpush

@push('scripts')
    <script>
        (function () {
            // Toggle login
            document.getElementById('toggle-login')?.addEventListener('click', function () {
                const el = document.getElementById('login-collapse');
                el.style.display = el.style.display === 'none' ? 'block' : 'none';
            });

            // AJAX Login during checkout
            document.getElementById('do-login-btn')?.addEventListener('click', function () {
                const email = document.getElementById('login-email').value;
                const password = document.getElementById('login-password').value;
                const btn = this;

                if (!email || !password) {
                    alert('Vui lòng nhập email và mật khẩu.');
                    return;
                }

                btn.disabled = true;
                btn.textContent = 'Đang đăng nhập...';

                fetch('{{ route("login") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ email, password })
                })
                    .then(r => r.json())
                    .then(res => {
                        if (res.success) {
                            // Success: Reload page to pre-fill authenticated user data
                            location.reload();
                        } else {
                            alert(res.message || 'Đăng nhập thất bại.');
                            btn.disabled = false;
                            btn.textContent = 'Đăng nhập';
                        }
                    })
                    .catch(() => {
                        alert('Lỗi kết nối.');
                        btn.disabled = false;
                        btn.textContent = 'Đăng nhập';
                    });
            });

            // Toggle coupon
            document.getElementById('toggle-coupon')?.addEventListener('click', function () {
                const el = document.getElementById('coupon-collapse');
                el.style.display = el.style.display === 'none' ? 'block' : 'none';
            });
        })();
    </script>
@endpush