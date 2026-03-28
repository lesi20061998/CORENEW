@extends('layouts.app')

@section('title', 'Tài khoản của tôi - VietTinMart')

@section('content')
<div class="breadcrumb-area">
    <div class="container">
        <div class="breadcrumb-content">
            <ul class="breadcrumb-list">
                <li><a href="{{ route('home') }}">Trang chủ</a></li>
                <li class="active">Tài khoản</li>
            </ul>
        </div>
    </div>
</div>

<section class="account-area section-padding-tb">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-3">
                <div class="account-sidebar p-3 border rounded">
                    <div class="user-info text-center mb-4">
                        <div class="avatar mb-2">
                            <i class="fa-solid fa-circle-user" style="font-size:4rem; color:#ccc;"></i>
                        </div>
                        <h6>{{ $user->name }}</h6>
                        <small class="text-muted">{{ $user->email }}</small>
                    </div>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('account.profile') }}" class="d-block p-2 rounded bg-light"><i class="fa-solid fa-user me-2"></i>Thông tin cá nhân</a></li>
                        <li class="mb-2"><a href="{{ route('account.orders') }}" class="d-block p-2 rounded"><i class="fa-solid fa-box me-2"></i>Đơn hàng của tôi</a></li>
                        <li>
                            <form action="{{ route('auth.logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-link p-2 text-danger text-decoration-none">
                                    <i class="fa-solid fa-right-from-bracket me-2"></i>Đăng xuất
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="p-4 border rounded">
                    <h5 class="mb-4">Thông tin cá nhân</h5>

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form action="{{ route('account.profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Họ và tên</label>
                                <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Số điện thoại</label>
                                <input type="tel" name="phone" class="form-control" value="{{ $user->phone ?? '' }}">
                            </div>
                            <div class="col-12">
                                <button type="submit" class="rts-btn btn-primary">Cập nhật thông tin</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
