@extends('layouts.app')

@section('title', 'Chi tiết đơn hàng #' . $order->order_number . ' - VietTinMart')

@section('content')
<div class="breadcrumb-area">
    <div class="container">
        <div class="breadcrumb-content">
            <ul class="breadcrumb-list">
                <li><a href="{{ route('home') }}">Trang chủ</a></li>
                <li><a href="{{ route('account.orders') }}">Đơn hàng</a></li>
                <li class="active">{{ $order->order_number }}</li>
            </ul>
        </div>
    </div>
</div>

<section class="section-padding-tb">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="p-4 border rounded mb-4">
                    <h5 class="mb-3">Sản phẩm đã đặt</h5>
                    @foreach($order->items as $item)
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                        <div>
                            <strong>{{ $item->name }}</strong>
                            <small class="text-muted d-block">{{ $item->quantity }} × {{ number_format($item->price) }}đ</small>
                        </div>
                        <span>{{ number_format($item->price * $item->quantity) }}đ</span>
                    </div>
                    @endforeach
                    <div class="d-flex justify-content-between mt-3">
                        <strong>Tổng cộng:</strong>
                        <strong class="text-primary">{{ number_format($order->total) }}đ</strong>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="p-4 border rounded">
                    <h5 class="mb-3">Thông tin đơn hàng</h5>
                    <p><strong>Mã đơn:</strong> {{ $order->order_number }}</p>
                    <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                    <p><strong>Người nhận:</strong> {{ $order->customer_name }}</p>
                    <p><strong>Điện thoại:</strong> {{ $order->customer_phone }}</p>
                    <p><strong>Địa chỉ:</strong> {{ $order->shipping_address }}</p>
                    <p><strong>Thanh toán:</strong> {{ $order->payment_method === 'cod' ? 'Tiền mặt (COD)' : 'Chuyển khoản' }}</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
