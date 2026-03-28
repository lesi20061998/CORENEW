@extends('admin.layouts.app')
@section('title', 'Đơn hàng')
@section('page-title', 'Quản lý đơn hàng')
@section('page-subtitle', 'Theo dõi và xử lý tất cả đơn hàng')

@section('content')
{{-- Filters --}}
<form method="GET" class="flex flex-wrap items-center gap-3 mb-5">
    <input type="text" name="search" value="{{ request('search') }}"
           placeholder="Tìm mã đơn, tên, SĐT..."
           class="form-input w-64">
    <select name="status" class="form-select w-44">
        <option value="">Tất cả trạng thái</option>
        @foreach($statuses as $key => $s)
        <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>{{ $s['label'] }}</option>
        @endforeach
    </select>
    <select name="payment_status" class="form-select w-44">
        <option value="">Tất cả thanh toán</option>
        @foreach($paymentStatuses as $key => $s)
        <option value="{{ $key }}" {{ request('payment_status') === $key ? 'selected' : '' }}>{{ $s['label'] }}</option>
        @endforeach
    </select>
    <button type="submit" class="btn-primary">
        <i class="fa-solid fa-magnifying-glass text-xs"></i> Lọc
    </button>
    @if(request()->hasAny(['search','status','payment_status']))
    <a href="{{ route('admin.orders.index') }}" class="btn-secondary">Xóa lọc</a>
    @endif
    <div class="ml-auto flex items-center gap-2">
        <a href="{{ route('admin.orders.trash') }}" class="btn-secondary">
            <i class="fa-solid fa-trash-can text-xs"></i> Thùng rác
        </a>
        <a href="{{ route('admin.orders.create') }}" class="btn-primary">
            <i class="fa-solid fa-plus text-xs"></i> Tạo đơn mới
        </a>
    </div>
</form>

<div class="card overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="tbl-th">Mã đơn</th>
                <th class="tbl-th">Khách hàng</th>
                <th class="tbl-th">Sản phẩm</th>
                <th class="tbl-th">Tổng tiền</th>
                <th class="tbl-th">Trạng thái</th>
                <th class="tbl-th">Thanh toán</th>
                <th class="tbl-th">Ngày đặt</th>
                <th class="tbl-th w-10"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($orders as $order)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="tbl-td">
                    <a href="{{ route('admin.orders.show', $order) }}"
                       class="text-sm font-semibold text-blue-600 hover:underline font-mono">
                        {{ $order->order_number }}
                    </a>
                </td>
                <td class="tbl-td">
                    <p class="text-sm font-medium text-gray-800">{{ $order->customer_name }}</p>
                    <p class="text-xs text-gray-400">{{ $order->customer_phone }}</p>
                </td>
                <td class="tbl-td">
                    <span class="text-sm text-gray-600">{{ $order->items->count() }} sản phẩm</span>
                </td>
                <td class="tbl-td">
                    <span class="text-sm font-semibold text-gray-800">
                        {{ number_format($order->total, 0, ',', '.') }}₫
                    </span>
                </td>
                <td class="tbl-td">
                    @php $sc = $order->status_color; @endphp
                    <span class="badge-{{ $sc }} text-xs">{{ $order->status_label }}</span>
                </td>
                <td class="tbl-td">
                    @php $pc = \App\Models\Order::$paymentStatuses[$order->payment_status]['color'] ?? 'gray'; @endphp
                    <span class="badge-{{ $pc }} text-xs">{{ $order->payment_status_label }}</span>
                </td>
                <td class="tbl-td">
                    <span class="text-xs text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                </td>
                <td class="tbl-td">
                    <a href="{{ route('admin.orders.show', $order) }}"
                       class="action-btn hover:bg-blue-50 hover:text-blue-600">
                        <i class="fa-solid fa-eye text-xs"></i>
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center py-12 text-sm text-gray-400">
                    <i class="fa-solid fa-box-open text-2xl text-gray-200 mb-2 block"></i>
                    Chưa có đơn hàng nào
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
