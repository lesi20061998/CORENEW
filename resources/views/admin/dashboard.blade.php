@extends('admin.layouts.app')
@section('title', 'Bảng điều khiển')
@section('page-title', 'Bảng điều khiển')
@section('page-subtitle', 'Xin chào, ' . (auth()->user()->name ?? 'Admin') . '! Đây là tổng quan hệ thống.')

@section('content')

{{-- Stat cards --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:24px;">
@php
$stats_cards = [
    ['label'=>'Tổng sản phẩm',    'value'=>$stats['total_products'],        'icon'=>'fa-box',          'bg'=>'#eff6ff','icon_bg'=>'#2563eb','icon_color'=>'#fff','trend'=>'+12%'],
    ['label'=>'Đang bán',          'value'=>$stats['active_products'],       'icon'=>'fa-circle-check', 'bg'=>'#f0fdf4','icon_bg'=>'#16a34a','icon_color'=>'#fff','trend'=>'+5%'],
    ['label'=>'Thuộc tính',        'value'=>$stats['total_attributes'],      'icon'=>'fa-tags',         'bg'=>'#faf5ff','icon_bg'=>'#7c3aed','icon_color'=>'#fff','trend'=>''],
    ['label'=>'Có thể lọc',        'value'=>$stats['filterable_attributes'], 'icon'=>'fa-filter',       'bg'=>'#fff7ed','icon_bg'=>'#ea580c','icon_color'=>'#fff','trend'=>''],
];
@endphp
@foreach($stats_cards as $sc)
<div style="background:#fff;border-radius:14px;border:1.5px solid #f1f5f9;box-shadow:0 1px 4px rgba(0,0,0,.05);padding:20px;display:flex;align-items:center;gap:16px;">
    <div style="width:48px;height:48px;border-radius:12px;background:{{ $sc['icon_bg'] }};display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 4px 12px rgba(0,0,0,.12);">
        <i class="fa-solid {{ $sc['icon'] }}" style="color:{{ $sc['icon_color'] }};font-size:18px;"></i>
    </div>
    <div>
        <p style="font-size:26px;font-weight:800;color:#0f172a;line-height:1;">{{ $sc['value'] }}</p>
        <p style="font-size:12px;color:#64748b;margin-top:3px;">{{ $sc['label'] }}</p>
    </div>
</div>
@endforeach
</div>

{{-- Quick actions --}}
<div style="background:#fff;border-radius:14px;border:1.5px solid #f1f5f9;box-shadow:0 1px 4px rgba(0,0,0,.05);padding:20px;margin-bottom:24px;">
    <p style="font-size:13px;font-weight:700;color:#374151;margin-bottom:14px;">Thao tác nhanh</p>
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:12px;">
    @php
    $quick = [
        ['href'=>route('admin.products.create'),'icon'=>'fa-plus',   'label'=>'Thêm sản phẩm', 'bg'=>'#eff6ff','ic'=>'#2563eb'],
        ['href'=>route('admin.posts.create'),   'icon'=>'fa-pen',    'label'=>'Viết bài mới',  'bg'=>'#f0fdf4','ic'=>'#16a34a'],
        ['href'=>route('admin.media.index'),    'icon'=>'fa-images', 'label'=>'Thư viện Media','bg'=>'#faf5ff','ic'=>'#7c3aed'],
        ['href'=>route('admin.settings.index'), 'icon'=>'fa-gear',   'label'=>'Cài đặt',       'bg'=>'#fff7ed','ic'=>'#ea580c'],
    ];
    @endphp
    @foreach($quick as $q)
    <a href="{{ $q['href'] }}"
       style="display:flex;flex-direction:column;align-items:center;gap:10px;padding:16px 12px;border-radius:12px;border:1.5px solid #f1f5f9;text-decoration:none;transition:all .15s;"
       onmouseover="this.style.borderColor='#e2e8f0';this.style.background='#f8fafc';this.style.transform='translateY(-1px)'"
       onmouseout="this.style.borderColor='#f1f5f9';this.style.background='#fff';this.style.transform='none'">
        <div style="width:42px;height:42px;border-radius:10px;background:{{ $q['bg'] }};display:flex;align-items:center;justify-content:center;">
            <i class="fa-solid {{ $q['icon'] }}" style="color:{{ $q['ic'] }};font-size:16px;"></i>
        </div>
        <span style="font-size:12.5px;font-weight:600;color:#374151;text-align:center;">{{ $q['label'] }}</span>
    </a>
    @endforeach
    </div>
</div>

{{-- Quick links --}}
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;">
@php
$links = [
    ['href'=>route('admin.products.index'), 'icon'=>'fa-box',        'label'=>'Quản lý sản phẩm', 'sub'=>$stats['total_products'].' sản phẩm', 'ic'=>'#2563eb','bg'=>'#eff6ff'],
    ['href'=>route('admin.posts.index'),    'icon'=>'fa-newspaper',  'label'=>'Quản lý bài viết', 'sub'=>'Tin tức & blog',                     'ic'=>'#16a34a','bg'=>'#f0fdf4'],
    ['href'=>route('admin.widgets.index'),  'icon'=>'fa-puzzle-piece','label'=>'Widget trang chủ','sub'=>'Kéo thả giao diện',                  'ic'=>'#7c3aed','bg'=>'#faf5ff'],
];
@endphp
@foreach($links as $lk)
<a href="{{ $lk['href'] }}"
   style="background:#fff;border-radius:14px;border:1.5px solid #f1f5f9;box-shadow:0 1px 4px rgba(0,0,0,.05);padding:18px 20px;display:flex;align-items:center;gap:14px;text-decoration:none;transition:all .15s;"
   onmouseover="this.style.borderColor='#e2e8f0';this.style.boxShadow='0 4px 12px rgba(0,0,0,.08)';this.style.transform='translateY(-1px)'"
   onmouseout="this.style.borderColor='#f1f5f9';this.style.boxShadow='0 1px 4px rgba(0,0,0,.05)';this.style.transform='none'">
    <div style="width:42px;height:42px;border-radius:10px;background:{{ $lk['bg'] }};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
        <i class="fa-solid {{ $lk['icon'] }}" style="color:{{ $lk['ic'] }};font-size:16px;"></i>
    </div>
    <div style="flex:1;min-width:0;">
        <p style="font-size:13.5px;font-weight:600;color:#0f172a;">{{ $lk['label'] }}</p>
        <p style="font-size:12px;color:#94a3b8;margin-top:2px;">{{ $lk['sub'] }}</p>
    </div>
    <i class="fa-solid fa-chevron-right" style="color:#cbd5e1;font-size:11px;flex-shrink:0;"></i>
</a>
@endforeach
</div>

@endsection
