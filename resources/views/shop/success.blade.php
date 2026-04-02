@extends('layouts.app')

@section('title', 'Đặt hàng thành công')

@section('content')
<div class="rts-navigation-area-breadcrumb">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="navigator-breadcrumb-wrapper">
                    <a href="{{ route('home') }}">Trang chủ</a>
                    <i class="fa-regular fa-chevron-right"></i>
                    <a class="current" href="#">Hoàn tất đặt hàng</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="rts-checkout-payment-area rts-section-gap">
    <div class="container">
        <div class="row g-5 justify-content-center">
            <div class="col-lg-8 text-center">
                <div class="success-icon mb--30">
                    <i class="fa-solid fa-circle-check text-success" style="font-size: 80px;"></i>
                </div>
                <h2 class="title font-bold mb--10">Đặt hàng thành công!</h2>
                <p class="mb--30">Cảm ơn bạn đã tin tưởng mua sắm tại VietTinMart. Mã đơn hàng của bạn là: <strong class="text-primary">#{{ $order->order_number }}</strong></p>
                
                <div class="order-summary-card p-5 border rounded-3xl bg-light text-start shadow-sm mb--40">
                    <h4 class="font-bold border-bottom pb--15 mb--20">Thông tin đơn hàng</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb--5"><strong>Người nhận:</strong> {{ $order->customer_name }}</p>
                            <p class="mb--5"><strong>Điện thoại:</strong> {{ $order->customer_phone }}</p>
                            <p class="mb--5"><strong>Địa chỉ:</strong> {{ $order->shipping_address }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb--5"><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                            <p class="mb--5"><strong>Phương thức:</strong> {{ strtoupper($order->payment_method) }}</p>
                            <p class="mb--5"><strong>Tổng thanh toán:</strong> <span class="text-primary font-black">{{ number_format($order->total, 0, ',', '.') }}₫</span></p>
                        </div>
                    </div>
                </div>

                <div class="action-buttons d-flex gap-3 justify-content-center">
                    <a href="{{ route('shop.index') }}" class="rts-btn btn-primary px-5 py-3 rounded-pill">Tiếp tục mua sắm</a>
                    <a href="{{ route('profile') . '?tab=orders' }}" class="rts-btn btn-outline border-only px-5 py-3 rounded-pill">Xem đơn hàng</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
