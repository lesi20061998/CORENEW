@extends('layouts.app')

@section('title', 'Theo dõi đơn hàng - VietTinMart')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb-area">
    <div class="container">
        <div class="breadcrumb-content">
            <ul class="breadcrumb-list">
                <li><a href="{{ route('home') }}">Trang chủ</a></li>
                <li class="active">Theo dõi đơn hàng</li>
            </ul>
        </div>
    </div>
</div>

<!-- Order Track Section -->
<section class="order-track-area section-padding-tb">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="section-title text-center mb-5">
                    <h2>Theo dõi đơn hàng</h2>
                    <p>Nhập mã đơn hàng để kiểm tra trạng thái giao hàng</p>
                </div>

                <div class="track-form-wrap p-4 border rounded shadow-sm">
                    <form action="#" method="GET">
                        <div class="mb-3">
                            <label for="order_number" class="form-label fw-semibold">Mã đơn hàng</label>
                            <input type="text"
                                class="form-control form-control-lg"
                                id="order_number"
                                name="order_number"
                                placeholder="Ví dụ: VTM-20250001"
                                value="{{ request('order_number') }}">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Email đặt hàng</label>
                            <input type="email"
                                class="form-control form-control-lg"
                                id="email"
                                name="email"
                                placeholder="email@example.com"
                                value="{{ request('email') }}">
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="fa-solid fa-magnifying-glass me-2"></i>Theo dõi đơn hàng
                        </button>
                    </form>
                </div>

                <div class="text-center mt-4">
                    <p class="text-muted small">
                        Bạn đã có tài khoản?
                        <a href="{{ route('account.orders') }}">Xem tất cả đơn hàng</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
