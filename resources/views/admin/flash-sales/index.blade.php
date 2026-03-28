@extends('admin.layouts.app')
@section('title', 'Flash Sale')
@section('page-title', 'Chiến dịch Flash Sale')
@section('page-subtitle', 'Quản lý các chiến dịch giảm giá theo thời gian')
@section('page-actions')
    <a href="{{ route('admin.flash-sales.create') }}" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i> Tạo chiến dịch
    </a>
@endsection

@section('content')

{{-- Filters --}}
<form method="GET" action="{{ route('admin.flash-sales.index') }}" class="card mb-4">
    <div class="card-body" style="display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end;">
        <div style="flex:1;min-width:200px;">
            <label style="font-size:12px;font-weight:600;color:#64748b;display:block;margin-bottom:4px;">Tìm kiếm</label>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Tên chiến dịch..."
                   style="width:100%;padding:7px 10px;border:1.5px solid #e2e8f0;border-radius:8px;font-size:13px;outline:none;">
        </div>
        <div style="min-width:160px;">
            <label style="font-size:12px;font-weight:600;color:#64748b;display:block;margin-bottom:4px;">Trạng thái</label>
            <select name="status" style="width:100%;padding:7px 10px;border:1.5px solid #e2e8f0;border-radius:8px;font-size:13px;outline:none;background:#fff;">
                <option value="">Tất cả</option>
                <option value="draft"  {{ request('status') === 'draft'  ? 'selected' : '' }}>Nháp</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Đang chạy</option>
                <option value="ended"  {{ request('status') === 'ended'  ? 'selected' : '' }}>Đã kết thúc</option>
            </select>
        </div>
        <div style="display:flex;gap:6px;">
            <button type="submit" class="btn btn-primary" style="padding:7px 16px;">
                <i class="fa-solid fa-magnifying-glass"></i> Lọc
            </button>
            @if(request()->hasAny(['search','status']))
            <a href="{{ route('admin.flash-sales.index') }}" class="btn btn-secondary" style="padding:7px 12px;">
                <i class="fa-solid fa-xmark"></i>
            </a>
            @endif
        </div>
    </div>
</form>

@if(session('success'))
<div class="alert alert-success mb-4">{{ session('success') }}</div>
@endif

<div class="card" style="overflow:hidden;">
    <div class="card-header">
        <p style="font-size:13px;font-weight:700;color:#374151;">
            Tất cả chiến dịch
            <span style="background:#f1f5f9;color:#64748b;font-size:11px;font-weight:600;padding:2px 8px;border-radius:20px;margin-left:6px;">{{ $campaigns->total() }}</span>
        </p>
    </div>
    <table class="w-full">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="tbl-th">Tên chiến dịch</th>
                <th class="tbl-th">Thời gian</th>
                <th class="tbl-th">Số items</th>
                <th class="tbl-th">Trạng thái</th>
                <th class="tbl-th w-10"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($campaigns as $campaign)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="tbl-td">
                    <a href="{{ route('admin.flash-sales.show', $campaign) }}"
                       class="text-sm font-semibold text-blue-600 hover:underline">
                        {{ $campaign->name }}
                    </a>
                    @if($campaign->is_running)
                    <span style="background:#dcfce7;color:#16a34a;font-size:10px;font-weight:700;padding:1px 6px;border-radius:20px;margin-left:6px;">
                        <i class="fa-solid fa-bolt"></i> LIVE
                    </span>
                    @endif
                    @if($campaign->description)
                    <p class="text-xs text-gray-400 mt-0.5">{{ Str::limit($campaign->description, 60) }}</p>
                    @endif
                </td>
                <td class="tbl-td">
                    <p class="text-xs text-gray-600">
                        <i class="fa-regular fa-clock text-gray-400 mr-1"></i>
                        {{ $campaign->starts_at->format('d/m/Y H:i') }}
                    </p>
                    <p class="text-xs text-gray-400">
                        <i class="fa-solid fa-arrow-right text-[10px] text-gray-300"></i> {{ $campaign->ends_at->format('d/m/Y H:i') }}
                    </p>
                </td>
                <td class="tbl-td">
                    <span class="text-sm text-gray-700">{{ $campaign->items_count }} items</span>
                </td>
                <td class="tbl-td">
                    @php $color = \App\Models\FlashSaleCampaign::$statuses[$campaign->status]['color'] ?? 'gray'; @endphp
                    <span class="badge-{{ $color }} text-xs">{{ $campaign->status_label }}</span>
                </td>
                <td class="tbl-td">
                    <div style="display:flex;gap:4px;">
                        <a href="{{ route('admin.flash-sales.edit', $campaign) }}"
                           class="action-btn hover:bg-blue-50 hover:text-blue-600" title="Sửa">
                            <i class="fa-solid fa-pen text-xs"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.flash-sales.destroy', $campaign) }}"
                              onsubmit="return confirm('Xóa chiến dịch này?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="action-btn hover:bg-red-50 hover:text-red-600" title="Xóa">
                                <i class="fa-solid fa-trash text-xs"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="tbl-td text-center text-gray-400 py-10">
                    <i class="fa-solid fa-bolt text-3xl mb-2 block opacity-30"></i>
                    Chưa có chiến dịch flash sale nào
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($campaigns->hasPages())
    <div class="card-footer">{{ $campaigns->links() }}</div>
    @endif
</div>
@endsection
