@extends('layouts.app')

@section('title', 'Thanh toán - ' . setting('site_name'))

@section('content')
    <x-breadcrumb :items="[
            ['label' => 'Cửa hàng', 'url' => route('shop.index')],
            ['label' => 'Thanh toán']
        ]" />

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container--default .select2-selection--single {
            height: 50px !important;
            border: 1px solid #eeeeee !important;
            border-radius: 6px !important;
            text-align: left !important;
            display: block !important;
            position: relative !important;
            background-color: #fff !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #2C3C28 !important;
            font-weight: 500 !important;
            padding-left: 20px !important;
            padding-right: 30px !important;
            line-height: 48px !important;
            text-align: left !important;
            display: block !important;
            width: 100% !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 48px !important;
            position: absolute !important;
            top: 1px !important;
            right: 10px !important;
            width: 20px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }

        .select2-dropdown {
            border: 1px solid #eeeeee !important;
            border-radius: 6px !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1) !important;
            z-index: 9999 !important;
            text-align: left !important;
        }

        /* Ẩn hoàn toàn nút clear nếu vẫn còn dư âm */
        .select2-selection__clear {
            display: none !important;
        }

        .select2-search__field {
            border: 1px solid #eeeeee !important;
            border-radius: 4px !important;
            padding: 8px 12px !important;
            outline: none !important;
        }

        .select2-results__option--highlighted[aria-selected] {
            background-color: var(--color-primary) !important;
        }

        .select2-container {
            width: 100% !important;
        }

        #province+.nice-select,
        #ward+.nice-select {
            display: none !important;
        }
    </style>
    <div class="checkout-area rts-section-gap">
        <div class="container">


            <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form">
                @csrf
                <div class="row">
                    <!-- Billing Details -->
                    <div
                        class="col-lg-8 pr--40 pr_md--5 pr_sm--5 order-2 order-xl-1 order-lg-2 order-md-2 order-sm-2 mt_md--30 mt_sm--30">
                        <div class="rts-billing-details-area">
                            <h3 class="title animated fadeIn">Thông tin thanh toán</h3>

                            <div class="half-input-wrapper">
                                <div class="single-input">
                                    <label for="f-name">Họ và tên đệm*</label>
                                    <input id="f-name" name="first_name" type="text" placeholder="Nguyễn Văn" required
                                        value="{{ old('first_name', auth()->user()?->name) }}">
                                </div>
                                <div class="single-input">
                                    <label for="l-name">Tên*</label>
                                    <input id="l-name" name="last_name" type="text" placeholder="A" required
                                        value="{{ old('last_name') }}">
                                </div>
                            </div>

                            <div class="half-input-wrapper">
                                <div class="single-input">
                                    <label for="email">Địa chỉ Email*</label>
                                    <input id="email" name="email" type="email" placeholder="example@gmail.com" required
                                        value="{{ old('email', auth()->user()?->email) }}">
                                </div>
                                <div class="single-input">
                                    <label for="phone">Số điện thoại*</label>
                                    <input id="phone" name="phone" type="text" placeholder="0123 456 789" required
                                        value="{{ old('phone', auth()->user()?->phone) }}">
                                </div>
                            </div>

                            <div class="half-input-wrapper">
                                <div class="single-input">
                                    <label for="province">Tỉnh / Thành phố*</label>
                                    <select id="province" name="province_code" class="form-select rts-custom-select"
                                        required>
                                        <option value="">Chọn Tỉnh / Thành phố</option>
                                    </select>
                                    <input type="hidden" name="province_name" id="province_name">
                                </div>
                                <div class="single-input">
                                    <label for="ward">Phường / Xã*</label>
                                    <select id="ward" name="ward_code" class="form-select rts-custom-select" required
                                        disabled>
                                        <option value="">Chọn Phường / Xã</option>
                                    </select>
                                    <input type="hidden" name="ward_name" id="ward_name">
                                </div>
                            </div>

                            <div class="single-input">
                                <label for="street">Địa Chỉ Chi Tiết (Số nhà, tên đường...)*</label>
                                <input id="street" name="street_address" type="text"
                                    placeholder="Ví dụ: 123 Đường Nguyễn Trãi..." required
                                    value="{{ old('street_address') }}">
                            </div>

                            <div class="single-input">
                                <label for="ordernotes">Ghi chú đơn hàng (Tùy chọn)</label>
                                <textarea id="ordernotes" name="notes"
                                    placeholder="Ghi chú về đơn hàng, ví dụ: lưu ý đặc biệt khi giao hàng.">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="col-lg-4 order-1 order-xl-2 order-lg-1 order-md-1 order-sm-1">
                        <h3 class="title-checkout animated fadeIn">Đơn hàng của bạn</h3>
                        <div class="right-card-sidebar-checkout">
                            <div class="top-wrapper">
                                <div class="product">Sản phẩm</div>
                                <div class="price">Tổng cộng</div>
                            </div>

                            <div class="cart-items-list" style="max-height: 450px; overflow-y: auto;">
                                @foreach($cart as $item)
                                    <div class="single-shop-list">
                                        <div class="left-area">
                                            <a href="{{ route('product.show', $item['slug']) }}" class="thumbnail">
                                                @php
                                                    $imgSrc = $item['image']
                                                        ? (str_starts_with($item['image'], 'http') ? $item['image'] : asset($item['image']))
                                                        : asset('theme/images/shop/default.png');
                                                @endphp
                                                <img src="{{ $imgSrc }}" alt="{{ $item['name'] }}">
                                            </a>
                                            <div class="info" style="flex: 1; padding-left: 15px;">
                                                <a href="{{ route('product.show', $item['slug']) }}" class="title"
                                                    style="font-size: 14px; font-weight: 500; color: #2C3C28; line-height: 1.4; display: block; margin-bottom: 2px;">{{ $item['name'] }}</a>
                                                <span class="qty" style="font-size: 12px; color: #777;">Số lượng:
                                                    {{ $item['qty'] }}</span>
                                            </div>
                                        </div>
                                        <span class="price"
                                            style="font-weight: 500; color: #2C3C28;">{{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}đ</span>
                                    </div>
                                @endforeach
                            </div>

                            <div class="single-shop-list mt--20">
                                <div class="left-area"><span>Tạm tính</span></div>
                                <span class="price">{{ number_format($subtotal, 0, ',', '.') }}đ</span>
                            </div>

                            @if($totalDiscount > 0)
                                @foreach($couponList as $cItem)
                                <div class="single-shop-list">
                                    <div class="left-area"><span class="text-success">Giảm giá ({{ $cItem['code'] }})</span></div>
                                    <span class="price text-success">-{{ number_format($cItem['discount'], 0, ',', '.') }}đ</span>
                                </div>
                                @endforeach
                            @endif

                            <div class="single-shop-list">
                                <div class="left-area"><span>Phí vận chuyển</span></div>
                                <span class="price shipping-fee">{{ $subtotal >= 500000 ? 'Miễn phí' : '30.000đ' }}</span>
                            </div>

                            <div class="single-shop-list border-top pt--20">
                                <div class="left-area">
                                    <span style="font-weight: 600; color: #2C3C28; font-size: 18px;">Tổng cộng:</span>
                                </div>
                                <span class="price" style="color: #629D23; font-size: 20px; font-weight: 700;">
                                    @php
                                        $shipping = $subtotal >= 500000 ? 0 : 30000;
                                        $finalTotal = max(0, $subtotal - $totalDiscount + $shipping);
                                    @endphp
                                    {{ number_format($finalTotal, 0, ',', '.') }}đ
                                </span>
                            </div>

                            <div class="cottom-cart-right-area mt--30">
                                <h4 class="mb--20 h6 text-uppercase fw-extrabold" style="letter-spacing: 1px;">Phương thức
                                    thanh toán</h4>
                                <ul class="list-unstyled p-0 m-0">
                                    <li class="mb--15 d-flex align-items-center">
                                        <input type="radio" id="cod" name="payment_method" value="cod" checked
                                            style="width: auto; margin-right: 10px;">
                                        <label for="cod" class="m-0 pointer">Thanh toán khi nhận hàng (COD)</label>
                                    </li>
                                    <li class="mb--20 d-flex align-items-center">
                                        <input type="radio" id="bacs" name="payment_method" value="bank_transfer"
                                            style="width: auto; margin-right: 10px;">
                                        <label for="bacs" class="m-0 pointer">Chuyển khoản ngân hàng</label>
                                    </li>
                                </ul>

                                <p class="mb--25 small text-muted lh-base">
                                    Dữ liệu cá nhân của bạn sẽ được sử dụng để xử lý đơn hàng, hỗ trợ trải nghiệm của bạn
                                    trên trang web này và cho các mục đích khác được mô tả trong chính sách bảo mật của
                                    chúng tôi.
                                </p>

                                <div class="single-category mb--30">
                                    <input id="agree" type="checkbox" required name="agree"
                                        style="width: auto; margin-right: 10px;">
                                    <label for="agree" class="pointer small">Tôi đã đọc và đồng ý với các điều khoản & chính
                                        sách *</label>
                                </div>

                                <button type="submit" class="rts-btn btn-primary w-100 py-4 fw-bold h6 m-0 shadow-sm"
                                    style="border-radius: 8px; letter-spacing: 0.5px;">ĐẶT HÀNG NGAY</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            $(document).ready(function () {
                const API_BASE = 'https://provinces.open-api.vn/api';

                // Khởi tạo Select2 với tìm kiếm
                function initSelect2(selector, placeholder) {
                    const $el = $(selector);
                    if ($.fn.niceSelect) $el.niceSelect('destroy'); // Hủy nice-select
                    $el.select2({
                        placeholder: placeholder,
                        allowClear: false,
                        width: '100%',
                        language: {
                            noResults: function () {
                                return "Không tìm thấy kết quả";
                            }
                        }
                    });
                }

                initSelect2('#province', 'Chọn Tỉnh / Thành phố');
                initSelect2('#ward', 'Chọn Phường / Xã');

                // 1. Tải danh sách tỉnh/thành phố
                $.getJSON(`${API_BASE}/p/`, function (data) {
                    let html = '<option value=""></option>';
                    data.forEach(p => {
                        html += `<option value="${p.code}" data-name="${p.name}">${p.name}</option>`;
                    });
                    $('#province').html(html).trigger('change.select2');
                });

                // 2. Khi chọn tỉnh/thành phố -> Tải TẤT CẢ phường/xã của tỉnh đó
                $('#province').on('change', function () {
                    const pCode = $(this).val();
                    const pName = $(this).find(':selected').data('name');
                    $('#province_name').val(pName);

                    if (!pCode) {
                        $('#ward').html('<option value=""></option>').prop('disabled', true).trigger('change.select2');
                        return;
                    }

                    $('#ward').prop('disabled', true).html('<option value="">Đang tải...</option>').trigger('change.select2');

                    // Lấy toàn bộ quận huyện và phường xã của tỉnh (depth=3)
                    // Lưu ý: Dùng API v1 vì v2 giới hạn depth=2
                    $.getJSON(`${API_BASE}/p/${pCode}?depth=3`, function (data) {
                        let html = '<option value=""></option>';

                        // Duyệt qua từng quận/huyện
                        if (data.districts) {
                            data.districts.forEach(d => {
                                // Duyệt qua từng phường/xã trong quận đó
                                if (d.wards) {
                                    d.wards.forEach(w => {
                                        // Hiển thị kèm tên quận để người dùng dễ nhận biết
                                        html += `<option value="${w.code}" data-name="${w.name}" data-district="${d.name}">${w.name} (${d.name})</option>`;
                                    });
                                }
                            });
                        }

                        $('#ward').prop('disabled', false).html(html).trigger('change.select2');
                    });
                });

                // 3. Khi chọn phường/xã -> Lưu tên và kèm theo tên quận vào district_name (để backup)
                $('#ward').on('change', function () {
                    const selected = $(this).find(':selected');
                    const wName = selected.data('name');
                    const dName = selected.data('district');
                    $('#ward_name').val(wName);

                    // Tạo một input ẩn cho district_name nếu cần, hoặc gán vào ward_name luôn
                    if ($('#district_name').length === 0) {
                        $('<input>').attr({
                            type: 'hidden',
                            id: 'district_name',
                            name: 'district_name'
                        }).appendTo('#checkout-form');
                    }
                    $('#district_name').val(dName);
                });
            });
        </script>
    @endpush

    <style>
        .rts-custom-select {
            height: 50px !important;
            border: 1px solid #eeeeee !important;
            background: #fff !important;
            padding-left: 20px !important;
            border-radius: 4px !important;
            color: #777 !important;
            width: 100% !important;
            outline: none !important;
            display: block;
        }

        .pointer {
            cursor: pointer;
        }

        .single-shop-list {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px dashed #eee;
        }

        .single-shop-list:last-child {
            border-bottom: none;
        }

        .single-shop-list .left-area {
            display: flex;
            align-items: center;
        }

        .single-shop-list .left-area .thumbnail img {
            width: 65px;
            height: 65px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #f0f0f0;
        }

        .rts-billing-details-area .title {
            font-size: 24px;
            font-weight: 700;
            color: #2C3C28;
            margin-bottom: 30px;
        }

        .single-input label {
            font-weight: 600;
            margin-bottom: 10px;
            display: block;
            color: #2C3C28;
        }

        .single-input input,
        .single-input textarea {
            border: 1px solid #eeeeee !important;
            padding: 15px 20px !important;
            border-radius: 6px !important;
        }

        .single-input textarea {
            height: 120px !important;
        }
    </style>
@endsection