@extends('admin.layouts.app')
@section('title', 'Cài đặt hệ thống')
@section('page-title', 'Cài đặt hệ thống')
@section('page-subtitle', 'Quản lý toàn bộ cấu hình website tại một nơi')

@section('content')
@php
$modules = $modules ?? [
    ['group'=>'general',  'icon'=>'fa-solid fa-globe',         'title'=>'Tổng quan',      'description'=>'Tên website, logo, favicon, đơn vị tiền tệ'],
    ['group'=>'seo',      'icon'=>'fa-solid fa-magnifying-glass','title'=>'SEO',           'description'=>'Meta title, description, từ khóa mặc định'],
    ['group'=>'tracking', 'icon'=>'fa-solid fa-chart-line',    'title'=>'Theo dõi',        'description'=>'Google Analytics, Facebook Pixel'],
    ['group'=>'styling',  'icon'=>'fa-solid fa-palette',       'title'=>'Giao diện',       'description'=>'Màu sắc chủ đạo, font chữ'],
    ['group'=>'header',   'icon'=>'fa-solid fa-window-maximize','title'=>'Header',         'description'=>'Topbar, hotline, menu điều hướng'],
    ['group'=>'social',   'icon'=>'fa-solid fa-share-nodes',   'title'=>'Mạng xã hội',     'description'=>'Facebook, Instagram, YouTube, TikTok'],
    ['group'=>'footer',   'icon'=>'fa-solid fa-window-minimize','title'=>'Footer',         'description'=>'Copyright, địa chỉ, thông tin liên hệ'],
];
$colors = ['#2563eb','#16a34a','#ea580c','#7c3aed','#0891b2','#db2777','#64748b'];
@endphp

<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;">
    @foreach($modules as $i => $module)
    @php $color = $colors[$i % count($colors)]; @endphp
    <a href="{{ route('admin.settings.group', $module['group']) }}"
       style="background:#fff;border-radius:14px;border:1.5px solid #f1f5f9;box-shadow:0 1px 4px rgba(0,0,0,.05);padding:20px;display:flex;align-items:flex-start;gap:14px;text-decoration:none;transition:all .15s;"
       onmouseover="this.style.borderColor='#e2e8f0';this.style.boxShadow='0 4px 16px rgba(0,0,0,.08)';this.style.transform='translateY(-2px)'"
       onmouseout="this.style.borderColor='#f1f5f9';this.style.boxShadow='0 1px 4px rgba(0,0,0,.05)';this.style.transform='none'">
        <div style="width:44px;height:44px;border-radius:12px;background:{{ $color }}18;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <i class="{{ $module['icon'] }}" style="color:{{ $color }};font-size:18px;"></i>
        </div>
        <div style="min-width:0;flex:1;">
            <p style="font-size:13.5px;font-weight:700;color:#0f172a;">{{ $module['title'] }}</p>
            <p style="font-size:12px;color:#94a3b8;margin-top:4px;line-height:1.5;">{{ $module['description'] }}</p>
        </div>
        <i class="fa-solid fa-chevron-right" style="color:#cbd5e1;font-size:11px;flex-shrink:0;margin-top:4px;"></i>
    </a>
    @endforeach
</div>
@endsection
