@extends('admin.layouts.app')
@section('title', 'Mã giảm giá')
@section('page-title', 'Quản lý Mã giảm giá')
@section('page-subtitle', 'Tạo và quản lý các chương trình khuyến mãi, mã giảm giá cho khách hàng')
@section('page-actions')
    <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary">
        <i class="fa-solid fa-plus mr-2"></i> Tạo mã mới
    </a>
@endsection

@section('content')
<div class="card">
    <div class="card-header flex justify-between items-center">
        <h3 class="card-title">Danh sách mã giảm giá ({{ $coupons->total() }})</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-slate-50/50">
                    <th class="tbl-th">Mã / Tên</th>
                    <th class="tbl-th text-center">Loại / Giá trị</th>
                    <th class="tbl-th text-center">Định mức tối thiểu</th>
                    <th class="tbl-th text-center">Thời hạn</th>
                    <th class="tbl-th text-center">Sử dụng</th>
                    <th class="tbl-th text-center">Trạng thái</th>
                    <th class="tbl-th w-24"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($coupons as $coupon)
                <tr class="group hover:bg-slate-50/30 transition-all">
                    <td class="tbl-td">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                                %
                            </div>
                            <div>
                                <span class="block font-black text-slate-900 uppercase tracking-wider">{{ $coupon->code }}</span>
                                <span class="text-[11px] text-slate-400 font-bold uppercase">{{ $coupon->name ?? 'Không có tên' }}</span>
                            </div>
                        </div>
                    </td>
                    <td class="tbl-td text-center">
                        @if($coupon->type === 'percentage')
                            <span class="badge badge-blue">{{ $coupon->value }}%</span>
                            @if($coupon->max_discount_value)
                                <p class="text-[10px] text-slate-400 mt-1 italic">Tối đa {{ number_format($coupon->max_discount_value, 0, ',', '.') }}đ</p>
                            @endif
                        @else
                            <span class="badge badge-green">{{ number_format($coupon->value, 0, ',', '.') }}đ</span>
                        @endif
                    </td>
                    <td class="tbl-td text-center font-bold text-slate-700">
                        {{ number_format($coupon->min_order_value, 0, ',', '.') }}đ
                    </td>
                    <td class="tbl-td text-center">
                        <div class="flex flex-col gap-1 text-[11px] font-bold">
                            @if($coupon->start_date)
                                <span class="text-slate-500">Bắt đầu: {{ $coupon->start_date->format('d/m/Y') }}</span>
                            @endif
                            @if($coupon->end_date)
                                <span class="text-rose-500">Hết hạn: {{ $coupon->end_date->format('d/m/Y') }}</span>
                            @else
                                <span class="text-green-600">Vô thời hạn</span>
                            @endif
                        </div>
                    </td>
                    <td class="tbl-td text-center">
                        <span class="text-sm font-black text-slate-800">{{ $coupon->usage_count }}</span>
                        <span class="text-[10px] text-slate-400 block font-bold">/{{ $coupon->usage_limit ?? '∞' }}</span>
                    </td>
                    <td class="tbl-td text-center">
                        @php $reason = $coupon->getInvalidReason(); @endphp
                        @if(!$reason)
                            <span class="badge badge-green">Đang hiệu lực</span>
                        @else
                            <span class="badge badge-rose">{{ $reason }}</span>
                        @endif
                    </td>
                    <td class="tbl-td text-right">
                        <div class="flex justify-end gap-2 px-4 opacity-0 group-hover:opacity-100 transition-all">
                            <a href="{{ route('admin.coupons.edit', $coupon) }}" class="action-btn edit" title="Sửa">
                                <i class="fa-solid fa-pen-nib"></i>
                            </a>
                            <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" onsubmit="return confirm('Xóa mã này?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="action-btn del"><i class="fa-solid fa-trash-can"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-20 text-center text-slate-400">Chưa có mã giảm giá nào.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($coupons->hasPages())
    <div class="p-6 border-t border-slate-50">
        {{ $coupons->links() }}
    </div>
    @endif
</div>
@endsection
