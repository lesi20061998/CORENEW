@extends('layouts.app')

@section('title', 'Giỏ hàng của bạn')

@section('content')
    {{-- Breadcrumb --}}
    <x-breadcrumb :items="[['label' => 'Giỏ hàng']]" />

    <div class="section-seperator bg_light-1">
        <div class="container">
            <hr class="section-seperator">
        </div>
    </div>

    <style>
        /* CSS đồng bộ tuyệt đối với theme Ekomart nhưng dùng class riêng để tránh xung đột JS */
        .single-cart-area-list.main .quantity-edit {
            width: 92px;
            display: flex;
            align-items: center;
            border: 1px solid rgba(43, 66, 38, 0.12);
            border-radius: 4px;
            padding: 2px 10px;
            justify-content: space-between;
            background: #fff;
            box-shadow: 0px 4px 17px rgba(0, 0, 0, 0.04);
        }
        .single-cart-area-list.main .quantity-edit .button-wrapper-action {
            border: 1px solid rgba(43, 66, 38, 0.12);
            border-radius: 2px;
            background: #fff;
            display: flex;
        }
        .single-cart-area-list.main .quantity-edit input.cart-qty-input {
            padding: 0;
            max-width: 30px !important; /* Nới rộng một chút để hiện đủ 2-3 chữ số */
            font-weight: 600;
            border: none !important;
            background: transparent !important;
            text-align: center;
        }
        .qty-btn-fix {
            padding: 0;
            max-width: max-content;
            font-size: 0;
            border: none;
            background: none;
            cursor: pointer;
        }
        .qty-btn-fix i {
            font-size: 10px;
            padding: 4px 6px;
            transition: 0.3s;
            display: block;
            color: #2C3C28;
        }
        .qty-btn-fix:first-child i {
            border-right: 1px solid rgba(43, 66, 38, 0.12);
        }
        .qty-btn-fix:hover i {
            background: var(--color-primary);
            color: #fff;
        }
        .d-none { display: none !important; }
    </style>

    <div class="rts-cart-area rts-section-gap bg_light-1">
        <div class="container">
            @if(empty($cart))
                <div class="row justify-content-center">
                    <div class="col-lg-6 text-center py-5">
                        <div class="empty-cart-wrapper p-5 bg-white rounded shadow-sm">
                            <i class="fa-light fa-cart-shopping-low-capacity mb-4" style="font-size: 80px; color: #ddd;"></i>
                            <h3 class="mb-3">Giỏ hàng đang trống</h3>
                            <p class="text-muted mb-4">Bạn chưa chọn được sản phẩm nào ưng ý. Hãy tiếp tục mua sắm nhé!</p>
                            <a href="{{ route('shop.index') }}" class="rts-btn btn-primary">Quay lại cửa hàng</a>
                        </div>
                    </div>
                </div>
            @else
                <div class="row g-5">
                    <div class="col-xl-9 col-lg-12 col-md-12 col-12 order-2 order-xl-1">
                        <div class="cart-area-main-wrapper">
                            @php
                                $threshold = 500000;
                                $remaining = max(0, $threshold - $subtotal);
                                $progress = min(100, ($subtotal / $threshold) * 100);
                            @endphp
                            <div class="cart-top-area-note">
                                <p id="shipping-tracker-text">
                                    @if($remaining > 0)
                                        Mua thêm <span>{{ number_format($remaining, 0, ',', '.') }}đ</span> để được miễn phí vận chuyển
                                    @else
                                        Chúc mừng! Bạn đã đủ điều kiện <span>miễn phí vận chuyển</span>
                                    @endif
                                </p>
                                <div class="bottom-content-deals mt--10">
                                    <div class="single-progress-area-incard">
                                        <div class="progress">
                                            <div class="progress-bar wow fadeInLeft" id="shipping-progress-bar" role="progressbar" style="width: {{ $progress }}%"
                                                aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100"></div>
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
                                    <p>Giá</p>
                                </div>
                                <div class="quantity">
                                    <p>Số lượng</p>
                                </div>
                                <div class="subtotal">
                                    <p>Thành tiền</p>
                                </div>
                            </div>

                            @foreach($cart as $rowId => $item)
                            <div class="single-cart-area-list main item-parent" data-row-id="{{ $rowId }}">
                                <div class="product-main-cart">
                                    <div class="close section-activation cart-remove-btn" data-row-id="{{ $rowId }}" style="cursor: pointer;">
                                        <i class="fa-regular fa-x"></i>
                                    </div>
                                    <div class="thumbnail">
                                        @php
                                            $imgSrc = $item['image'] 
                                                ? (str_starts_with($item['image'], 'http') ? $item['image'] : asset($item['image'])) 
                                                : asset('theme/images/shop/default.png');
                                        @endphp
                                        <img src="{{ $imgSrc }}" alt="{{ $item['name'] }}">
                                    </div>
                                    <div class="information">
                                        <h6 class="title mb-1">
                                            <a href="{{ route('product.show', $item['slug']) }}">{{ $item['name'] }}</a>
                                        </h6>
                                        @if(!empty($item['variant_label']))
                                            <span>{{ $item['variant_label'] }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="price">
                                    <p>{{ number_format($item['price'], 0, ',', '.') }}đ</p>
                                </div>
                                <div class="quantity">
                                    <div class="quantity-edit">
                                        <input type="text" class="input cart-qty-input" value="{{ $item['qty'] }}" data-row-id="{{ $rowId }}">
                                        <div class="button-wrapper-action">
                                            <button class="qty-btn-fix cart-qty-btn" data-row-id="{{ $rowId }}" data-action="minus"><i class="fa-regular fa-chevron-down"></i></button>
                                            <button class="qty-btn-fix plus cart-qty-btn" data-row-id="{{ $rowId }}" data-action="plus"><i class="fa-regular fa-chevron-up"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="subtotal">
                                    <p class="item-subtotal">{{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}đ</p>
                                </div>
                            </div>
                            @endforeach

                            <div class="bottom-cupon-code-cart-area">
                                <form id="apply-coupon-form" style="display: flex; gap: 10px; align-items: center;">
                                    <input type="text" id="coupon-code-input" placeholder="Mã giảm giá" style="padding: 10px 15px; border: 1px solid #ddd; border-radius: 4px; min-width: 200px;">
                                    <button type="submit" class="rts-btn btn-primary">Áp dụng</button>
                                </form>
                                <a href="javascript:void(0);" id="clear-cart-btn" class="rts-btn btn-primary">Xóa tất cả</a>
                            </div>

                            @if(!empty($validCoupons))
                            <div class="applied-coupons-list mt-4 p-3 bg-white border rounded">
                                <h6 class="mb-3" style="font-size: 14px;">Mã giảm giá đã áp dụng:</h6>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($validCoupons as $vCoupon)
                                        <div class="applied-coupon-item d-flex align-items-center gap-2" style="background: #f8f9fa; border: 1px solid #e9ecef; padding: 5px 12px; border-radius: 20px;">
                                            <span class="fw-bold text-success">{{ $vCoupon['code'] }}</span>
                                            <span class="text-muted" style="font-size: 12px;">(-{{ number_format($vCoupon['discount'], 0, ',', '.') }}đ)</span>
                                            <button type="button" class="remove-single-coupon" data-id="{{ $vCoupon['id'] }}" style="border: none; background: transparent; color: #ff4d4d; padding: 0 0 0 5px; cursor: pointer;">
                                                <i class="fa-solid fa-circle-xmark"></i>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-12 col-md-12 col-12 order-1 order-xl-2">
                        <div class="cart-total-area-start-right">
                            <h5 class="title">Tổng giỏ hàng</h5>
                            <div class="subtotal">
                                <span>Tạm tính</span>
                                <h6 class="price cart-subtotal-val">{{ number_format($subtotal, 0, ',', '.') }}đ</h6>
                            </div>
                            
                            <div id="cart-discounts-container">
                                @if(!empty($validCoupons))
                                    @foreach($validCoupons as $vCoupon)
                                    <div class="subtotal" style="border-top: 1px dashed #eee; padding-top: 10px; margin-top: 10px;">
                                        <span class="text-success">Giảm giá ({{ $vCoupon['code'] }})</span>
                                        <h6 class="price text-success">-{{ number_format($vCoupon['discount'], 0, ',', '.') }}đ</h6>
                                    </div>
                                    @endforeach
                                @endif
                            </div>
                            <div class="shipping">
                                <span>Vận chuyển</span>
                                <ul>
                                    <li>
                                        <input type="radio" id="f-option" name="shipping_selector" checked>
                                        <label for="f-option">Miễn phí vận chuyển</label>
                                        <div class="check"></div>
                                    </li>
                                    <li>
                                        <input type="radio" id="s-option" name="shipping_selector" disabled>
                                        <label for="s-option" class="opacity-50">Giao hàng đồng giá</label>
                                        <div class="check"></div>
                                    </li>
                                    <li>
                                        <p>Tùy chọn vận chuyển sẽ được cập nhật trong quá trình thanh toán.</p>
                                    </li>
                                </ul>
                            </div>
                            <div class="bottom">
                                <div class="wrapper">
                                    <span>Tổng cộng</span>
                                    <h6 class="price cart-total-val">{{ number_format($subtotal - $totalDiscount, 0, ',', '.') }}đ</h6>
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
$(document).ready(function() {
    const token = $('meta[name="csrf-token"]').attr('content');

    // Remove item
    $(document).on('click', '.cart-remove-btn', function() {
        const rowId = $(this).data('row-id');
        const $row = $(this).closest('.item-parent');

        if(confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng?')) {
            $.post('/gio-hang/xoa', { rowId, _token: token }, function(res) {
                $row.fadeOut(300, function() {
                    $(this).remove();
                    if ($('.item-parent').length === 0) location.reload();
                    refreshTotals();
                });
                updateHeaderCount();
            });
        }
    });

    // Qty update via buttons
    $(document).on('click', '.cart-qty-btn', function(e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();

        const rowId = $(this).data('row-id');
        const action = $(this).data('action');
        const $input = $(this).closest('.quantity-edit').find('.cart-qty-input');
        
        let qty = parseInt($input.val()) || 1;
        qty = action === 'plus' ? qty + 1 : Math.max(1, qty - 1);
        $input.val(qty);
        
        updateQty(rowId, qty, $(this).closest('.item-parent').find('.item-subtotal'));
    });

    // Manual Qty Input
    $(document).on('change', '.cart-qty-input', function() {
        const rowId = $(this).data('row-id');
        let qty = Math.max(1, parseInt($(this).val()) || 1);
        $(this).val(qty);
        updateQty(rowId, qty, $(this).closest('.item-parent').find('.item-subtotal'));
    });

    // Clear All
    $('#clear-cart-btn').on('click', function() {
        if(confirm('Bạn có chắc chắn muốn làm trống giỏ hàng?')) {
            $.post('/gio-hang/xoa-het', { _token: token }, function() {
                location.reload();
            });
        }
    });

    function updateQty(rowId, qty, $subtotalEl) {
        $.post('/gio-hang/cap-nhat', { rowId, qty, _token: token }, function(res) {
            if (res && res.item_subtotal_formatted) {
                $subtotalEl.text(res.item_subtotal_formatted);
            }
            refreshTotals();
            updateHeaderCount();
        });
    }

    function refreshTotals() {
        $.get('/gio-hang/tong', function(data) {
            $('.cart-subtotal-val').text(data.subtotal_formatted);
            $('.cart-total-val').text(data.total_formatted);

            // Rebuild coupon rows in summary
            let $container = $('#cart-discounts-container');
            $container.empty();
            if (data.coupons && data.coupons.length > 0) {
                data.coupons.forEach(function(c) {
                    $container.append(`
                        <div class="subtotal" style="border-top: 1px dashed #eee; padding-top: 10px; margin-top: 10px;">
                            <span class="text-success">Giảm giá (${c.code})</span>
                            <h6 class="price text-success">${c.discount_formatted}</h6>
                        </div>
                    `);
                });
            }

            // Update Shipping Tracker using subtotal (before discount)
            const total = data.subtotal;
            const threshold = 500000;
            const remaining = Math.max(0, threshold - total);
            const progress = Math.min(100, (total / threshold) * 100);

            $('#shipping-progress-bar').css('width', progress + '%');
            
            if(remaining > 0) {
                let formattedRemaining = new Intl.NumberFormat('vi-VN').format(remaining) + 'đ';
                $('#shipping-tracker-text').html(`Mua thêm <span>${formattedRemaining}</span> để được miễn phí vận chuyển`);
            } else {
                $('#shipping-tracker-text').html(`Chúc mừng! Bạn đã đủ điều kiện <span>miễn phí vận chuyển</span>`);
            }
        });
    }

    // Apply Coupon
    $('#apply-coupon-form').on('submit', function(e) {
        e.preventDefault();
        const code = $('#coupon-code-input').val();
        if (!code) return alert('Vui lòng nhập mã giảm giá');

        $.post('/gio-hang/apply-coupon', { code, _token: token }, function(res) {
            if (res.success) {
                alert(res.message);
                location.reload(); // Reload to refresh the UI state (input disabled, button change)
            } else {
                alert(res.message);
            }
        });
    });

    // Remove Single Coupon
    $(document).on('click', '.remove-single-coupon', function() {
        const couponId = $(this).data('id');
        $.post('/gio-hang/remove-coupon', { coupon_id: couponId, _token: token }, function(res) {
            if (res.success) {
                location.reload();
            }
        });
    });

    function updateHeaderCount() {
        $.get('/gio-hang/so-luong', function(data) {
            $('.cart .number').text(data.count);
        });
    }
});
</script>
@endpush