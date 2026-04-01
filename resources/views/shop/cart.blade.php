@extends('layouts.app')

@section('title', 'Giỏ hàng')

@section('content')
<!-- breadcrumb -->
<div class="rts-navigation-area-breadcrumb bg_light-1">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="navigator-breadcrumb-wrapper">
                    <a href="{{ route('home') }}">Trang chủ</a>
                    <i class="fa-regular fa-chevron-right"></i>
                    <a class="current" href="#">Giỏ hàng</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- rts cart area start -->
<div class="rts-cart-area rts-section-gap bg_light-1">
    <div class="container">
        @if(empty($cart))
        <div class="row">
            <div class="col-12 text-center py-5">
                <i class="fa-regular fa-cart-shopping" style="font-size:60px;color:#ccc;"></i>
                <h4 class="mt--20">Giỏ hàng của bạn đang trống</h4>
                <a href="{{ route('shop.index') }}" class="rts-btn btn-primary mt--20">Tiếp tục mua sắm</a>
            </div>
        </div>
        @else
        <div class="row g-5">
            <!-- Left: cart list -->
            <div class="col-xl-9 col-lg-12 col-md-12 col-12 order-2 order-xl-1 order-lg-2 order-md-2 order-sm-2">
                @php
                    $freeShippingThreshold = 500000;
                    $remaining = max(0, $freeShippingThreshold - $total);
                    $progress = min(100, ($total / $freeShippingThreshold) * 100);
                @endphp
                <div class="cart-area-main-wrapper">
                    <div class="cart-top-area-note">
                        @if($remaining > 0)
                            <p>Mua thêm <span>{{ number_format($remaining, 0, ',', '.') }}đ</span> để được miễn phí vận chuyển</p>
                        @else
                            <p>Bạn đã đủ điều kiện <span>miễn phí vận chuyển</span></p>
                        @endif
                        <div class="bottom-content-deals mt--10">
                            <div class="single-progress-area-incard">
                                <div class="progress">
                                    <div class="progress-bar wow fadeInLeft" role="progressbar" style="width: {{ $progress }}%" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rts-cart-list-area">
                    <div class="single-cart-area-list head">
                        <div class="product-main"><p>Sản phẩm</p></div>
                        <div class="price"><p>Đơn giá</p></div>
                        <div class="quantity"><p>Số lượng</p></div>
                        <div class="subtotal"><p>Thành tiền</p></div>
                    </div>

                    @foreach($cart as $rowId => $item)
                    <div class="single-cart-area-list main item-parent" data-row-id="{{ $rowId }}">
                        <div class="product-main-cart">
                            <div class="close section-activation cart-remove-btn" data-row-id="{{ $rowId }}" style="cursor:pointer;">
                                <i class="fa-regular fa-x"></i>
                            </div>
                            <div class="thumbnail">
                                @php
                                    $imgSrc = $item['image']
                                        ? (str_starts_with($item['image'], 'http') ? $item['image'] : asset('storage/' . $item['image']))
                                        : asset('theme/images/shop/default.png');
                                @endphp
                                <img src="{{ $imgSrc }}" alt="{{ $item['name'] }}">
                            </div>
                            <div class="information">
                                <h6 class="title">
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
                                    <button class="button cart-qty-btn" data-row-id="{{ $rowId }}" data-action="minus">
                                        <i class="fa-regular fa-chevron-down"></i>
                                    </button>
                                    <button class="button plus cart-qty-btn" data-row-id="{{ $rowId }}" data-action="plus">
                                        +<i class="fa-regular fa-chevron-up"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="subtotal">
                            <p class="item-subtotal">{{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}đ</p>
                        </div>
                    </div>
                    @endforeach

                    <div class="bottom-cupon-code-cart-area">
                        <form action="#" id="coupon-form">
                            <input type="text" placeholder="Mã giảm giá" id="coupon-input">
                            <button type="submit" class="rts-btn btn-primary">Áp dụng</button>
                        </form>
                        <button class="rts-btn btn-primary mr--50" id="clear-cart-btn">Xóa tất cả</button>
                    </div>
                </div>
            </div>

            <!-- Right: cart totals -->
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
                                <input type="radio" id="f-option" name="selector" checked>
                                <label for="f-option">Miễn phí vận chuyển</label>
                                <div class="check"></div>
                            </li>
                            <li>
                                <input type="radio" id="s-option" name="selector">
                                <label for="s-option">Giao hàng cố định</label>
                                <div class="check"><div class="inside"></div></div>
                            </li>
                            <li>
                                <input type="radio" id="t-option" name="selector">
                                <label for="t-option">Nhận tại cửa hàng</label>
                                <div class="check"><div class="inside"></div></div>
                            </li>
                            <li>
                                <p>Phí vận chuyển sẽ được tính khi thanh toán</p>
                                <p class="bold">Tính phí vận chuyển</p>
                            </li>
                        </ul>
                    </div>
                    <div class="bottom">
                        <div class="wrapper">
                            <span>Tổng cộng</span>
                            <h6 class="price" id="cart-total">{{ number_format($total, 0, ',', '.') }}đ</h6>
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
<!-- rts cart area end -->
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    const token = $('meta[name="csrf-token"]').attr('content');

    // Remove item
    $(document).on('click', '.cart-remove-btn', function () {
        const rowId = $(this).data('row-id');
        const $row = $(this).closest('.single-cart-area-list');
        $.post('/gio-hang/xoa', { rowId, _token: token }, function () {
            $row.fadeOut(300, function () { $(this).remove(); refreshTotals(); });
            updateHeaderCount();
        });
    });

    // Qty +/-
    $(document).on('click', '.cart-qty-btn', function () {
        const rowId = $(this).data('row-id');
        const action = $(this).data('action');
        const $input = $(this).closest('.quantity-edit').find('.cart-qty-input');
        let qty = parseInt($input.val()) || 1;
        qty = action === 'plus' ? qty + 1 : Math.max(1, qty - 1);
        $input.val(qty);
        updateQty(rowId, qty);
    });

    // Qty manual input
    $(document).on('change', '.cart-qty-input', function () {
        const rowId = $(this).data('row-id');
        const qty = Math.max(1, parseInt($(this).val()) || 1);
        $(this).val(qty);
        updateQty(rowId, qty);
    });

    // Clear all
    $('#clear-cart-btn').on('click', function () {
        $.post('/gio-hang/xoa-het', { _token: token }, function () {
            location.reload();
        });
    });

    function updateQty(rowId, qty) {
        $.post('/gio-hang/cap-nhat', { rowId, qty, _token: token }, function () {
            refreshTotals();
            updateHeaderCount();
        });
    }

    function refreshTotals() {
        $.get('/gio-hang/tong', function (data) {
            $('#cart-subtotal, #cart-total').text(data.total_formatted);
        });
    }

    function updateHeaderCount() {
        $.get('/gio-hang/so-luong', function (data) {
            $('.cart .number').text(data.count);
        });
    }
});
</script>
@endpush
