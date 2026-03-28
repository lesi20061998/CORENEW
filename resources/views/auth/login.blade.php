@extends('layouts.app')

@section('title', 'Đăng nhập - VietTinMart')

@section('content')
<section class="auth-area section-padding-tb">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="auth-form-wrap p-5 border rounded shadow-sm">
                    <div class="text-center mb-4">
                        <h3>Đăng nhập</h3>
                        <p class="text-muted">Chào mừng bạn quay lại VietTinMart</p>
                    </div>

                    @if($errors->any())
                        <div class="alert alert-danger">{{ $errors->first() }}</div>
                    @endif

                    <form action="{{ route('login') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control form-control-lg"
                                value="{{ old('email') }}" required autofocus>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mật khẩu</label>
                            <input type="password" name="password" class="form-control form-control-lg" required>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                <label class="form-check-label" for="remember">Ghi nhớ đăng nhập</label>
                            </div>
                        </div>
                        <button type="submit" class="rts-btn btn-primary w-100 btn-lg">Đăng nhập</button>
                    </form>

                    <div class="text-center mt-4">
                        <p class="text-muted">Chưa có tài khoản? <a href="{{ route('auth.register') }}">Đăng ký ngay</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
