@extends('layouts.app')

@section('title', 'Đăng nhập')

@section('body_class', 'login-page')

@section('content')
    <div class="rts-register-area rts-section-gap bg_light-1">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="registration-wrapper-1">
                        <div class="logo-area mb--0 text-center">
                            <img src="{{ asset('storage/media/viettinmart-logo-tach-nen-1774681112.png') }}"
                                alt="VIETTIN MART" class="logo mb--20" style="max-height: 200px; width: auto;">
                        </div>
                        <h3 class="title animated fadeIn text-center">Đăng nhập tài khoản</h3>
                        <form action="{{ route('login') }}" method="POST" class="registration-form">
                            @csrf
                            <div class="input-wrapper">
                                <label for="email">Email*</label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                            </div>
                            <div class="input-wrapper">
                                <label for="password">Mật khẩu*</label>
                                <input type="password" id="password" name="password" required>
                            </div>
                            <div class="form-check mb-4 mt-2">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                <label class="form-check-label small" for="remember" style="color: var(--color-body);">Ghi nhớ đăng nhập</label>
                            </div>
                            @if($errors->any())
                                <div class="alert alert-danger p-2 mb-3 small">
                                    {{ $errors->first() }}
                                </div>
                            @endif
                            <button type="submit" class="rts-btn btn-primary w-100">Đăng nhập</button>
                                <p class="mt--20">Chưa có tài khoản? <a href="{{ route('register') }}">Đăng ký ngay</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection