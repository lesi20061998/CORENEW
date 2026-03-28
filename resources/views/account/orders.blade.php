@extends('layouts.app')

@section('title', 'Đơn hàng của tôi - VietTinMart')

@section('content')
<div class="breadcrumb-area">
    <div class="container">
        <div class="breadcrumb-content">
            <ul class="breadcrumb-list">
                <li><a href="{{ route('home') }}">Trang chủ</a></li>
                <li><a href="{{ route('account.profile') }}">Tài khoản</a></li>
                <li class="active">Đơn hàng</li>
            </ul>
        </div>
    </div>
</div>

<section class="account-area section-padding-tb">
    <div class="container">
        <h4 class="mb-4">Đơn hàng của tôi</h4>

        @if($orders->isEmpty())
            <div class="text-center py-5">
                <i class="fa-regular fa-box-open fs-1 text-muted mb-3 d-block"></i>
                <p>Bạn chưa có đơn hàng nào.</p>
                <a href="{{ route('shop.index') }}" class="rts-btn btn-primary">Mua sắm ngay</a>
            </div>
        @else
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Mã đơn hàng</th>
                        <th>Ngày đặt</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td><strong>{{ $order->order_number }}</strong></td>
                        <td>{{ $order->created_at->format('d/m/Y') }}</td>
                        <td>{{ number_format($order->total) }}đ</td>
                        <td>
                            @php
                            $statusMap = ['pending' => ['label' => 'Chờ xử lý', 'class' => 'warning'], 'processing' => ['label' => 'Đang xử lý', 'class' => 'info'], 'shipped' => ['label' => 'Đang giao', 'class' => 'primary'], 'delivered' => ['label' => 'Đã giao', 'class' => 'success'], 'cancelled' => ['label' => 'Đã hủy', 'class' => 'danger']];
                            $s = $statusMap[$order->status] ?? ['label' => $order->status, 'class' => 'secondary'];
                            @endphp
                            <span class="badge bg-{{ $s['class'] }}">{{ $s['label'] }}</span>
                        </td>
                        <td><a href="{{ route('account.order.detail', $order) }}" class="btn btn-sm btn-outline-primary">Chi tiết</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $orders->links('vendor.pagination.custom') }}
        @endif
    </div>
</section>
@endsection
