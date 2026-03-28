@extends('admin.layouts.app')
@section('title', 'Tài khoản của tôi')
@section('page-title', 'Tài khoản của tôi')
@section('page-subtitle', 'Chỉnh sửa thông tin và mật khẩu')

@section('content')
<div style="max-width:680px;">
    <form action="{{ route('admin.account.update') }}" method="POST" id="account-form">
        @csrf @method('PUT')

        {{-- Thông tin --}}
        <div class="card" style="margin-bottom:16px;">
            <div class="card-header">
                <span class="card-title"><i class="fa-solid fa-user" style="color:#3b82f6;margin-right:8px;"></i>Thông tin cá nhân</span>
            </div>
            <div class="card-body">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div>
                        <label class="form-label">Họ tên <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Email <span style="color:#ef4444;">*</span></label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Số điện thoại</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="form-input" placeholder="0901234567">
                    </div>
                    <div style="display:flex;align-items:center;gap:10px;padding-top:24px;">
                        <div style="width:42px;height:42px;border-radius:50%;background:linear-gradient(135deg,#3b82f6,#8b5cf6);display:flex;align-items:center;justify-content:center;font-size:16px;font-weight:700;color:#fff;">
                            {{ strtoupper(substr($user->name,0,1)) }}
                        </div>
                        <div>
                            <p style="font-size:13px;font-weight:600;color:#0f172a;">{{ $user->name }}</p>
                            <span class="badge badge-blue" style="font-size:11px;">Admin</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Mật khẩu --}}
        <div class="card" style="margin-bottom:16px;">
            <div class="card-header">
                <span class="card-title"><i class="fa-solid fa-lock" style="color:#f59e0b;margin-right:8px;"></i>Đổi mật khẩu</span>
            </div>
            <div class="card-body">
                <p style="font-size:12.5px;color:#94a3b8;margin-bottom:14px;">Để trống nếu không muốn thay đổi.</p>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div>
                        <label class="form-label">Mật khẩu mới</label>
                        <input type="password" name="password" class="form-input" placeholder="Tối thiểu 6 ký tự">
                    </div>
                    <div>
                        <label class="form-label">Xác nhận mật khẩu</label>
                        <input type="password" name="password_confirmation" class="form-input" placeholder="Nhập lại mật khẩu">
                    </div>
                </div>
            </div>
        </div>

        <div style="display:flex;justify-content:flex-end;">
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-floppy-disk"></i> Lưu thay đổi
            </button>
        </div>
    </form>
</div>
@endsection
