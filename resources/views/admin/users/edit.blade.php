@extends('admin.layouts.app')
@section('title', 'Chỉnh sửa tài khoản')
@section('page-title', 'Chỉnh sửa tài khoản')
@section('page-subtitle', $user->name)

@section('page-actions')
<a href="{{ route('admin.users.index') }}" class="btn btn-ghost btn-sm">
    <i class="fa-solid fa-arrow-left"></i> Quay lại
</a>
@endsection

@section('content')
<div style="display:grid;grid-template-columns:1fr 340px;gap:20px;align-items:start;">

    {{-- Left: Edit form --}}
    <div style="display:flex;flex-direction:column;gap:16px;">

        {{-- Thông tin cơ bản --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title"><i class="fa-solid fa-user" style="color:#3b82f6;margin-right:8px;"></i>Thông tin tài khoản</span>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.users.update', $user) }}" method="POST" id="user-form">
                    @csrf @method('PUT')
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
                        <div>
                            <label class="form-label">Vai trò</label>
                            <select name="role" class="form-select">
                                <option value="user"  {{ old('role',$user->role)==='user'  ? 'selected' : '' }}>Khách hàng</option>
                                <option value="admin" {{ old('role',$user->role)==='admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                        </div>
                        <div style="grid-column:1/-1;">
                            <label class="form-label">Địa chỉ</label>
                            <textarea name="address" class="form-textarea" rows="2" placeholder="Địa chỉ giao hàng mặc định">{{ old('address', $user->address) }}</textarea>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Đổi mật khẩu --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title"><i class="fa-solid fa-lock" style="color:#f59e0b;margin-right:8px;"></i>Đổi mật khẩu</span>
            </div>
            <div class="card-body">
                <p style="font-size:12.5px;color:#94a3b8;margin-bottom:14px;">Để trống nếu không muốn thay đổi mật khẩu.</p>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div>
                        <label class="form-label">Mật khẩu mới</label>
                        <input type="password" name="password" form="user-form" class="form-input" placeholder="Tối thiểu 6 ký tự">
                    </div>
                    <div>
                        <label class="form-label">Xác nhận mật khẩu</label>
                        <input type="password" name="password_confirmation" form="user-form" class="form-input" placeholder="Nhập lại mật khẩu">
                    </div>
                </div>
            </div>
        </div>

        <div style="display:flex;justify-content:flex-end;">
            <button type="submit" form="user-form" class="btn btn-primary">
                <i class="fa-solid fa-floppy-disk"></i> Lưu thay đổi
            </button>
        </div>
    </div>

    {{-- Right: Info + Orders --}}
    <div style="display:flex;flex-direction:column;gap:16px;">

        {{-- Tóm tắt --}}
        <div class="card">
            <div class="card-body" style="text-align:center;padding:24px;">
                <div style="width:64px;height:64px;border-radius:50%;background:linear-gradient(135deg,#3b82f6,#8b5cf6);display:flex;align-items:center;justify-content:center;font-size:24px;font-weight:700;color:#fff;margin:0 auto 12px;">
                    {{ strtoupper(substr($user->name,0,1)) }}
                </div>
                <p style="font-size:15px;font-weight:700;color:#0f172a;">{{ $user->name }}</p>
                <p style="font-size:12.5px;color:#94a3b8;margin-top:4px;">{{ $user->email }}</p>
                <div style="margin-top:12px;">
                    @if($user->isAdmin())
                        <span class="badge badge-blue">Admin</span>
                    @else
                        <span class="badge badge-gray">Khách hàng</span>
                    @endif
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-top:16px;text-align:center;">
                    <div style="background:#f8fafc;border-radius:10px;padding:12px;">
                        <p style="font-size:20px;font-weight:700;color:#0f172a;">{{ $user->orders->count() }}</p>
                        <p style="font-size:11px;color:#94a3b8;margin-top:2px;">Đơn hàng</p>
                    </div>
                    <div style="background:#f8fafc;border-radius:10px;padding:12px;">
                        <p style="font-size:13px;font-weight:600;color:#0f172a;">{{ $user->created_at->format('d/m/Y') }}</p>
                        <p style="font-size:11px;color:#94a3b8;margin-top:2px;">Ngày tạo</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Đơn hàng gần đây --}}
        @if($user->orders->count())
        <div class="card">
            <div class="card-header">
                <span class="card-title">Đơn hàng gần đây</span>
                <a href="{{ route('admin.orders.index') }}?search={{ $user->email }}" style="font-size:12px;color:#3b82f6;">Xem tất cả</a>
            </div>
            <div style="padding:0 4px;">
                @foreach($user->orders as $order)
                <a href="{{ route('admin.orders.show', $order) }}"
                   style="display:flex;align-items:center;justify-content:space-between;padding:12px 18px;border-bottom:1px solid #f8fafc;text-decoration:none;transition:background .1s;"
                   onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                    <div>
                        <p style="font-size:13px;font-weight:600;color:#2563eb;font-family:monospace;">{{ $order->order_number }}</p>
                        <p style="font-size:11.5px;color:#94a3b8;margin-top:2px;">{{ $order->created_at->format('d/m/Y') }}</p>
                    </div>
                    <div style="text-align:right;">
                        <p style="font-size:13px;font-weight:600;color:#0f172a;">{{ number_format($order->total,0,',','.') }}₫</p>
                        <span class="badge badge-{{ \App\Models\Order::$statuses[$order->status]['color'] ?? 'gray' }}" style="font-size:10px;padding:2px 8px;">{{ $order->status_label }}</span>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</div>
@endsection
