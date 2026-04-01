@extends('layouts.app')

@section('title', 'Thanh toán - ' . setting('site_name'))

@section('content')
<!-- rts navigation area -->
<div class="rts-navigation-area-breadcrumb">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="navigator-breadcrumb-wrapper">
                    <a href="{{ route('home') }}">Trang chủ</a>
                    <i class="fa-regular fa-chevron-right"></i>
                    <a href="{{ route('shop.index') }}">Cửa hàng</a>
                    <i class="fa-regular fa-chevron-right"></i>
                    <a class="current" href="#">Thanh toán</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="checkout-area rts-section-gap">
    <div class="container">
        <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form">
            @csrf
            <div class="row">
                <div class="col-lg-8 order-2 order-xl-1">
                    <div class="rts-billing-details-area checkout-form-container">
                        <h3 class="title">Thông tin giao hàng</h3>
                        <div class="half-input-wrapper">
                            <div class="single-input">
                                <label for="name">Họ và tên*</label>
                                <input id="name" name="name" type="text" value="{{ old('name', auth()->user()?->name) }}" required placeholder="Nhập họ tên của bạn">
                            </div>
                            <div class="single-input">
                                <label for="phone">Số điện thoại*</label>
                                <input id="phone" name="phone" type="text" value="{{ old('phone', auth()->user()?->phone) }}" required placeholder="Nhập số điện thoại">
                            </div>
                        </div>
                        <div class="single-input">
                            <label for="email">Địa chỉ Email*</label>
                            <input id="email" name="email" type="email" value="{{ old('email', auth()->user()?->email) }}" required placeholder="Nhập email của bạn">
                        </div>

                        <div class="half-input-wrapper">
                            <div class="single-input">
                                <label for="province">Tỉnh / Thành phố*</label>
                                <select id="province" name="province_code" class="form-select" required>
                                    <option value="">Chọn Tỉnh / Thành phố</option>
                                </select>
                                <input type="hidden" name="province_name" id="province_name">
                            </div>
                            <div class="single-input">
                                <label for="district">Quận / Huyện*</label>
                                <select id="district" name="district_code" class="form-select" required disabled>
                                    <option value="">Chọn Quận / Huyện</option>
                                </select>
                                <input type="hidden" name="district_name" id="district_name">
                            </div>
                        </div>

                        <div class="half-input-wrapper">
                            <div class="single-input">
                                <label for="ward">Phường / Xã*</label>
                                <select id="ward" name="ward_code" class="form-select" required disabled>
                                    <option value="">Chọn Phường / Xã</option>
                                </select>
                                <input type="hidden" name="ward_name" id="ward_name">
                            </div>
                            <div class="single-input">
                                <label for="street">Địa chỉ cụ thể*</label>
                                <input id="street" name="street_address" type="text" placeholder="Số nhà, tên đường..." required>
                            </div>
                        </div>

                        <div class="single-input mt--20">
                            <label for="ordernotes">Ghi chú đơn hàng (Tùy chọn)</label>
                            <textarea id="ordernotes" name="notes" placeholder="Ghi chú về đơn hàng, ví dụ: lưu ý đặc biệt khi giao hàng."></textarea>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 order-1 order-xl-2">
                    <h3 class="title-checkout">Đơn hàng của bạn</h3>
                    <div class="right-card-sidebar-checkout">
                        <div class="top-wrapper">
                            <div class="product">Sản phẩm</div>
                            <div class="price">Tổng cộng</div>
                        </div>
                        
                        <div class="cart-items-list" style="max-height: 400px; overflow-y: auto;">
                            @foreach($cart as $item)
                            <div class="single-shop-list" style="padding: 15px 0; border-bottom: 1px solid #eee;">
                                <div class="left-area">
                                    <a href="{{ route('shop.show', $item['slug']) }}" class="thumbnail">
                                        <img src="{{ asset($item['image']) }}" alt="{{ $item['name'] }}" style="width: 50px; height: 50px; object-fit: cover;">
                                    </a>
                                    <div class="info">
                                        <a href="{{ route('shop.show', $item['slug']) }}" class="title" style="font-size: 14px; display: block;">{{ $item['name'] }}</a>
                                        <span class="qty" style="font-size: 12px; color: #666;">x {{ $item['qty'] }}</span>
                                    </div>
                                </div>
                                <span class="price" style="font-weight: 500;">{{ number_format($item['price'] * $item['qty']) }} đ</span>
                            </div>
                            @endforeach
                        </div>

                        <div class="single-shop-list" style="margin-top: 20px;">
                            <div class="left-area"><span>Tạm tính</span></div>
                            <span class="price">{{ number_format($total) }} đ</span>
                        </div>
                        <div class="single-shop-list">
                            <div class="left-area"><span>Phí vận chuyển</span></div>
                            <span class="price shipping-fee">{{ $total >= 500000 ? 'Miễn phí' : '30.000 đ' }}</span>
                        </div>
                        <div class="single-shop-list" style="border-top: 2px solid var(--color-primary); padding-top: 15px;">
                            <div class="left-area"><span style="font-weight: 700; color: #2C3C28; font-size: 18px;">Tổng cộng:</span></div>
                            <span class="price total-price" style="color: var(--color-primary); font-size: 20px; font-weight: 800;">
                                {{ number_format($total + ($total >= 500000 ? 0 : 30000)) }} đ
                            </span>
                        </div>

                        <div class="cottom-cart-right-area">
                            <h4 style="font-size: 16px; margin-bottom: 15px;">Phương thức thanh toán</h4>
                            <ul>
                                <li style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                                    <input type="radio" id="cod" name="payment_method" value="cod" checked style="width: auto;">
                                    <label for="cod" style="margin: 0; cursor: pointer;">Thanh toán khi nhận hàng (COD)</label>
                                </li>
                                <li style="display: flex; align-items: center; gap: 10px;">
                                    <input type="radio" id="bacs" name="payment_method" value="bank_transfer" style="width: auto;">
                                    <label for="bacs" style="margin: 0; cursor: pointer;">Chuyển khoản ngân hàng</label>
                                </li>
                            </ul>
                            <p class="mb--20" style="font-size: 13px; line-height: 1.4; color: #777;">Dữ liệu cá nhân của bạn sẽ được sử dụng để xử lý đơn hàng và hỗ trợ trải nghiệm của bạn trên trang web này.</p>
                            <div class="single-category mb--30">
                                <input id="agree" type="checkbox" required>
                                <label for="agree" style="cursor: pointer;"> Tôi đã đọc và đồng ý với các điều khoản và điều kiện *</label>
                            </div>
                            <button type="submit" class="rts-btn btn-primary w-100" style="padding: 15px; font-weight: 700; font-size: 16px; border-radius: 8px;">ĐẶT HÀNG NGAY</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    const API_BASE = 'https://provinces.open-api.vn/api';

    // 1. Tải danh sách tỉnh/thành phố
    $.getJSON(`${API_BASE}/p/`, function(data) {
        let html = '<option value="">Chọn Tỉnh / Thành phố</option>';
        data.forEach(p => {
            html += `<option value="${p.code}" data-name="${p.name}">${p.name}</option>`;
        });
        $('#province').html(html);
    });

    // 2. Khi chọn tỉnh/thành phố -> Tải quận/huyện
    $('#province').on('change', function() {
        const pCode = $(this).val();
        const pName = $(this).find(':selected').data('name');
        $('#province_name').val(pName);

        $('#district').prop('disabled', true).html('<option value="">Đang tải...</option>');
        $('#ward').prop('disabled', true).html('<option value="">Chọn Phường / Xã</option>');

        if (pCode) {
            $.getJSON(`${API_BASE}/p/${pCode}?depth=2`, function(data) {
                let html = '<option value="">Chọn Quận / Huyện</option>';
                data.districts.forEach(d => {
                    html += `<option value="${d.code}" data-name="${d.name}">${d.name}</option>`;
                });
                $('#district').prop('disabled', false).html(html);
            });
        } else {
            $('#district').html('<option value="">Chọn Quận / Huyện</option>');
        }
    });

    // 3. Khi chọn quận/huyện -> Tải phường/xã
    $('#district').on('change', function() {
        const dCode = $(this).val();
        const dName = $(this).find(':selected').data('name');
        $('#district_name').val(dName);

        $('#ward').prop('disabled', true).html('<option value="">Đang tải...</option>');

        if (dCode) {
            $.getJSON(`${API_BASE}/d/${dCode}?depth=2`, function(data) {
                let html = '<option value="">Chọn Phường / Xã</option>';
                data.wards.forEach(w => {
                    html += `<option value="${w.code}" data-name="${w.name}">${w.name}</option>`;
                });
                $('#ward').prop('disabled', false).html(html);
            });
        } else {
            $('#ward').html('<option value="">Chọn Phường / Xã</option>');
        }
    });

    // 4. Khi chọn phường/xã -> Lưu tên
    $('#ward').on('change', function() {
        const wName = $(this).find(':selected').data('name');
        $('#ward_name').val(wName);
    });
});
</script>
@endpush

<style>
    .form-select {
        height: 50px;
        border: 1px solid #eee;
        border-radius: 5px;
        padding: 0 15px;
        width: 100%;
        outline: none;
        transition: 0.3s;
    }
    .form-select:focus {
        border-color: var(--color-primary);
    }
    .single-input label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: #2c3c28;
    }
    .single-shop-list .info {
        flex: 1;
        padding-left: 15px;
    }
    .checkout-area {
        background: #f9f9f9;
        padding: 60px 0;
    }
    .rts-billing-details-area {
        background: #fff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 5px 25px rgba(0,0,0,0.05);
    }
    .right-card-sidebar-checkout {
        background: #fff;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 5px 25px rgba(0,0,0,0.05);
    }
</style>
@endsection
