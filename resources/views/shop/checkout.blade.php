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


            @if(session('error'))
                <div class="alert alert-danger mb--30 p-4 rounded-3 border-0 shadow-sm" style="background-color: #ffe5e5; color: #d32f2f;">
                    <i class="fa-solid fa-circle-exclamation me-2"></i> {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form" novalidate>
                @csrf
                <div class="row">
                    
                    <div class="col-lg-8 pr--40 pr_md--5 pr_sm--5 order-2 order-xl-1 order-lg-2 order-md-2 order-sm-2 mt_md--30 mt_sm--30">
                     
                            @auth
                                @if(auth()->user()->addresses->count() > 0)
                                    <div class="saved-addresses-wrapper mb-4">
                                        <label class="mb-3">Chọn từ địa chỉ đã lưu</label>
                                        <div class="row g-3">
                                            @foreach(auth()->user()->addresses->sortByDesc('is_default') as $addr)
                                                <div class="col-md-6">
                                                    <div class="address-card p-3 border rounded pointer {{ $addr->is_default ? 'selected border-primary bg_light-2' : '' }}" 
                                                         data-id="{{ $addr->id }}"
                                                         data-receiver-name="{{ $addr->receiver_name }}"
                                                         data-receiver-phone="{{ $addr->receiver_phone }}"
                                                         data-province="{{ $addr->province_code }}"
                                                         data-ward="{{ $addr->ward_code }}"
                                                         data-detail="{{ $addr->address_detail }}">
                                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                                            <div class="d-flex align-items-center">
                                                                <div class="selection-indicator me-3">
                                                                    <i class="fa-regular {{ $addr->is_default ? 'fa-circle-dot text-primary' : 'fa-circle text-muted' }}"></i>
                                                                </div>
                                                                <h4 class="h6 mb-0">{{ $addr->receiver_name }}</h4>
                                                            </div>
                                                            @if($addr->is_default)
                                                                <span class="badge bg-primary px-1 py-0" style="font-size: 0.6rem;">Mặc định</span>
                                                            @endif
                                                        </div>
                                                        <div class="ms--30">
                                                            <p class="small text-muted mb-1"><i class="fa-solid fa-phone me-2"></i>{{ $addr->receiver_phone }}</p>
                                                            <p class="small text-dark mb-0"><i class="fa-solid fa-location-dot me-2"></i>{{ Str::limit($addr->full_address, 70) }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                            <div class="col-md-6">
                                                <div class="address-card p-3 border rounded pointer d-flex align-items-center h-100" data-id="new">
                                                    <div class="selection-indicator me-3">
                                                        <i class="fa-regular fa-circle text-muted"></i>
                                                    </div>
                                                    <span class="small fw-bold text-primary"><i class="fa-solid fa-plus me-2"></i>Sử dụng địa chỉ khác</span>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="saved_address_id" id="saved_address_id" value="{{ auth()->user()->addresses->where('is_default', true)->first()?->id }}">
                                    </div>
                                @endif
                            @endauth       
                    <div class="rts-billing-details-area">
                        @php
                            $defaultAddr = auth()->user()?->addresses->where('is_default', true)->first();
                            $defaultName = $defaultAddr ? $defaultAddr->receiver_name : (auth()->user()?->name ?? '');
                            $defaultPhone = $defaultAddr ? $defaultAddr->receiver_phone : (auth()->user()?->phone ?? '');
                            
                            $nameParts = explode(' ', trim($defaultName));
                            $lastName = count($nameParts) > 1 ? array_pop($nameParts) : '';
                            $firstName = implode(' ', $nameParts) ?: $defaultName;
                        @endphp
                        
                        <h3 class="title animated fadeIn">Thông tin thanh toán</h3>

                        <div class="half-input-wrapper">
                            <x-form-input name="first_name" id="f-name" label="Họ và tên đệm" placeholder="Nguyễn Văn" required 
                                         value="{{ old('first_name', $firstName) }}" />
                            <x-form-input name="last_name" id="l-name" label="Tên" placeholder="A" required 
                                         value="{{ old('last_name', $lastName) }}" />
                        </div>

                        <div class="half-input-wrapper">
                            <x-form-input name="email" id="email" type="email" label="Địa chỉ Email" placeholder="example@gmail.com" required 
                                         value="{{ old('email', auth()->user()?->email) }}" />
                            <x-form-input name="phone" id="phone" label="Số điện thoại" placeholder="0123 456 789" required 
                                         value="{{ old('phone', $defaultPhone) }}" />
                        </div>


                            <style>
                                .address-card {
                                    transition: all 0.2s;
                                    min-height: 100px;
                                }
                                .address-card:hover {
                                    border-color: var(--color-primary) !important;
                                }
                                .address-card.selected {
                                    border-color: var(--color-primary) !important;
                                    border-width: 2px !important;
                                    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
                                }
                                .pointer { cursor: pointer; }
                            </style>

                            <div id="address_fields_wrapper">
                                <x-address-selector 
                                    id="checkout-addr"
                                    :selected-province="auth()->user()?->province_code"
                                    :selected-ward="auth()->user()?->ward_code"
                                />

                                <x-form-input name="street_address" id="street" label="Địa Chỉ Chi Tiết (Số nhà, tên đường...)" placeholder="Ví dụ: 123 Đường Nguyễn Trãi..." required 
                                             value="{{ old('street_address', auth()->user()?->address_detail) }}" />
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
                        <div class="right-card-sidebar-checkout p-4 border rounded-3 bg-white shadow-sm">
                            
                            <!-- Voucher Section -->
                            <div class="voucher-selection-area mb--30">
                                <div class="d-flex align-items-center justify-content-between mb--15">
                                    <h4 class="h6 mb-0 text-uppercase fw-bold" style="letter-spacing: 0.5px; color: #2C3C28;">Mã khuyến mãi</h4>
                                    <span class="text-primary small pointer fw-bold" data-bs-toggle="modal" data-bs-target="#voucherModal">Xem thêm</span>
                                </div>
                                
                                <div class="input-group mb-3">
                                    <input type="text" id="coupon_code_input" class="form-control" placeholder="Nhập mã voucher của Shop" 
                                           style="height: 45px; border-radius: 4px 0 0 4px; border-right: none;">
                                    <button class="btn btn-primary px-4 fw-bold apply-coupon-btn" type="button" 
                                            style="height: 45px; border-radius: 0 4px 4px 0; font-size: 13px;">ÁP DỤNG</button>
                                </div>

                                <div class="selected-vouchers-list">
                                    @php
                                        $appliedCodes = array_column($couponList, 'code');
                                    @endphp
                                    @foreach($availableCoupons->whereIn('code', $appliedCodes) as $coupon)
                                        <div class="voucher-card d-flex mb-3 align-items-stretch position-relative" 
                                             style="height: 90px; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.05)); border: 1px solid var(--color-primary); border-radius: 4px;">
                                            <!-- Left side (Logo/Icon) -->
                                            <div class="voucher-left bg-primary d-flex flex-column align-items-center justify-content-center text-white px-2 py-1 position-relative" 
                                                 style="width: 80px; border-radius: 3px 0 0 3px;">
                                                <img src="{{ asset('theme/images/logo/logo-small.png') }}" alt="VTM" style="width: 30px; margin-bottom: 2px; filter: brightness(0) invert(1);">
                                                <span style="font-size: 8px; font-weight: 700;">VietTinMart</span>
                                            </div>
                                            <!-- Right side (Content) -->
                                            <div class="voucher-right flex-grow-1 bg-white p-2 d-flex flex-column justify-content-center position-relative" 
                                                 style="border-radius: 0 4px 4px 0;">
                                                <div class="info">
                                                    <h5 class="mb-0 fw-bold text-primary" style="font-size: 13px;">Đã áp dụng mã: {{ $coupon->code }}</h5>
                                                    <p class="mb-0 text-muted" style="font-size: 10px;">Giảm {{ $coupon->type === 'fixed' ? number_format($coupon->value, 0, ',', '.') . 'đ' : $coupon->value . '%' }}</p>
                                                </div>
                                                <div class="action-btn position-absolute top-0 end-0 p-2">
                                                    <button type="button" class="btn-close remove-coupon-btn" data-id="{{ $coupon->id }}" style="font-size: 8px;"></button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                    @if(count($appliedCodes) == 0)
                                        <div class="text-center py-3 border rounded-3 dashed mb-3" style="border: 1px dashed #ddd;">
                                            <p class="small text-muted mb-0">Chưa có mã giảm giá nào được áp dụng</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="top-wrapper border-bottom pb--15 mb--15">
                                <div class="product fw-bold text-uppercase" style="font-size: 13px; color: #777;">Sản phẩm</div>
                                <div class="price fw-bold text-uppercase" style="font-size: 13px; color: #777;">Tổng cộng</div>
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
                                                <span class="qty" style="font-size: 12px; color: #777;">Số lượng: {{ $item['qty'] }}</span>
                                            </div>
                                        </div>
                                        <span class="price" style="font-weight: 500; color: #2C3C28;">{{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}đ</span>
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

                let fullProvincesData = [];

                // 1. Tải toàn bộ dữ liệu từ file local để tránh request liên tục
                $.getJSON('{{ asset("data/provinces.json") }}', function (data) {
                    fullProvincesData = data;
                    let html = '<option value=""></option>';
                    data.forEach(p => {
                        html += `<option value="${p.code}" data-name="${p.name}">${p.name}</option>`;
                    });
                    $('#province').html(html).trigger('change.select2');
                }).fail(function() {
                    console.error("Không thể tải dữ liệu tỉnh thành từ file local.");
                });

                // 2. Khi chọn tỉnh/thành phố -> Lọc trực tiếp từ biến local (không gọi API nữa)
                $('#province').on('change', function () {
                    const pCode = $(this).val();
                    const pName = $(this).find(':selected').data('name');
                    $('#province_name').val(pName);

                    if (!pCode) {
                        $('#ward').html('<option value=""></option>').prop('disabled', true).trigger('change.select2');
                        return;
                    }

                    // Tìm tỉnh tương ứng trong dữ liệu đã tải
                    const province = fullProvincesData.find(p => p.code == pCode);
                    let html = '<option value=""></option>';

                    if (province && province.districts) {
                        province.districts.forEach(d => {
                            if (d.wards) {
                                d.wards.forEach(w => {
                                    // Hiển thị kèm tên quận huyện để người dùng dễ nhận biết
                                    html += `<option value="${w.code}" data-name="${w.name}" data-district="${d.name}">${w.name} (${d.name})</option>`;
                                });
                            }
                        });
                    }

                    $('#ward').prop('disabled', false).html(html).trigger('change.select2');
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

                // 4. Form Validation & Loading State
                $('#checkout-form').on('submit', function (e) {
                    const $form = $(this);
                    const $btn = $form.find('button[type="submit"]');
                // Saved Address Card Selection Handler
                const handleAddressCardSelection = function($card) {
                    $('.address-card').removeClass('selected border-primary bg_light-2 text-primary');
                    $('.address-card .selection-indicator i').removeClass('fa-circle-dot text-primary').addClass('fa-circle text-muted');
                    
                    $card.addClass('selected border-primary bg_light-2 text-primary');
                    $card.find('.selection-indicator i').removeClass('fa-circle text-muted').addClass('fa-circle-dot text-primary');
                    
                    const val = $card.data('id');
                    $('#saved_address_id').val(val);
                    const $wrapper = $('#address_fields_wrapper');
                    
                    if (val === 'new') {
                        // Clear fields for new entry
                        $('#f-name, #l-name, #phone, #street').val('');
                        $('#province-checkout-addr').val('').trigger('change');
                        // Email usually stays as user's email but can be changed
                        $wrapper.slideDown();
                    } else {
                        const name = $card.data('receiver-name') || '';
                        const phone = $card.data('receiver-phone') || '';
                        const pCode = $card.data('province');
                        const wCode = $card.data('ward');
                        const detail = $card.data('detail');

                        // Split name precisely
                        const nameParts = name.trim().split(/\s+/);
                        let firstName = '';
                        let lastName = '';
                        
                        if (nameParts.length > 1) {
                            lastName = nameParts.pop();
                            firstName = nameParts.join(' ');
                        } else {
                            firstName = name;
                            lastName = '';
                        }

                        $('#f-name').val(firstName);
                        $('#l-name').val(lastName);
                        $('#phone').val(phone);
                        $('#street').val(detail);

                        // Reset email to default user email if it was changed
                        @auth
                            $('#email').val('{{ auth()->user()->email }}');
                        @endauth

                        const $pSelect = $('#province-checkout-addr');
                        const $wSelect = $('#ward-checkout-addr');

                        $pSelect.val(pCode).trigger('change');

                        const checkWards = setInterval(() => {
                            if ($wSelect.find(`option[value="${wCode}"]`).length > 0) {
                                $wSelect.val(wCode).trigger('change');
                                clearInterval(checkWards);
                            }
                        }, 100);
                        setTimeout(() => clearInterval(checkWards), 3000);

                        $wrapper.slideDown();
                    }
                };

                $(document).on('click', '.address-card', function() {
                    handleAddressCardSelection($(this));
                });

                // Initial Load for default address card
                const $defaultCard = $('.address-card.selected');
                if ($defaultCard.length) {
                    handleAddressCardSelection($defaultCard);
                }

                // Form Validation & Loading State
                $('#checkout-form').on('submit', function (e) {
                    const $form = $(this);
                    const $btn = $form.find('button[type="submit"]');

                    let isValid = true;

                    // Reset previous styles and error messages
                    $('.error-msg').text('');
                    $('.form-control').css('border-color', '#eeeeee');

                    function setError(id, msg) {
                        $(`#error-${id}`).text(msg);
                        $(`#${id}`).addClass('border-danger');
                        $(`#${id}`).css('border-color', '#d32f2f');
                        isValid = false;
                    }

                    // Check products stock first
                    const hasInsufficientStock = $('.cart-items-list .text-danger').length > 0;
                    if (hasInsufficientStock) {
                        alert("Thay đổi số lượng giỏ hàng vì một số sản phẩm đã hết hàng hoặc không đủ tồn kho.");
                        return false;
                    }

                    // Validate Fields
                    if (!$('#f-name').val().trim()) setError('f-name', "Vui lòng nhập Họ.");
                    if (!$('#l-name').val().trim()) setError('l-name', "Vui lòng nhập Tên.");
                    
                    const email = $('#email').val().trim();
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!email) setError('email', "Vui lòng nhập Địa chỉ Email.");
                    else if (!emailRegex.test(email)) setError('email', "Địa chỉ Email không hợp lệ.");

                    const phone = $('#phone').val().trim();
                    if (!phone) setError('phone', "Vui lòng nhập Số điện thoại.");

                    // Address Validation
                    if (!$('#province-checkout-addr').val()) setError('province-checkout-addr', "Vui lòng chọn Tỉnh / Thành phố.");
                    if (!$('#ward-checkout-addr').val()) setError('ward-checkout-addr', "Vui lòng chọn Phường / Xã.");
                    if (!$('#street').val().trim()) setError('street', "Vui lòng nhập Địa chỉ chi tiết.");
                    
                    if (!$('#agree').is(':checked')) {
                        alert("Bạn phải đồng ý với Điều khoản & Chính sách.");
                        isValid = false;
                    }

                    if (!isValid) {
                        e.preventDefault();
                        // Scroll to the first error
                        const firstError = $('.error-msg:not(:empty)').first().parent();
                        if (firstError.length) {
                             $('html, body').animate({
                                scrollTop: firstError.offset().top - 150
                            }, 500);
                        }
                        return false;
                    }

                    // Form is valid - Show loading
                    $btn.prop('disabled', true)
                        .addClass('opacity-50')
                        .html('<i class="fa-solid fa-spinner fa-spin me-2"></i> ĐANG XỬ LÝ...');
                    
                    return true;
                });

                // Voucher Selection & Application
                $('.apply-coupon-btn').on('click', function() {
                    const code = $('#coupon_code_input').val().trim();
                    if (!code) {
                        alert("Vui lòng nhập mã giảm giá.");
                        return;
                    }
                    applyCouponCode(code);
                });

                $(document).on('click', '.select-voucher-btn', function() {
                    const code = $(this).data('code');
                    applyCouponCode(code);
                });

                function applyCouponCode(code) {
                    const $btn = $('.apply-coupon-btn');
                    const originalText = $btn.text();
                    
                    $btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i>');

                    $.ajax({
                        url: '{{ route("cart.apply-coupon") }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            code: code
                        },
                        success: function(res) {
                            if (res.success) {
                                // Reload to update all logic simply OR update summary via JS
                                // For better UX, let's reload the page to refresh availableCoupons logic in PHP
                                location.reload();
                            } else {
                                alert(res.message || "Không thể áp dụng mã giảm giá này.");
                            }
                        },
                        error: function() {
                            alert("Đã có lỗi xảy ra. Vui lòng thử lại.");
                        },
                        complete: function() {
                            $btn.prop('disabled', false).text(originalText);
                        }
                    });
                }

                $(document).on('click', '.remove-coupon-btn', function() {
                    const id = $(this).data('id');
                    $.post('{{ route("cart.remove-coupon") }}', {
                        _token: '{{ csrf_token() }}',
                        coupon_id: id
                    }, function() {
                        location.reload();
                    });
                });

                // Clear errors on input
                $('.form-control, select:not(.rts-custom-select)').on('input change', function() {
                    const id = $(this).attr('id');
                    $(`#error-${id}`).text('');
                    $(this).css('border-color', '#eeeeee');
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

    <!-- Voucher Modal -->
    <div class="modal fade" id="voucherModal" tabindex="-1" aria-labelledby="voucherModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 overflow-hidden" style="border-radius: 12px; background: #f8f9fa;">
                <div class="modal-header border-bottom-0 p-4 pb-0">
                    <h5 class="modal-title fw-bold" id="voucherModalLabel" style="color: #2C3C28;">Kho Voucher</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4" style="max-height: 70vh; overflow-y: auto;">
                    <div class="row g-4">
                        @foreach($availableCoupons as $coupon)
                            @php
                                $invalidReason = $coupon->getInvalidReason($subtotal);
                                $isApplied = in_array($coupon->code, array_column($couponList, 'code'));
                            @endphp
                            <div class="col-md-6">
                                <div class="voucher-card d-flex align-items-stretch position-relative {{ $invalidReason ? 'opacity-75' : '' }}" 
                                     style="height: 110px; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.05)); border-radius: 6px; overflow: hidden; border: 1px solid {{ $isApplied ? 'var(--color-primary)' : '#fff' }};">
                                    <div class="voucher-left bg-primary d-flex flex-column align-items-center justify-content-center text-white px-3" style="width: 100px;">
                                        <img src="{{ asset('theme/images/logo/logo-small.png') }}" style="width: 40px; filter: brightness(0) invert(1);">
                                        <span style="font-size: 9px; font-weight: 700; margin-top: 5px;">VietTinMart</span>
                                    </div>
                                    <div class="voucher-right flex-grow-1 bg-white p-3 d-flex flex-column justify-content-between">
                                        <div>
                                            <h6 class="mb-0 fw-bold" style="font-size: 15px;">Giảm {{ $coupon->type === 'fixed' ? number_format($coupon->value, 0, ',', '.') . 'đ' : $coupon->value . '%' }}</h6>
                                            <p class="small text-muted mb-0" style="font-size: 11px;">Đơn tối thiểu {{ number_format($coupon->min_order_value, 0, ',', '.') }}đ</p>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="text-primary fw-bold" style="font-size: 11px;">Mã: {{ $coupon->code }}</span>
                                            @if($isApplied)
                                                <button type="button" class="btn btn-sm btn-outline-danger py-0 px-2 remove-coupon-btn" 
                                                        data-id="{{ $coupon->id }}" style="font-size: 10px; height: 26px;">Gỡ bỏ</button>
                                            @elseif($invalidReason)
                                                <span class="text-danger fw-bold" style="font-size: 10px;">{{ $invalidReason }}</span>
                                            @else
                                                <button type="button" class="btn btn-sm btn-primary py-0 px-2 select-voucher-btn" 
                                                        data-code="{{ $coupon->code }}" style="font-size: 11px; height: 26px;">Dùng ngay</button>
                                            @endif
                                        </div>
                                    </div>
                                    @if($isApplied)
                                    <div class="position-absolute top-0 end-0 bg-primary text-white px-2 py-0" style="font-size: 9px; border-radius: 0 0 0 6px;">
                                        Đã chọn
                                    </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer border-top-0 p-4 pt-0">
                    <button type="button" class="btn btn-secondary w-100 fw-bold py-3" data-bs-dismiss="modal">ĐÓNG</button>
                </div>
            </div>
        </div>
    </div>
@endsection