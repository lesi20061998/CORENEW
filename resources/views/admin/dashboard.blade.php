@extends('admin.layouts.app')
@section('title', 'Bảng điều khiển')
@section('page-title', 'Bảng điều khiển')
@section('page-subtitle', 'Xin chào, ' . (auth()->user()->name ?? 'Admin') . '! Đây là báo cáo kinh doanh của bạn.')

@section('content')

{{-- E-commerce Stat Cards --}}
<div class="grid grid-cols-5 gap-4 mb-8">
    @php
    $stats_cards = [
        [
            'label' => 'Doanh thu (6 tháng)',
            'value' => number_format($stats['revenue_6m'], 0, ',', '.') . '₫',
            'icon' => 'fa-money-bill-trend-up',
            'class' => 'bg-emerald-50 text-emerald-600',
        ],
        [
            'label' => 'Doanh thu (3 tháng)',
            'value' => number_format($stats['revenue_3m'], 0, ',', '.') . '₫',
            'icon' => 'fa-chart-line',
            'class' => 'bg-blue-50 text-blue-600',
        ],
        [
            'label' => 'Tháng này',
            'value' => number_format($stats['monthly_revenue'], 0, ',', '.') . '₫',
            'icon' => 'fa-calendar-check',
            'class' => 'bg-indigo-50 text-indigo-600',
        ],
        [
            'label' => 'Đơn hàng',
            'value' => number_format($stats['total_orders']),
            'icon' => 'fa-shopping-bag',
            'class' => 'bg-amber-50 text-amber-600',
        ],
        [
            'label' => 'Khách hàng',
            'value' => number_format($stats['total_customers']),
            'icon' => 'fa-users',
            'class' => 'bg-rose-50 text-rose-600',
        ],
    ];
    @endphp
    @foreach($stats_cards as $sc)
    <div class="card p-5 flex flex-col gap-3 hover:translate-y-[-2px] transition-all duration-300 group">
        <div class="w-10 h-10 rounded-xl {{ $sc['class'] }} flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
            <i class="fa-solid {{ $sc['icon'] }} text-lg"></i>
        </div>
        <div>
            <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">{{ $sc['label'] }}</p>
            <p class="text-lg font-black text-slate-800 leading-none tracking-tight">{{ $sc['value'] }}</p>
        </div>
    </div>
    @endforeach
</div>

