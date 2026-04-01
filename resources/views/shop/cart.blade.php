@extends('layouts.app')

@section('title', 'Giỏ hàng của bạn')

@section('content')
    {{-- Breadcrumb --}}
    <div class="rts-navigation-area-breadcrumb bg-slate-50/50 border-b border-slate-100">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="flex items-center gap-3 py-4 text-xs font-bold uppercase tracking-widest">
                        <a href="{{ route('home') }}" class="text-slate-400 hover:text-blue-600 transition-colors">Trang chủ</a>
                        <i class="fa-regular fa-chevron-right text-[10px] text-slate-300"></i>
                        <span class="text-slate-800">Giỏ hàng</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                                $remaining = max(0, $threshold - $total);
                                $progress = min(100, ($total / $threshold) * 100);
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
                        <div class="rts-cart-list-area shadow-sm bg-white rounded">
                            <div class="single-cart-area-list head bg-light">
                                <div class="product-main">
                                    <p>Sản phẩm</p>
                                </div>
                                <div class="price text-center">
                                    <p>Giá</p>
                                </div>
                                <div class="quantity text-center">
                                    <p>Số lượng</p>
                                </div>
                                <div class="subtotal text-center">
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
                                        <img src="{{ $imgSrc }}" alt="{{ $item['name'] }}" style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px;">
                                    </div>
                                    <div class="information">
                                        <h6 class="title mb-1">
                                            <a href="{{ route('product.show', $item['slug']) }}" class="text-dark">{{ $item['name'] }}</a>
                                        </h6>
                                        @if(!empty($item['variant_label']))
                                            <span class="badge bg-light text-muted border fw-normal">{{ $item['variant_label'] }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="price text-center">
                                    <p>{{ number_format($item['price'], 0, ',', '.') }}đ</p>
                                </div>
                                <div class="quantity">
                                    <div class="quantity-edit">
                                        <input type="text" class="input cart-qty-input" value="{{ $item['qty'] }}" data-row-id="{{ $rowId }}">
                                        <div class="button-wrapper-action">
                                            <button class="button cart-qty-btn" data-row-id="{{ $rowId }}" data-action="minus"><i class="fa-regular fa-chevron-down"></i></button>
                                            <button class="button plus cart-qty-btn" data-row-id="{{ $rowId }}" data-action="plus"><i class="fa-regular fa-chevron-up"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="subtotal text-center">
                                    <p class="item-subtotal font-weight-bold" style="color: #0b5a96;">{{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}đ</p>
                                </div>
                            </div>
                            @endforeach

                            <div class="bottom-cupon-code-cart-area bg-light p-4">
                                <form action="#" class="d-flex gap-2">
                                    <input type="text" placeholder="Mã giảm giá" class="form-control" style="max-width: 200px; border-radius: 4px;">
                                    <button class="rts-btn btn-primary px-4">Áp dụng</button>
                                </form>
                                <button type="button" id="clear-cart-btn" class="rts-btn btn-outline border px-4">Xóa tất cả</button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xl-3 col-lg-12 col-md-12 col-12 order-1 order-xl-2">
                        <div class="cart-total-area-start-right shadow-sm p-4 bg-white rounded">
                            <h5 class="title mb-4 border-bottom pb-2">Tổng giỏ hàng</h5>
                            <div class="subtotal d-flex justify-content-between mb-3">
                                <span>Tạm tính</span>
                                <h6 class="price cart-subtotal-val">{{ number_format($total, 0, ',', '.') }}đ</h6>
                            </div>
                            <div class="shipping mb-4 pb-3 border-bottom">
                                <span class="d-block mb-3 fw-bold">Vận chuyển</span>
                                <ul class="list-unstyled">
                                    <li class="mb-2 d-flex align-items-center">
                                        <input type="radio" id="f-option" name="shipping_selector" checked>
                                        <label for="f-option" class="ms-2 mb-0">Miễn phí vận chuyển</label>
                                    </li>
                                    <li class="mb-2 d-flex align-items-center opacity-50">
                                        <input type="radio" id="s-option" name="shipping_selector" disabled>
                                        <label for="s-option" class="ms-2 mb-0">Giao hàng đồng giá</label>
                                    </li>
                                    <li class="mb-2">
                                        <p class="text-muted small">Tùy chọn vận chuyển sẽ được cập nhật trong quá trình thanh toán.</p>
                                    </li>
                                </ul>
                            </div>
                            <div class="bottom">
                                <div class="wrapper d-flex justify-content-between mb-4">
                                    <span class="fw-bold">Tổng cộng</span>
                                    <h6 class="price cart-total-val" style="color: #0b5a96; font-size: 24px;">{{ number_format($total, 0, ',', '.') }}đ</h6>
                                </div>
                                <div class="button-area">
                                    <a href="{{ route('checkout.index') }}" class="rts-btn btn-primary w-100 text-center py-3">Tiến hành thanh toán</a>
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
    $(document).on('click', '.cart-qty-btn', function() {
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
            if (res && res.item_subtotal) {
                $subtotalEl.text(res.item_subtotal);
            }
            refreshTotals();
            updateHeaderCount();
        });
    }

    function refreshTotals() {
        $.get('/gio-hang/tong', function(data) {
            $('.cart-subtotal-val, .cart-total-val').text(data.total_formatted);

            // Update Shipping Tracker
            const total = data.total;
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

    function updateHeaderCount() {
        $.get('/gio-hang/so-luong', function(data) {
            $('.cart .number').text(data.count);
        });
    }
});
</script>
@endpush