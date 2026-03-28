@extends('admin.layouts.app')
@section('title', 'Đơn hàng ' . $order->order_number)
@section('page-title', 'Đơn hàng ' . $order->order_number)
@section('page-subtitle', 'Đặt lúc ' . $order->created_at->format('H:i d/m/Y'))

@section('page-actions')
<a href="{{ route('admin.orders.index') }}" class="btn-secondary">
    <i class="fa-solid fa-arrow-left text-xs"></i> Quay lại
</a>
<a href="{{ route('admin.orders.print', $order) }}" target="_blank" class="btn-primary">
    <i class="fa-solid fa-print text-xs"></i> In đơn hàng
</a>
@endsection

@section('content')
<div class="grid grid-cols-1 xl:grid-cols-[1fr_320px] gap-5">

{{-- Cột trái --}}
<div class="space-y-5">

    {{-- Sản phẩm --}}
    <div class="card">
        <div class="card-header">
            <p class="text-sm font-semibold text-gray-800">Sản phẩm đặt hàng</p>
        </div>
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="tbl-th">Sản phẩm</th>
                    <th class="tbl-th text-right">Đơn giá</th>
                    <th class="tbl-th text-center">SL</th>
                    <th class="tbl-th text-right">Thành tiền</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($order->items as $item)
                <tr>
                    <td class="tbl-td">
                        <div class="flex items-center gap-3">
                            @if($item->image)
                            <img src="{{ $item->image }}" class="w-10 h-10 rounded-lg object-cover border border-gray-200 flex-shrink-0">
                            @else
                            <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                                <i class="fa-solid fa-box text-gray-300 text-xs"></i>
                            </div>
                            @endif
                            <div>
                                <p class="text-sm font-medium text-gray-800">{{ $item->product_name }}</p>
                                @if($item->variant_label)
                                <p class="text-xs text-gray-400">{{ $item->variant_label }}</p>
                                @endif
                                @if($item->sku)
                                <p class="text-xs text-gray-400 font-mono">{{ $item->sku }}</p>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="tbl-td text-right text-sm text-gray-700">
                        {{ number_format($item->price, 0, ',', '.') }}₫
                    </td>
                    <td class="tbl-td text-center text-sm text-gray-700">{{ $item->quantity }}</td>
                    <td class="tbl-td text-right text-sm font-semibold text-gray-800">
                        {{ number_format($item->total, 0, ',', '.') }}₫
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot class="border-t border-gray-100 bg-gray-50">
                <tr>
                    <td colspan="3" class="tbl-td text-right text-sm text-gray-600">Tạm tính</td>
                    <td class="tbl-td text-right text-sm font-medium">{{ number_format($order->subtotal, 0, ',', '.') }}₫</td>
                </tr>
                @if($order->discount > 0)
                <tr>
                    <td colspan="3" class="tbl-td text-right text-sm text-green-600">Giảm giá</td>
                    <td class="tbl-td text-right text-sm font-medium text-green-600">-{{ number_format($order->discount, 0, ',', '.') }}₫</td>
                </tr>
                @endif
                <tr>
                    <td colspan="3" class="tbl-td text-right text-sm text-gray-600">Phí vận chuyển</td>
                    <td class="tbl-td text-right text-sm font-medium">{{ number_format($order->shipping_fee, 0, ',', '.') }}₫</td>
                </tr>
                <tr>
                    <td colspan="3" class="tbl-td text-right text-sm font-bold text-gray-800">Tổng cộng</td>
                    <td class="tbl-td text-right text-base font-bold text-blue-600">{{ number_format($order->total, 0, ',', '.') }}₫</td>
                </tr>
            </tfoot>
        </table>
    </div>

    {{-- Lịch sử trạng thái --}}
    <div class="card">
        <div class="card-header">
            <p class="text-sm font-semibold text-gray-800">Lịch sử trạng thái</p>
        </div>
        <div class="card-body">
            <div class="space-y-3">
                @forelse($order->statusHistories as $history)
                <div class="flex items-start gap-3">
                    <div class="w-2 h-2 rounded-full bg-blue-500 mt-1.5 flex-shrink-0"></div>
                    <div class="flex-1">
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-medium text-gray-800">
                                {{ \App\Models\Order::$statuses[$history->status]['label'] ?? $history->status }}
                            </span>
                            <span class="text-xs text-gray-400">{{ $history->created_at->format('H:i d/m/Y') }}</span>
                            @if($history->createdBy)
                             <span class="text-xs text-gray-400">/ {{ $history->createdBy->name }}</span>
                            @endif
                        </div>
                        @if($history->note)
                        <p class="text-xs text-gray-500 mt-0.5">{{ $history->note }}</p>
                        @endif
                    </div>
                </div>
                @empty
                <p class="text-sm text-gray-400">Chưa có lịch sử.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Ghi chú admin --}}
    <div class="card">
        <div class="card-header">
            <p class="text-sm font-semibold text-gray-800">Ghi chú nội bộ</p>
        </div>
        <form action="{{ route('admin.orders.update-note', $order) }}" method="POST">
            @csrf @method('PUT')
            <div class="card-body">
                <textarea name="admin_note" rows="3" class="form-input resize-none"
                          placeholder="Ghi chú nội bộ (khách không thấy)...">{{ $order->admin_note }}</textarea>
            </div>
            <div class="px-6 py-3 border-t border-gray-100">
                <button type="submit" class="btn-primary text-xs py-1.5">Lưu ghi chú</button>
            </div>
        </form>
    </div>