<div class="grid grid-cols-12 gap-6">
    {{-- High Level Insights (60%) --}}
    <div class="col-span-8 flex flex-col gap-6">
        {{-- Revenue Chart --}}
        <div class="card p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] flex items-center gap-3">
                    <span class="w-6 h-[2px] bg-emerald-500 rounded-full"></span>
                    Biểu đồ doanh thu hàng tháng
                </h3>
            </div>
            <div class="h-[220px] w-full relative">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        {{-- Top Selling Products --}}
        <div class="card p-0 overflow-hidden">
            <div class="p-6 pb-3 border-b border-slate-50 flex items-center justify-between">
                <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] flex items-center gap-3">
                    <span class="w-6 h-[2px] bg-blue-500 rounded-full"></span>
                    Sản phẩm bán chạy nhất
                </h3>
                <span class="text-[8px] font-black text-blue-600 border border-blue-100 px-2 py-0.5 rounded uppercase">Theo số lượng</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50/30">
                            <th class="px-6 py-3 text-[9px] font-black text-slate-400 uppercase">Sản phẩm</th>
                            <th class="px-6 py-3 text-[9px] font-black text-slate-400 uppercase text-center">Đã bán</th>
                            <th class="px-6 py-3 text-[9px] font-black text-slate-400 uppercase text-right">Doanh thu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($stats['top_selling'] as $p)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-3">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $p->image_url }}" class="w-8 h-8 rounded-lg object-cover border border-slate-100">
                                    <span class="text-[11px] font-bold text-slate-700 truncate max-w-[200px]">{{ $p->product_name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-3 text-center">
                                <span class="bg-emerald-50 text-emerald-600 px-2 py-0.5 rounded-full text-[10px] font-black">{{ $p->total_qty }}</span>
                            </td>
                            <td class="px-6 py-3 text-right font-black text-[11px] text-slate-800">
                                {{ number_format($p->revenue, 0, ',', '.') }}₫
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Side Reports (40%) --}}
    <div class="col-span-4 flex flex-col gap-6">
        {{-- Slow Moving Products --}}
        <div class="card p-0 overflow-hidden">
            <div class="p-6 pb-3 border-b border-slate-100 bg-rose-50/30">
                <h3 class="text-[10px] font-black text-rose-600 uppercase tracking-[0.2em] flex items-center gap-3">
                    <span class="w-6 h-[2px] bg-rose-500 rounded-full"></span>
                    Sản phẩm chưa bán được
                </h3>
            </div>
            <div class="p-4 space-y-3">
                @forelse($stats['low_selling'] as $lp)
                <div class="flex items-center justify-between p-3 rounded-2xl border border-slate-50 hover:bg-slate-50 transition-all group">
                    <div class="flex items-center gap-3">
                        <img src="{{ $lp->thumbnail_url ?: asset('theme/images/no-image.png') }}" class="w-8 h-8 rounded-lg object-cover grayscale opacity-60 group-hover:grayscale-0 group-hover:opacity-100 transition-all">
                        <div class="flex flex-col">
                            <span class="text-[10px] font-bold text-slate-700 truncate max-w-[120px]">{{ $lp->name }}</span>
                            <span class="text-[8px] text-slate-400 uppercase tracking-widest">{{ $lp->sku ?? 'NO SKU' }}</span>
                        </div>
                    </div>
                    <a href="{{ route('admin.products.edit', $lp) }}" class="w-6 h-6 flex items-center justify-center rounded-lg bg-white border border-slate-100 text-slate-400 hover:text-blue-600 transition-colors">
                        <i class="fa-solid fa-pen text-[10px]"></i>
                    </a>
                </div>
                @empty
                <p class="text-center py-4 text-[10px] text-slate-400 italic">Mọi sản phẩm đều đang bán tốt!</p>
                @endforelse
                <div class="pt-2">
                    <p class="text-[9px] text-rose-500 font-bold italic leading-relaxed">
                        <i class="fa-solid fa-circle-exclamation me-1"></i> Gợi ý: Hãy kiểm tra lại mô tả sản phẩm hoặc chạy chiến dịch Flash Sale cho những mặt hàng này.
                    </p>
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="card p-6">
            <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6">Thao tác quản trị</h3>
            <div class="grid grid-cols-2 gap-3">
                <a href="{{ route('admin.products.create') }}" class="flex flex-col items-center gap-2 p-4 rounded-2xl border border-slate-100 hover:bg-emerald-50 transition-all group">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:bg-emerald-600 group-hover:text-white transition-all">
                        <i class="fa-solid fa-plus text-sm"></i>
                    </div>
                    <span class="text-[8px] font-black text-slate-600 uppercase tracking-widest text-center">Thêm sản phẩm</span>
                </a>
                <a href="{{ route('admin.orders.index') }}" class="flex flex-col items-center gap-2 p-4 rounded-2xl border border-slate-100 hover:bg-blue-50 transition-all group">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-all">
                        <i class="fa-solid fa-cart-shopping text-sm"></i>
                    </div>
                    <span class="text-[8px] font-black text-slate-600 uppercase tracking-widest text-center">QL Đơn hàng</span>
                </a>
            </div>
        </div>

        {{-- Recent Orders Small List --}}
        <div class="card p-5">
            <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Hoạt động gần đây</h4>
            <div class="space-y-3" id="recent-activity-list">
                @foreach($stats['recent_orders'] as $ro)
                <div class="flex items-center justify-between anim-new-order">
                    <div class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-400"></span>
                        <span class="text-[10px] font-bold">#{{ $ro->order_number }}</span>
                    </div>
                    <span class="text-[9px] text-slate-400">{{ $ro->created_at->diffForHumans() }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('revenueChart').getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 220);
        gradient.addColorStop(0, 'rgba(16, 185, 129, 0.15)');
        gradient.addColorStop(1, 'rgba(16, 185, 129, 0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($stats['revenue_chart']->pluck('month')) !!},
                datasets: [{
                    label: 'Doanh thu',
                    data: {!! json_encode($stats['revenue_chart']->pluck('revenue')) !!},
                    borderColor: '#10b981',
                    borderWidth: 2,
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#10b981',
                    pointBorderWidth: 1.5,
                    pointRadius: 3,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        titleFont: { size: 9, weight: 'bold' },
                        bodyFont: { size: 11, weight: '900' },
                        padding: 8,
                        cornerRadius: 8,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(context.raw);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { display: true, color: '#f1f5f9', borderDash: [5, 5] },
                        ticks: {
                            font: { size: 8, weight: '700' },
                            color: '#94a3b8',
                            callback: function(value) {
                                if (value >= 1000000) return (value / 1000000) + 'M';
                                if (value >= 1000) return (value / 1000) + 'K';
                                return value;
                            }
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 8, weight: '700' }, color: '#94a3b8' }
                    }
                }
            }
        });
    });
</script>
@endpush
