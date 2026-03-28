@extends('admin.layouts.app')
@section('title', 'Khách hàng')
@section('page-title', 'Quản lý tài khoản')
@section('page-subtitle', 'Danh sách người dùng và phân quyền')

@section('content')
{{-- Filters --}}
<form method="GET" style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:20px;align-items:center;">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm tên, email, SĐT..."
           class="form-input" style="width:260px;">
    <select name="role" class="form-select" style="width:160px;">
        <option value="">Tất cả vai trò</option>
        <option value="admin" {{ request('role')==='admin' ? 'selected' : '' }}>Admin</option>
        <option value="user"  {{ request('role')==='user'  ? 'selected' : '' }}>Khách hàng</option>
    </select>
    <button type="submit" class="btn btn-primary btn-sm">
        <i class="fa-solid fa-magnifying-glass"></i> Lọc
    </button>
    @if(request()->hasAny(['search','role']))
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm">Xóa lọc</a>
    @endif
</form>

<div class="tbl-wrap">
    <table style="width:100%;border-collapse:collapse;">
        <thead class="tbl-head">
            <tr>
                <th class="tbl-th">Người dùng</th>
                <th class="tbl-th">Liên hệ</th>
                <th class="tbl-th">Vai trò</th>
                <th class="tbl-th">Đơn hàng</th>
                <th class="tbl-th">Ngày tạo</th>
                <th class="tbl-th" style="width:80px;"></th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr class="tbl-tr">
                <td class="tbl-td">
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#3b82f6,#8b5cf6);display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;color:#fff;flex-shrink:0;">
                            {{ strtoupper(substr($user->name,0,1)) }}
                        </div>
                        <div>
                            <p style="font-size:13.5px;font-weight:600;color:#0f172a;">{{ $user->name }}</p>
                            <p style="font-size:12px;color:#94a3b8;">{{ $user->email }}</p>
                        </div>
                    </div>
                </td>
                <td class="tbl-td">
                    <p style="font-size:13px;color:#374151;">{{ $user->phone ?: 'N/A' }}</p>
                </td>
                <td class="tbl-td">
                    @if($user->isAdmin())
                        <span class="badge badge-blue">Admin</span>
                    @else
                        <span class="badge badge-gray">Khách hàng</span>
                    @endif
                </td>
                <td class="tbl-td">
                    <span style="font-size:13px;font-weight:600;color:#374151;">{{ $user->orders_count }}</span>
                </td>
                <td class="tbl-td">
                    <span style="font-size:12px;color:#94a3b8;">{{ $user->created_at->format('d/m/Y') }}</span>
                </td>
                <td class="tbl-td">
                    <div style="display:flex;gap:4px;">
                        <a href="{{ route('admin.users.edit', $user) }}" class="act-btn edit" title="Chỉnh sửa">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        @if($user->id !== auth()->id())
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                              onsubmit="return confirm('Xóa tài khoản {{ $user->name }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="act-btn del" title="Xóa">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center;padding:48px;color:#94a3b8;font-size:13px;">
                    <i class="fa-solid fa-users" style="font-size:28px;opacity:.2;display:block;margin-bottom:10px;"></i>
                    Chưa có tài khoản nào
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($users->hasPages())
<div style="margin-top:16px;">{{ $users->links() }}</div>
@endif
@endsection
