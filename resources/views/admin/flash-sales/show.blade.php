@extends('admin.layouts.app')
@section('title', $campaign->name)
@section('page-title', $campaign->name)
@section('page-subtitle', 'Chi tiết chiến dịch flash sale')
@section('page-actions')
    <a href="{{ route('admin.flash-sales.edit', $campaign) }}" class="btn btn-primary">
        <i class="fa-solid fa-pen"></i> Chỉnh sửa
    </a>
    <a href="{{ route('admin.flash-sales.index') }}" class="btn btn-secondary">
        <i class="fa-solid fa-arrow-left"></i> Quay lại
    </a>
@endsection

@section('content')

@if(session('success'))
<div class="alert alert-success mb-4">{{ session('success') }}</div>
@endif

<div style="display:grid;grid-template-columns:1fr 300px;gap:20px;align-items:start;">

    {{-- Items table --}}
    <div class="card" style="overflow:hidden;">
        <div class="card-header">
            <p class="card-title">Danh sách sản phẩm / danh mục</p>
        </div>
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="tbl-th">Loại</th>
                    <th class="tbl-th">Tên</th>
                    <th class="tbl-th">Giảm giá</th>
                    <th class="tbl-th">Đã bán / Giới hạn</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($campaign->items as $item)
                <tr>
                    <td class="tbl-td">
                        @if($item->product_id)
                        <span style="background:#dbeafe;color:#1d4ed8;font-size:11px;font-weight:600;padding:2px 8px;border-radius:20px;">
                            <i class="fa-solid fa-box mr-1"></i>Sản phẩm
                        </span>
                        @else
                        <span style="background:#ede9fe;color:#7c3aed;font-size:11px;font-weight:600;padding:2px 8px;border-radius:20px;">
                            <i class="fa-solid fa-folder mr-1"></i>Danh mục
                        </span>
                        @endif
                    </td>
                    <td class="tbl-td">
                        @if($item->product_id)
                        <div style="display:flex;align-items:center;gap:8px;">
                            @if($item->product?->image)
                            <img src="{{ $item->product->image }}" style="width:32px;height:32px;object-fit:cover;border-radius:6px;">
                            @endif
                            <div>
                                <p class="text-sm font-medium text-gray-800">{{ $item->product?->name ?? '—' }}</p>
                                <p class="text-xs text-gray-400">Giá gốc: {{ number_format($item->product?->price ?? 0, 0, ',', '.') }}₫</p>
                            </div>
                        </div>
                        @else
                        <p class="text-sm font-medium text-gray-800">{{ $item->category?->name ?? '—' }}</p>
                        @endif
                    </td>
                    <td class="tbl-td">
                        @if($item->discount_type === 'percent')
                        <span style="background:#fef9c3;color:#a16207;font-size:13px;font-weight:700;padding:2px 10px;border-radius:20px;">
                            -{{ $item->discount_value }}%
                        </span>
                        @else
                        <span style="background:#fef9c3;color:#a16207;font-size:13px;font-weight:700;padding:2px 10px;border-radius:20px;">
                            -{{ number_format($item->discount_value, 0, ',', '.') }}₫
                        </span>
                        @endif
                        @if($item->product_id && $item->product)
                        @php $flashPrice = $item->calcFlashPrice($item->product->price); @endphp
                        <p class="text-xs text-green-600 mt-0.5">→ {{ number_format($flashPrice, 0, ',', '.') }}₫</p>
                        @endif
                    </td>
                    <td class="tbl-td">
                        @if($item->sale_limit)
                        <div style="display:flex;align-items:center;gap:6px;">
                            <div style="flex:1;background:#f1f5f9;border-radius:20px;height:6px;overflow:hidden;">
                                <div style="background:#f97316;height:100%;border-radius:20px;width:{{ min(100, ($item->sold_count / $item->sale_limit) * 100) }}%"></div>
                            </div>
                            <span class="text-xs text-gray-500">{{ $item->sold_count }}/{{ $item->sale_limit }}</span>
                        </div>
                        @else
                        <span class="text-xs text-gray-400">Không giới hạn</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="tbl-td text-center text-gray-400 py-8">Chưa có item nào</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Right sidebar --}}
    <div style="display:flex;flex-direction:column;gap:16px;">

        <div class="card">
            <div class="card-header"><p class="card-title">Thông tin</p></div>
            <div class="card-body" style="display:flex;flex-direction:column;gap:10px;font-size:13px;">
                <div>
                    <p style="color:#64748b;font-size:11px;font-weight:600;text-transform:uppercase;margin-bottom:2px;">Trạng thái</p>
                    @php $color = \App\Models\FlashSaleCampaign::$statuses[$campaign->status]['color'] ?? 'gray'; @endphp
                    <span class="badge-{{ $color }}">{{ $campaign->status_label }}</span>
                    @if($campaign->is_running)
                    <span style="background:#dcfce7;color:#16a34a;font-size:10px;font-weight:700;padding:1px 6px;border-radius:20px;margin-left:4px;">
                        <i class="fa-solid fa-bolt"></i> LIVE
                    </span>
                    @endif
                </div>
                <div>
                    <p style="color:#64748b;font-size:11px;font-weight:600;text-transform:uppercase;margin-bottom:2px;">Bắt đầu</p>
                    <p class="text-gray-800">{{ $campaign->starts_at->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <p style="color:#64748b;font-size:11px;font-weight:600;text-transform:uppercase;margin-bottom:2px;">Kết thúc</p>
                    <p class="text-gray-800">{{ $campaign->ends_at->format('d/m/Y H:i') }}</p>
                </div>
                @if($campaign->description)
                <div>
                    <p style="color:#64748b;font-size:11px;font-weight:600;text-transform:uppercase;margin-bottom:2px;">Mô tả</p>
                    <p class="text-gray-600">{{ $campaign->description }}</p>
                </div>
                @endif
                <div>
                    <p style="color:#64748b;font-size:11px;font-weight:600;text-transform:uppercase;margin-bottom:2px;">Tổng items</p>
                    <p class="text-gray-800 font-semibold">{{ $campaign->items->count() }} items</p>
                </div>
            </div>
        </div>

        {{-- Countdown nếu đang chạy --}}
        @if($campaign->is_running)
        <div class="card" style="background:linear-gradient(135deg,#ff6b35,#f7c59f);border:none;">
            <div class="card-body" style="text-align:center;color:#fff;">
                <p style="font-size:12px;font-weight:700;text-transform:uppercase;opacity:.8;margin-bottom:6px;">
                    <i class="fa-solid fa-bolt"></i> Kết thúc sau
                </p>
                <div id="countdown" style="font-size:24px;font-weight:800;letter-spacing:2px;">--:--:--</div>
            </div>
        </div>
        @endif

        <form method="POST" action="{{ route('admin.flash-sales.destroy', $campaign) }}"
              onsubmit="return confirm('Xóa chiến dịch này? Hành động không thể hoàn tác.')">
            @csrf @method('DELETE')
            <button type="submit" class="btn w-full" style="background:#fee2e2;color:#dc2626;border:1px solid #fca5a5;">
                <i class="fa-solid fa-trash"></i> Xóa chiến dịch
            </button>
        </form>

    </div>
</div>

@if($campaign->is_running)
@push('scripts')
<script>
const endsAt = new Date('{{ $campaign->ends_at->toIso8601String() }}');
function updateCountdown() {
    const diff = endsAt - new Date();
    if (diff <= 0) { document.getElementById('countdown').textContent = 'Đã kết thúc'; return; }
    const h = Math.floor(diff / 3600000);
    const m = Math.floor((diff % 3600000) / 60000);
    const s = Math.floor((diff % 60000) / 1000);
    document.getElementById('countdown').textContent =
        String(h).padStart(2,'0') + ':' + String(m).padStart(2,'0') + ':' + String(s).padStart(2,'0');
}
updateCountdown();
setInterval(updateCountdown, 1000);
</script>
@endpush
@endif
@endsection
