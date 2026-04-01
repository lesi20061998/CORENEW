@extends('admin.layouts.app')
@section('title', 'Bảng điều khiển')
@section('page-title', 'Bảng điều khiển')
@section('page-subtitle', 'Xin chào, ' . (auth()->user()->name ?? 'Admin') . '! Đây là tổng quan hệ thống.')

@section('content')

{{-- Stat cards --}}
<div class="grid grid-cols-4 gap-8 mb-10">
    @php
    $stats_cards = [
        ['label'=>'Tổng sản phẩm',    'value'=>$stats['total_products'],        'icon'=>'fa-box',          'class'=>'bg-blue-50 text-blue-600',   'trend'=>'+12%'],
        ['label'=>'Đang hiển thị',    'value'=>$stats['active_products'],       'icon'=>'fa-circle-check', 'class'=>'bg-green-50 text-green-600', 'trend'=>'+5%'],
        ['label'=>'Tổng thuộc tính',  'value'=>$stats['total_attributes'],      'icon'=>'fa-tags',         'class'=>'bg-purple-50 text-purple-600','trend'=>''],
        ['label'=>'Bộ lọc hoạt động', 'value'=>$stats['filterable_attributes'], 'icon'=>'fa-filter',       'class'=>'bg-orange-50 text-orange-600','trend'=>''],
    ];
    @endphp
    @foreach($stats_cards as $sc)
    <div class="card p-8 flex items-center gap-6 hover:translate-y-[-4px] transition-all duration-300 group">
        <div class="w-16 h-16 rounded-[20px] {{ $sc['class'] }} flex items-center justify-center shrink-0 shadow-lg shadow-current/10 group-hover:scale-110 transition-transform">
            <i class="fa-solid {{ $sc['icon'] }} text-2xl"></i>
        </div>
        <div>
            <p class="text-3xl font-black text-slate-900 leading-none tracking-tighter">{{ $sc['value'] }}</p>
            <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest mt-2">{{ $sc['label'] }}</p>
        </div>
    </div>
    @endforeach
</div>

<div class="grid grid-cols-12 gap-8">
    {{-- Quick actions --}}
    <div class="col-span-8">
        <div class="card p-10">
            <h3 class="text-[12px] font-black text-slate-400 uppercase tracking-[0.2em] mb-8 flex items-center gap-3">
                <span class="w-8 h-[2px] bg-blue-600 rounded-full"></span>
                Thao tác quản trị nhanh
            </h3>
            <div class="grid grid-cols-4 gap-6">
                @php
                $quick = [
                    ['href'=>route('admin.products.create'),'icon'=>'fa-plus',   'label'=>'Thêm sản phẩm', 'color'=>'blue'],
                    ['href'=>route('admin.posts.create'),   'icon'=>'fa-pen-nib', 'label'=>'Viết bài mới',  'color'=>'green'],
                    ['href'=>route('admin.media.index'),    'icon'=>'fa-images', 'label'=>'Thư viện ảnh',  'color'=>'purple'],
                    ['href'=>route('admin.settings.index'), 'icon'=>'fa-gears',  'label'=>'Cấu hình hệ thống','color'=>'orange'],
                ];
                @endphp
                @foreach($quick as $q)
                <a href="{{ $q['href'] }}"
                   class="flex flex-col items-center gap-4 p-6 rounded-[30px] border-2 border-slate-50 hover:border-{{ $q['color'] }}-100 hover:bg-{{ $q['color'] }}-50/30 transition-all group">
                    <div class="w-14 h-14 rounded-2xl bg-white shadow-xl shadow-slate-200/50 flex items-center justify-center group-hover:bg-{{ $q['color'] }}-600 group-hover:text-white transition-all">
                        <i class="fa-solid {{ $q['icon'] }} text-lg"></i>
                    </div>
                    <span class="text-xs font-black text-slate-600 uppercase tracking-widest text-center">{{ $q['label'] }}</span>
                </a>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Shortcuts --}}
    <div class="col-span-4 space-y-6">
        @php
        $links = [
            ['href'=>route('admin.products.index'), 'icon'=>'fa-box',        'label'=>'Quản lý kho hàng', 'sub'=>$stats['total_products'].' sản phẩm', 'color'=>'blue'],
            ['href'=>route('admin.posts.index'),    'icon'=>'fa-newspaper',  'label'=>'Tin tức & Blog',   'sub'=>'Nội dung website', 'color'=>'green'],
            ['href'=>route('admin.widgets.index'),  'icon'=>'fa-puzzle-piece','label'=>'Widget Giao diện', 'sub'=>'Kéo thả layout', 'color'=>'purple'],
        ];
        @endphp
        @foreach($links as $lk)
        <a href="{{ $lk['href'] }}"
           class="card p-6 flex items-center gap-5 hover:translate-x-2 transition-all group">
            <div class="w-12 h-12 rounded-2xl bg-{{ $lk['color'] }}-50 text-{{ $lk['color'] }}-600 flex items-center justify-center shrink-0">
                <i class="fa-solid {{ $lk['icon'] }} text-lg"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-[14px] font-black text-slate-900 uppercase tracking-tighter">{{ $lk['label'] }}</p>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">{{ $lk['sub'] }}</p>
            </div>
            <i class="fa-solid fa-chevron-right text-[10px] text-slate-200 group-hover:text-{{ $lk['color'] }}-600 transition-colors"></i>
        </a>
        @endforeach
    </div>
</div>

@endsection
