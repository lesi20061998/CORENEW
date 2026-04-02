@extends('layouts.app')

@section('title', 'Đăng ký tài khoản')

@section('content')
    <div class="rts-register-area rts-section-gap bg_light-1">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="registration-wrapper-1">
                        <div class="logo-area mb--0 text-center">
                            <img src="{{ asset('storage/media/viettinmart-logo-tach-nen-1774681112.png') }}" alt="VIETTIN MART" class="logo mb--20" style="max-height: 120px; width: auto;">
                        </div>
                        <h3 class="title animated fadeIn text-center">Tạo tài khoản mới</h3>
                        <form action="{{ route('register') }}" method="POST" class="registration-form">
                            @csrf
                            <div class="input-wrapper">
                                <label for="name">Họ và tên*</label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}" required>
                            </div>
                            <div class="input-wrapper">
                                <label for="email">Email*</label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                            </div>
                            <div class="input-wrapper">
                                <label for="password">Mật khẩu*</label>
                                <input type="password" id="password" name="password" required>
                            </div>
                            <div class="input-wrapper">
                                <label for="password_confirmation">Xác nhận mật khẩu*</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" required>
                            </div>
                            @if($errors->any())
                                <div class="alert alert-danger p-2 mb-3 small">
                                    {{ $errors->first() }}
                                </div>
                            @endif
                            <button type="submit" class="rts-btn btn-primary w-100">Đăng ký ngay</button>
                                <p class="mt--20">Đã có tài khoản? <a href="{{ route('login') }}">Đăng nhập</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
