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
                            <p class="mb--5"><strong>Phương thức:</strong> {{ $order->payment_method === 'bank_transfer' ? 'Chuyển khoản ngân hàng (VietQR)' : strtoupper($order->payment_method) }}</p>
                            <p class="mb--5"><strong>Tổng thanh toán:</strong> <span class="text-primary font-black">{{ number_format($order->total, 0, ',', '.') }}₫</span></p>
                        </div>
                    </div>
                </div>

                @if($order->payment_method === 'bank_transfer')
                <div class="bank-transfer-instruction mb--40 p-5 border rounded-3xl bg-white shadow-sm text-center">
                    <h4 class="font-bold mb--20">Thông tin chuyển khoản</h4>
                    
                    @php
                        $bankId = setting('vietqr_bank_id');
                        $accNo = setting('vietqr_account_no');
                        $accName = setting('vietqr_account_name');
                        $template = setting('vietqr_template', 'compact2');
                        
                        // Use only order number for description
                        $fullDesc = $order->order_number;
                        
                        $qrUrl = "https://img.vietqr.io/image/{$bankId}-{$accNo}-{$template}.png?amount={$order->total}&addInfo=" . urlencode($fullDesc) . "&accountName=" . urlencode($accName);
                    @endphp

                    <div class="qr-code-wrapper mb--20">
                        <img src="{{ $qrUrl }}" alt="VietQR" style="max-width: 300px; border: 4px solid #f8f9fa; border-radius: 20px;">
                    </div>
                    
                    <div class="bank-details text-start d-inline-block mx-auto" style="max-width: 400px; width: 100%;">
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted">Ngân hàng:</span>
                            <strong class="text-primary">{{ strtoupper($bankId) }}</strong>
                        </div>
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted">Số tài khoản:</span>
                            <strong class="text-dark">{{ $accNo }}</strong>
                        </div>
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted">Chủ tài khoản:</span>
                            <strong class="text-dark">{{ strtoupper($accName) }}</strong>
                        </div>
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted">Số tiền:</span>
                            <strong class="text-danger">{{ number_format($order->total, 0, ',', '.') }}₫</strong>
                        </div>
                        <div class="d-flex justify-content-between py-2">
                            <span class="text-muted">Nội dung:</span>
                            <strong class="text-dark">{{ $fullDesc }}</strong>
                        </div>
                    </div>
                    
                    <div class="alert alert-info mt--20 rounded-xl border-0" style="background: #e0f2fe; color: #0369a1;">
                        <i class="fa-solid fa-circle-info me-2"></i> Vui lòng quét mã QR hoặc chuyển khoản chính xác nội dung trên để đơn hàng được xác nhận nhanh nhất.
                    </div>
                </div>
                @endif

                <div class="action-buttons d-flex gap-3 justify-content-center">
                    <a href="{{ route('shop.index') }}" class="rts-btn btn-primary px-5 py-3 rounded-pill">Tiếp tục mua sắm</a>
                    <a href="{{ route('profile') . '?tab=orders' }}" class="rts-btn btn-outline border-only px-5 py-3 rounded-pill">Xem đơn hàng</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
