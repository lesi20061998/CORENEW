@extends('admin.layouts.app')
@section('title', 'Thùng rác - Đơn hàng')
@section('page-title', 'Thùng rác')
@section('page-subtitle', 'Danh sách các đơn hàng đã xóa tạm thời')

@section('content')
<div class="mb-5 flex items-center justify-between">
    <a href="{{ route('admin.orders.index') }}" class="btn-secondary">
        <i class="fa-solid fa-arrow-left text-xs"></i> Quay lại
    </a>
    <form method="GET" class="flex gap-2">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Tìm theo mã đơn, khách, SĐT..."
               class="form-input w-64">
        <button type="submit" class="btn-primary">Tìm kiếm</button>
    </form>
</div>

<div class="card overflow-hidden">
    <table class="w-full">
        <thead class="bg-slate-50 border-b border-slate-100">
            <tr>
                <th class="tbl-th">Mã đơn</th>
                <th class="tbl-th">Khách hàng</th>
                <th class="tbl-th">Tổng</th>
                <th class="tbl-th">Ngày xóa</th>
                <th class="tbl-th w-32 text-center">Thao tác</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-50">
            @forelse($orders as $order)
            <tr>
                <td class="tbl-td font-mono font-bold text-slate-800">{{ $order->order_number }}</td>
                <td class="tbl-td">
                    <p class="text-sm font-medium">{{ $order->customer_name }}</p>
                    <p class="text-xs text-slate-400">{{ $order->customer_phone }}</p>
                </td>
                <td class="tbl-td text-sm font-semibold text-slate-800">
                    {{ number_format($order->total, 0, ',', '.') }}₫
                </td>
                <td class="tbl-td text-xs text-slate-500">
                    {{ $order->deleted_at->format('d/m/Y H:i') }}
                </td>
                <td class="tbl-td">
                    <div class="flex items-center justify-center gap-2">
                        <form action="{{ route('admin.orders.restore', $order->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-colors" title="Khôi phục">
                                <i class="fa-solid fa-rotate-left"></i>
                            </button>
                        </form>
                        <form action="{{ route('admin.orders.force-delete', $order->id) }}" method="POST" onsubmit="return confirm('Xóa vĩnh viễn đơn hàng này? Thao tác này không thể hoàn tác!')">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Xóa vĩnh viễn">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="py-12 text-center text-slate-400 text-sm">
                    Thùng rác hiện đang trống.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($orders->hasPages())
<div class="mt-4">{{ $orders->links() }}</div>
@endif
@endsection