</div>

{{-- Cột phải --}}
<div class="space-y-4">

    {{-- Cập nhật trạng thái --}}
    <div class="card">
        <div class="card-header">
            <p class="text-sm font-semibold text-gray-800">Trạng thái đơn hàng</p>
        </div>
        <form action="{{ route('admin.orders.update-status', $order) }}" method="POST">
            @csrf @method('PUT')
            <div class="card-body space-y-3">
                <select name="status" class="form-select">
                    @foreach($statuses as $key => $s)
                    <option value="{{ $key }}" {{ $order->status === $key ? 'selected' : '' }}>
                        {{ $s['label'] }}
                    </option>
                    @endforeach
                </select>
                <textarea name="note" rows="2" class="form-input resize-none text-sm"
                          placeholder="Ghi chú thay đổi trạng thái..."></textarea>
                <button type="submit" class="btn-primary w-full justify-center text-sm">
                    Cập nhật trạng thái
                </button>
            </div>
        </form>
    </div>

    {{-- Thanh toán --}}
    <div class="card">
        <div class="card-header">
            <p class="text-sm font-semibold text-gray-800">Thanh toán</p>
        </div>
        <form action="{{ route('admin.orders.update-payment', $order) }}" method="POST">
            @csrf @method('PUT')
            <div class="card-body space-y-3">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">Phương thức</span>
                    <span class="font-medium text-gray-800">{{ strtoupper($order->payment_method) }}</span>
                </div>
                <select name="payment_status" class="form-select">
                    @foreach(\App\Models\Order::$paymentStatuses as $key => $s)
                    <option value="{{ $key }}" {{ $order->payment_status === $key ? 'selected' : '' }}>
                        {{ $s['label'] }}
                    </option>
                    @endforeach
                </select>
                <button type="submit" class="btn-primary w-full justify-center text-sm">
                    Cập nhật thanh toán
                </button>
            </div>
        </form>
    </div>

    {{-- Thông tin khách hàng --}}
    <div class="card">
        <div class="card-header">
            <p class="text-sm font-semibold text-gray-800">Thông tin khách hàng</p>
        </div>
        <div class="card-body space-y-2 text-sm">
            <div class="flex gap-2">
                <i class="fa-solid fa-user text-gray-400 w-4 mt-0.5 flex-shrink-0"></i>
                <span class="text-gray-800 font-medium">{{ $order->customer_name }}</span>
            </div>
            <div class="flex gap-2">
                <i class="fa-solid fa-phone text-gray-400 w-4 mt-0.5 flex-shrink-0"></i>
                <span class="text-gray-700">{{ $order->customer_phone }}</span>
            </div>
            @if($order->customer_email)
            <div class="flex gap-2">
                <i class="fa-solid fa-envelope text-gray-400 w-4 mt-0.5 flex-shrink-0"></i>
                <span class="text-gray-700">{{ $order->customer_email }}</span>
            </div>
            @endif
            <div class="flex gap-2">
                <i class="fa-solid fa-location-dot text-gray-400 w-4 mt-0.5 flex-shrink-0"></i>
                <span class="text-gray-700">{{ $order->shipping_address }}</span>
            </div>
            @if($order->customer_note)
            <div class="mt-2 p-2.5 bg-yellow-50 border border-yellow-200 rounded-lg">
                <p class="text-xs font-semibold text-yellow-700 mb-0.5">Ghi chú của khách</p>
                <p class="text-xs text-yellow-700">{{ $order->customer_note }}</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Chuyển vào thùng rác --}}
    <form action="{{ route('admin.orders.destroy', $order) }}" method="POST"
          onsubmit="return confirm('Chuyển đơn hàng này vào thùng rác?')">
        @csrf @method('DELETE')
        <button type="submit" class="btn-danger w-full justify-center text-sm">
            <i class="fa-solid fa-trash-can text-xs"></i> Chuyển vào thùng rác
        </button>
    </form>

</div>
</div>
@endsection
