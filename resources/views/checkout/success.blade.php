@extends('layouts.app')

@section('title', 'Đặt hàng thành công - VietTinMart')

@section('content')
<section class="checkout-success-area section-padding-tb">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 text-center">
                <div class="success-icon mb-4">
                    <i class="fa-solid fa-circle-check text-success" style="font-size: 5rem;"></i>
                </div>
                <h2 class="mb-3">Đặt hàng thành công!</h2>
                                <p class="text-muted mb-4">Cảm ơn bạn đã mua hàng tại VietTinMart. Mã đơn hàng của bạn là:</p>
                <div class="order-number-box p-3 bg-light rounded mb-4">
                    <h4 class="text-primary mb-0">{{ $order->order_number }}</h4>
                </div>
                <p class="text-muted mb-4">Chúng tôi sẽ gửi email xác nhận đến <strong>{{ $order->customer_email }}</strong> và liên hệ với bạn sớm nhất.</p>
                <div class="d-flex gap-3 justify-content-center">
                    <a href="{{ route('home') }}" class="rts-btn btn-border">Về trang chủ</a>
                    <a href="{{ route('shop.index') }}" class="rts-btn btn-primary">Tiếp tục mua sắm</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
