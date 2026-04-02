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
<form method="GET" action="{{ route('admin.flash-sales.index') }}" class="card mb-8">
    <div class="p-8 flex flex-wrap items-end gap-6">
        <div class="flex-1 min-w-[300px]">
            <label class="form-label">Tìm kiếm chiến dịch</label>
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Nhập tên chiến dịch..."
                       class="form-input pl-12 shadow-sm border-slate-100">
                <i class="fa-solid fa-magnifying-glass absolute left-5 top-1/2 -translate-y-1/2 text-slate-300"></i>
            </div>
        </div>
        <div class="w-64">
            <label class="form-label">Trạng thái</label>
            <select name="status" class="form-select shadow-sm border-slate-100">
                <option value="">Tất cả trạng thái</option>
                <option value="draft"  {{ request('status') === 'draft'  ? 'selected' : '' }}>Nháp</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Đang chạy</option>
                <option value="ended"  {{ request('status') === 'ended'  ? 'selected' : '' }}>Đã kết thúc</option>
            </select>
        </div>
        <div class="flex gap-3">
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-filter mr-2"></i> Áp dụng lọc
            </button>
            @if(request()->hasAny(['search','status']))
            <a href="{{ route('admin.flash-sales.index') }}" class="btn btn-secondary">
                <i class="fa-solid fa-rotate-left mr-2"></i> Đặt lại
            </a>
            @endif
        </div>
    </div>
</form>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Tất cả chiến dịch Flash Sale ({{ $campaigns->total() }})</h3>
    </div>
    <div class="overflow-x-auto custom-scroll">
        <table class="w-full">
            <thead>
                <tr class="bg-slate-50/50">
                    <th class="tbl-th">Thông tin chiến dịch</th>
                    <th class="tbl-th text-center">Thời gian diễn ra</th>
                    <th class="tbl-th text-center">Hệ số Items</th>
                    <th class="tbl-th text-center">Trạng thái</th>
                    <th class="tbl-th w-24"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($campaigns as $campaign)
                <tr class="group transition-all hover:bg-slate-50/30">
                    <td class="tbl-td min-w-[280px]">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-orange-50 text-orange-500 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-bolt text-sm"></i>
                            </div>
                            <div>
                                <a href="{{ route('admin.flash-sales.show', $campaign) }}"
                                   class="text-[15px] font-black text-slate-900 group-hover:text-blue-600 transition-colors uppercase tracking-tighter">
                                    {{ $campaign->name }}
                                </a>
                                @if($campaign->description)
                                <p class="text-[11px] text-slate-400 font-bold uppercase tracking-widest mt-1">{{ Str::limit($campaign->description, 60) }}</p>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="tbl-td text-center">
                        <div class="inline-flex flex-col gap-1">
                            <span class="text-[12px] font-black text-slate-700 bg-slate-100 px-3 py-1 rounded-lg">
                                {{ $campaign->starts_at->format('d/m/Y H:i') }}
                            </span>
                            <i class="fa-solid fa-arrow-down text-[10px] text-slate-300"></i>
                            <span class="text-[12px] font-black text-rose-500 bg-rose-50 px-3 py-1 rounded-lg">
                                {{ $campaign->ends_at->format('d/m/Y H:i') }}
                            </span>
                        </div>
                    </td>
                    <td class="tbl-td text-center">
                        <span class="text-sm font-black text-slate-900">{{ $campaign->items_count }}</span>
                        <span class="text-[10px] text-slate-400 font-bold uppercase block">Sản phẩm</span>
                    </td>
                    <td class="tbl-td text-center">
                        @php $color = \App\Models\FlashSaleCampaign::$statuses[$campaign->status]['color'] ?? 'gray'; @endphp
                        @php $badgeColor = $color === 'active' ? 'green' : ($color === 'ended' ? 'rose' : 'slate'); @endphp
                        <span class="badge badge-{{ $badgeColor }}">{{ $campaign->status_label }}</span>
                        @if($campaign->is_running)
                        <div class="mt-2 animate-pulse flex items-center justify-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-green-500"></span>
                            <span class="text-[9px] font-black text-green-600 uppercase tracking-widest">Đang chạy</span>
                        </div>
                        @endif
                    </td>
                    <td class="tbl-td">
                        <div class="flex items-center justify-end gap-2 px-4 opacity-0 group-hover:opacity-100 transition-all transform translate-x-2 group-hover:translate-x-0">
                            <a href="{{ route('admin.flash-sales.edit', $campaign) }}"
                               class="action-btn edit" title="Chỉnh sửa">
                                <i class="fa-solid fa-pen-nib"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.flash-sales.destroy', $campaign) }}"
                                  onsubmit="return confirm('Bạn chắc chắn muốn xóa chiến dịch này?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="action-btn del" title="Xóa bỏ">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-32 text-center">
                        <div class="flex flex-col items-center gap-4 text-slate-300">
                            <i class="fa-solid fa-bolt-slash text-6xl opacity-10"></i>
                            <p class="text-xs font-black uppercase tracking-[0.25em]">Chưa có chiến dịch Flash Sale</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($campaigns->hasPages())
    <div class="p-8 bg-slate-50/50 border-t border-slate-50">{{ $campaigns->links() }}</div>
    @endif
</div>
@endsection
