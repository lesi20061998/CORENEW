@extends('layouts.app')

@section('title', 'Đăng ký - VietTinMart')

@section('content')
<section class="auth-area section-padding-tb">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="auth-form-wrap p-5 border rounded shadow-sm">
                    <div class="text-center mb-4">
                        <h3>Tạo tài khoản</h3>
                        <p class="text-muted">Đăng ký để nhận ưu đãi độc quyền</p>
                    </div>

                    @if($errors->any())
                        <div class="alert alert-danger">{{ $errors->first() }}</div>
                    @endif

                    <form action="{{ route('auth.register') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Họ và tên</label>
                            <input type="text" name="name" class="form-control form-control-lg"
                                value="{{ old('name') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control form-control-lg"
                                value="{{ old('email') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mật khẩu</label>
                            <input type="password" name="password" class="form-control form-control-lg" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Xác nhận mật khẩu</label>
                            <input type="password" name="password_confirmation" class="form-control form-control-lg" required>
                        </div>
                        <button type="submit" class="rts-btn btn-primary w-100 btn-lg">Đăng ký</button>
                    </form>

                    <div class="text-center mt-4">
                        <p class="text-muted">Đã có tài khoản? <a href="{{ route('login') }}">Đăng nhập</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
