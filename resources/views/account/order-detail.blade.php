@extends('layouts.app')

@section('title', 'Chi tiết đơn hàng #' . $order->order_number)

@section('content')
    <x-breadcrumb :items="[
            ['label' => 'Tài khoản', 'url' => route('profile')],
            ['label' => 'Đơn hàng của tôi', 'url' => route('profile') . '?tab=orders'],
            ['label' => 'Chi tiết đơn hàng #' . $order->order_number]
        ]" />

    <div class="account-tab-area-start rts-section-gap">
        <div class="container-2">
            <div class="row">
                <div class="col-lg-3">
                    <div class="nav accout-dashborard-nav flex-column nav-pills me-3">
                        <a href="{{ route('profile') }}" class="nav-link"><i class="fa-regular fa-chart-line"></i>Bảng điều khiển</a>
                        <a href="{{ route('profile') . '?tab=orders' }}" class="nav-link active"><i class="fa-regular fa-bag-shopping"></i>Đơn hàng</a>
                        <a href="{{ route('profile') . '?tab=address' }}" class="nav-link"><i class="fa-sharp fa-regular fa-location-dot"></i>Địa chỉ của tôi</a>
                        <a href="{{ route('profile') . '?tab=profile' }}" class="nav-link"><i class="fa-light fa-user"></i>Thông tin cá nhân</a>
                        <button class="nav-link text-danger border-0 text-start bg-transparent" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fa-light fa-right-from-bracket"></i> Đăng xuất
                        </button>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                    </div>
                </div>
                <div class="col-lg-9 pl--50 pl_md--10 pl_sm--10 pt_md--30 pt_sm--30">
                    <div class="order-table-account">
                        <div class="d-flex justify-content-between align-items-center mb--30">
                            <h2 class="title mb--0">Chi tiết đơn hàng #{{ $order->order_number }}</h2>
                            @php
                                $colors = [
                                    'pending' => '#f59e0b',
                                    'processing' => '#3b82f6',
                                    'shipping' => '#8b5cf6',
                                    'delivered' => '#10b981',
                                    'completed' => '#059669',
                                    'cancelled' => '#ef4444',
                                ];
                                $color = $colors[$order->status] ?? '#6b7280';
                            @endphp
                            <span class="badge" style="background-color: {{ $color }}; color: white; border-radius: 4px; padding: 6px 12px; font-weight: 500;">{{ $order->status_label }}</span>
                        </div>
                        <p class="text-muted mb--30" style="margin-top: -20px;">Ngày đặt: {{ $order->created_at->format('d/m/Y H:i') }}</p>

                        <div class="row g-4 mb--40">
                            <div class="col-md-6">
                                <div style="background: #f9f9f9; padding: 20px; border-radius: 8px; border: 1px solid #eee; height: 100%;">
                                    <h6 class="text-uppercase mb--15" style="border-bottom: 2px solid var(--color-primary); display: inline-block; padding-bottom: 5px;">Thông tin nhận hàng</h6>
                                    <p class="mb--5" style="font-weight: 600; color: #333;">{{ $order->customer_name }}</p>
                                    <p class="mb--5"><i class="fa-light fa-phone me-2"></i> {{ $order->customer_phone }}</p>
                                    <p class="mb--5"><i class="fa-light fa-location-dot me-2"></i> {{ $order->shipping_address }}</p>
                                    <p class="mb--0 text-muted" style="font-size: 13px;"><i class="fa-light fa-envelope me-2"></i> {{ $order->customer_email }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div style="background: #f9f9f9; padding: 20px; border-radius: 8px; border: 1px solid #eee; height: 100%;">
                                    <h6 class="text-uppercase mb--15" style="border-bottom: 2px solid var(--color-primary); display: inline-block; padding-bottom: 5px;">Thanh toán & Ghi chú</h6>
                                    <p class="mb--10">Phương thức: <span style="font-weight: 600;">{{ strtoupper($order->payment_method) }}</span></p>
                                    <p class="mb--10">Trạng thái: 
                                        <span class="badge" style="background: {{ $order->payment_status === 'paid' ? '#10b981' : '#f59e0b' }}; color: white; padding: 3px 8px; border-radius: 4px; font-size: 11px;">{{ $order->payment_status_label }}</span>
                                    </p>
                                    @if($order->customer_note)
                                        <div class="mt--15 pt--15" style="border-top: 1px dashed #ddd; font-style: italic; font-size: 13px;">
                                            <strong>Ghi chú:</strong> {{ $order->customer_note }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table">
                                <thead style="background: #f4f4f4;">
                                    <tr>
                                        <th style="padding: 15px;">Sản phẩm</th>
                                        <th style="padding: 15px; text-align: center;">Giá</th>
                                        <th style="padding: 15px; text-align: center;">SL</th>
                                        <th style="padding: 15px; text-align: right;">Tổng</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $item)
                                        <tr>
                                            <td style="padding: 15px;">
                                                <div class="d-flex align-items-center gap-3">
                                                    <img src="{{ $item->image ? asset($item->image) : asset('theme/images/shop/default.png') }}" 
                                                         alt="{{ $item->product_name }}" 
                                                         style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                                                    <div>
                                                        <span style="font-weight: 600; display: block; color: #333;">{{ $item->product_name }}</span>
                                                        @if($item->variant_label)
                                                            <span style="font-size: 12px; color: #888;">{{ $item->variant_label }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td style="padding: 15px; text-align: center;">{{ number_format($item->price, 0, ',', '.') }}đ</td>
                                            <td style="padding: 15px; text-align: center;">x{{ $item->quantity }}</td>
                                            <td style="padding: 15px; text-align: right; font-weight: 600;">{{ number_format($item->total, 0, ',', '.') }}đ</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt--30 p--30" style="background: #f9f9f9; border-radius: 8px; border: 1px solid #eee;">
                            <div class="row justify-content-end">
                                <div class="col-md-5">
                                    <div class="d-flex justify-content-between mb--10">
                                        <span class="text-muted">Tạm tính:</span>
                                        <span style="color: #333;">{{ number_format($order->subtotal, 0, ',', '.') }}đ</span>
                                    </div>
                                    @if($order->discount > 0)
                                        <div class="d-flex justify-content-between mb--10 text-success">
                                            <span>Giảm giá:</span>
                                            <span>-{{ number_format($order->discount, 0, ',', '.') }}đ</span>
                                        </div>
                                    @endif
                                    <div class="d-flex justify-content-between mb--10 pb--10" style="border-bottom: 1px solid #ddd;">
                                        <span class="text-muted">Phí vận chuyển:</span>
                                        <span style="color: #333;">{{ $order->shipping_fee > 0 ? number_format($order->shipping_fee, 0, ',', '.') . 'đ' : 'Miễn phí' }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span style="font-size: 18px; font-weight: 700; color: #333;">Tổng cộng:</span>
                                        <span style="font-size: 22px; font-weight: 700; color: var(--color-primary);">{{ number_format($order->total, 0, ',', '.') }}đ</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt--40 text-center">
                            <a href="{{ route('profile') . '?tab=orders' }}" class="rts-btn btn-primary" style="padding: 15px 40px;">
                                <i class="fa-light fa-arrow-left me-2"></i> Quay lại đơn hàng
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>