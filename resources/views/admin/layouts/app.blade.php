<!DOCTYPE html>
<html lang="vi" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Quản trị') | {{ get_setting('site_name', 'Kalles Store') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { height: 100%; }
        body { font-family: 'Inter', 'Segoe UI', system-ui, sans-serif; font-size: 14px; background: #f0f2f5; color: #1a202c; }

        /* ════════════════════════════════
           SIDEBAR
        ════════════════════════════════ */
        #sidebar {
            width: 260px;
            min-width: 260px;
            background: #0f172a;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            overflow-x: hidden;
            flex-shrink: 0;
        }
        #sidebar::-webkit-scrollbar { width: 3px; }
        #sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,.08); }

        .sb-logo {
            padding: 20px 18px;
            border-bottom: 1px solid rgba(255,255,255,.06);
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .sb-logo-icon {
            width: 42px; height: 42px;
            border-radius: 12px;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 6px 20px rgba(59,130,246,.4);
        }
        .sb-logo-icon i { color: #fff; font-size: 16px; }
        .sb-logo-text { min-width: 0; }
        .sb-logo-text p { font-size: 15px; font-weight: 700; color: #fff; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .sb-logo-text span { font-size: 11px; color: #475569; margin-top: 2px; display: block; }

        /* Nav items */
        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 18px;
            font-size: 14px;
            font-weight: 500;
            color: #94a3b8;
            cursor: pointer;
            transition: all .15s;
            border: none;
            width: 100%;
            text-align: left;
            text-decoration: none;
            background: transparent;
            position: relative;
        }
        .nav-item:hover { background: rgba(255,255,255,.04); color: #e2e8f0; }
        .nav-item.active {
            color: #60a5fa;
            background: rgba(96,165,250,.08);
        }
        .nav-item.active::before {
            content: '';
            position: absolute;
            left: 0; top: 6px; bottom: 6px;
            width: 3px;
            background: #3b82f6;
            border-radius: 0 3px 3px 0;
        }
        .nav-item.group-open { color: #fff; }

        .nav-icon {
            width: 36px; height: 36px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            font-size: 15px;
            background: rgba(255,255,255,.06);
            color: #64748b;
            transition: all .15s;
        }
        .nav-item:hover .nav-icon { color: #94a3b8; }
        .nav-item.active .nav-icon { background: rgba(59,130,246,.2); color: #60a5fa; }
        .nav-item.group-open .nav-icon { background: rgba(255,255,255,.1); color: #e2e8f0; }

        /* Colored group icons */
        .nav-item.grp-shop.group-open .nav-icon    { background: rgba(245,158,11,.2); color: #fbbf24; }
        .nav-item.grp-content.group-open .nav-icon { background: rgba(16,185,129,.2); color: #34d399; }
        .nav-item.grp-media.group-open .nav-icon   { background: rgba(139,92,246,.2); color: #a78bfa; }
        .nav-item.grp-system.active .nav-icon      { background: rgba(239,68,68,.15); color: #f87171; }

        .nav-chevron {
            margin-left: auto;
            font-size: 11px;
            color: #334155;
            transition: transform .2s;
        }
        .nav-chevron.open { transform: rotate(180deg); color: #64748b; }

        /* Sub menu */
        .sub-menu { background: rgba(0,0,0,.2); overflow: hidden; }
        .sub-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 18px 9px 66px;
            font-size: 13.5px;
            font-weight: 400;
            color: #64748b;
            text-decoration: none;
            transition: all .15s;
        }
        .sub-item:hover { color: #cbd5e1; background: rgba(255,255,255,.03); }
        .sub-item.active {
            color: #93c5fd;
            font-weight: 600;
            background: rgba(96,165,250,.06);
        }
        .sub-item .dot {
            width: 5px; height: 5px;
            border-radius: 50%;
            background: #334155;
            flex-shrink: 0;
            transition: background .15s;
        }
        .sub-item.active .dot { background: #3b82f6; }
        .sub-item:hover .dot { background: #64748b; }

        .nav-sep {
            height: 1px;
            background: rgba(255,255,255,.05);
            margin: 6px 18px;
        }
        .nav-label {
            padding: 14px 18px 6px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .1em;
            color: #1e3a5f;
        }

        /* ════════════════════════════════
           TOPBAR
        ════════════════════════════════ */
        #topbar {
            background: #fff;
            border-bottom: 1px solid #e8ecf0;
            padding: 0 28px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-shrink: 0;
            box-shadow: 0 1px 3px rgba(0,0,0,.05);
        }
        .topbar-title { font-size: 18px; font-weight: 700; color: #0f172a; }
        .topbar-sub { font-size: 12.5px; color: #94a3b8; margin-top: 1px; }

        /* ════════════════════════════════
           REUSABLE COMPONENTS
        ════════════════════════════════ */
        .btn {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 9px 18px; border-radius: 9px; border: none;
            font-size: 13.5px; font-weight: 600; cursor: pointer;
            transition: all .15s; text-decoration: none; white-space: nowrap;
            font-family: inherit;
        }
        .btn-primary { background: #2563eb; color: #fff; }
        .btn-primary:hover { background: #1d4ed8; box-shadow: 0 4px 12px rgba(37,99,235,.35); }
        .btn-secondary { background: #f1f5f9; color: #475569; }
        .btn-secondary:hover { background: #e2e8f0; }
        .btn-danger { background: #dc2626; color: #fff; }
        .btn-danger:hover { background: #b91c1c; }
        .btn-ghost { background: transparent; color: #64748b; border: 1.5px solid #e2e8f0; }
        .btn-ghost:hover { background: #f8fafc; border-color: #cbd5e1; }
        .btn-sm { padding: 6px 12px; font-size: 12.5px; border-radius: 7px; }

        .form-input, .form-select, .form-textarea {
            width: 100%;
            border: 1.5px solid #e2e8f0;
            border-radius: 9px;
            padding: 10px 14px;
            font-size: 14px;
            color: #1e293b;
            outline: none;
            transition: border-color .15s, box-shadow .15s;
            background: #fff;
            font-family: inherit;
        }
        .form-input:focus, .form-select:focus, .form-textarea:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59,130,246,.1);
        }
        .form-input.error { border-color: #f87171; }
        .form-select { cursor: pointer; }
        .form-textarea { resize: vertical; }
        .form-label { display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 7px; }
        .form-hint { font-size: 12px; color: #94a3b8; margin-top: 5px; }

        .card {
            background: #fff;
            border-radius: 16px;
            border: 1px solid #e8ecf0;
            box-shadow: 0 1px 4px rgba(0,0,0,.04);
        }
        .card-header {
            padding: 18px 22px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .card-title { font-size: 14px; font-weight: 700; color: #0f172a; }
        .card-body { padding: 22px; }

        .badge {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 4px 10px; border-radius: 20px;
            font-size: 12px; font-weight: 600;
        }
        .badge-green  { background: #dcfce7; color: #15803d; }
        .badge-yellow { background: #fef9c3; color: #a16207; }
        .badge-red    { background: #fee2e2; color: #b91c1c; }
        .badge-blue   { background: #dbeafe; color: #1d4ed8; }
        .badge-gray   { background: #f1f5f9; color: #64748b; }
        .badge-purple { background: #ede9fe; color: #6d28d9; }
        .badge-orange { background: #ffedd5; color: #c2410c; }

        .tbl-wrap { overflow: hidden; border-radius: 16px; border: 1px solid #e8ecf0; background: #fff; box-shadow: 0 1px 4px rgba(0,0,0,.04); }
        .tbl-head { background: #f8fafc; border-bottom: 1px solid #f1f5f9; }
        .tbl-th { padding: 13px 18px; font-size: 11.5px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: .06em; text-align: left; }
        .tbl-td { padding: 15px 18px; font-size: 14px; vertical-align: middle; border-bottom: 1px solid #f8fafc; }
        .tbl-tr:last-child .tbl-td { border-bottom: none; }
        .tbl-tr:hover .tbl-td { background: #fafbfc; }

        .act-btn {
            width: 32px; height: 32px; border-radius: 8px; border: none;
            display: inline-flex; align-items: center; justify-content: center;
            cursor: pointer; transition: all .15s; color: #94a3b8;
            background: transparent; text-decoration: none; font-size: 13px;
        }
        .act-btn:hover { background: #f1f5f9; color: #475569; }
        .act-btn.edit:hover { background: #dbeafe; color: #2563eb; }
        .act-btn.view:hover { background: #dcfce7; color: #16a34a; }
        .act-btn.del:hover  { background: #fee2e2; color: #dc2626; }

        /* Flash */
        .flash { display: flex; align-items: center; gap: 12px; padding: 13px 18px; border-radius: 10px; font-size: 14px; }
        .flash-success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #15803d; }
        .flash-error   { background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; }

        [x-cloak] { display: none !important; }

        /* Scrollbar main */
        #main-scroll::-webkit-scrollbar { width: 6px; }
        #main-scroll::-webkit-scrollbar-track { background: transparent; }
        #main-scroll::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 6px; }
    </style>
    <style>
        .seo-tab{display:flex;align-items:center;gap:6px;padding:10px 14px;font-size:12.5px;font-weight:500;color:#6b7280;border:none;background:none;cursor:pointer;border-bottom:2px solid transparent;transition:all .15s;white-space:nowrap}
        .seo-tab:hover{color:#374151;background:#f9fafb}
        .seo-tab.active{color:#2563eb;border-bottom-color:#2563eb;background:#fff}
        .seo-tab i{font-size:11px}
        .seo-check-item{display:flex;align-items:flex-start;gap:8px;padding:5px 0;font-size:13px;color:#374151;line-height:1.5}
        .seo-check-icon{flex-shrink:0;width:18px;height:18px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:10px;margin-top:1px}
        .seo-check-icon.fail{background:#fee2e2;color:#ef4444}
        .seo-check-icon.pass{background:#dcfce7;color:#16a34a}
    </style>
    @stack('styles')
</head>
<body>

@php
$inShop     = request()->routeIs('admin.products.*') || request()->routeIs('admin.attributes.*') || (request()->routeIs('admin.categories.*') && request()->get('type','product') === 'product');
$inContent  = request()->routeIs('admin.posts.*') || request()->routeIs('admin.pages.*') || (request()->routeIs('admin.categories.*') && request()->get('type') === 'post');
$inMedia    = request()->routeIs('admin.media.*') || request()->routeIs('admin.widgets.*');
$inSettings = request()->routeIs('admin.settings.*') || request()->routeIs('admin.languages.*') || request()->routeIs('admin.translations.*');
@endphp

<div style="display:flex;height:100vh;overflow:hidden;"
     x-data="{ open: '{{ $inShop ? 'shop' : ($inContent ? 'content' : ($inMedia ? 'media' : ($inSettings ? 'settings' : ''))) }}' }">

    {{-- ══════════════════════════════════════════
         SIDEBAR
    ══════════════════════════════════════════ --}}
    <aside id="sidebar">

        {{-- Logo --}}
        <div class="sb-logo">
            <div class="sb-logo-icon">
                <i class="fa-solid fa-store"></i>
            </div>
            <div class="sb-logo-text">
                <p>{{ get_setting('site_name', 'Kalles Store') }}</p>
                <span>Trang quản trị</span>
            </div>
        </div>

        {{-- Navigation --}}
        <nav style="flex:1;padding:10px 0;">

            <p class="nav-label">Tổng quan</p>

            <a href="{{ route('admin.dashboard') }}"
               class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-gauge-high"></i></span>
                Dashboard
            </a>

            <div class="nav-sep"></div>
            <p class="nav-label">Cửa hàng</p>

            {{-- Sản phẩm --}}
            <button @click="open = open === 'shop' ? '' : 'shop'"
                    class="nav-item grp-shop {{ $inShop ? 'group-open' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-box-open"></i></span>
                <span style="flex:1;">Sản Phẩm</span>
                <i class="fa-solid fa-chevron-down nav-chevron" :class="open==='shop' ? 'open' : ''"></i>
            </button>
            <div class="sub-menu" x-show="open==='shop'" x-cloak
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0">
                <a href="{{ route('admin.products.index') }}"
                   class="sub-item {{ request()->routeIs('admin.products.index') || (request()->routeIs('admin.products.*') && !request()->routeIs('admin.products.create')) ? 'active' : '' }}">
                    <span class="dot"></span> Danh sách sản phẩm
                </a>
                <a href="{{ route('admin.products.create') }}"
                   class="sub-item {{ request()->routeIs('admin.products.create') ? 'active' : '' }}">
                    <span class="dot"></span> Thêm sản phẩm mới
                </a>
                <a href="{{ route('admin.categories.index', ['type' => 'product']) }}"
                   class="sub-item {{ request()->routeIs('admin.categories.*') && request()->get('type','product') === 'product' ? 'active' : '' }}">
                    <span class="dot"></span> Danh mục sản phẩm
                </a>
                <a href="{{ route('admin.attributes.index') }}"
                   class="sub-item {{ request()->routeIs('admin.attributes.*') ? 'active' : '' }}">
                    <span class="dot"></span> Thuộc tính sản phẩm
                </a>
            </div>

            <a href="{{ route('admin.orders.index') }}"
               class="nav-item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}"
               style="margin-top:2px;">
                <span class="nav-icon" style="{{ request()->routeIs('admin.orders.*') ? '' : '' }}"><i class="fa-solid fa-bag-shopping"></i></span>
                Đơn Hàng
            </a>

            <a href="{{ route('admin.flash-sales.index') }}"
               class="nav-item {{ request()->routeIs('admin.flash-sales.*') ? 'active' : '' }}">
                <span class="nav-icon" style="{{ request()->routeIs('admin.flash-sales.*') ? 'background:rgba(249,115,22,.2);color:#fb923c;' : '' }}">
                    <i class="fa-solid fa-bolt"></i>
                </span>
                Flash Sale
            </a>

            <a href="{{ route('admin.users.index') }}"
               class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-users"></i></span>
                Khách Hàng
            </a>

            <div class="nav-sep"></div>
            <p class="nav-label">Nội dung</p>

            {{-- Bài viết --}}
            <button @click="open = open === 'content' ? '' : 'content'"
                    class="nav-item grp-content {{ $inContent ? 'group-open' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-newspaper"></i></span>
                <span style="flex:1;">Bài Viết</span>
                <i class="fa-solid fa-chevron-down nav-chevron" :class="open==='content' ? 'open' : ''"></i>
            </button>
            <div class="sub-menu" x-show="open==='content'" x-cloak
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0">
                <a href="{{ route('admin.posts.index') }}"
                   class="sub-item {{ request()->routeIs('admin.posts.*') ? 'active' : '' }}">
                    <span class="dot"></span> Danh sách bài viết
                </a>
                <a href="{{ route('admin.posts.create') }}"
                   class="sub-item {{ request()->routeIs('admin.posts.create') ? 'active' : '' }}">
                    <span class="dot"></span> Thêm bài viết mới
                </a>
                <a href="{{ route('admin.categories.index', ['type' => 'post']) }}"
                   class="sub-item {{ request()->routeIs('admin.categories.*') && request()->get('type') === 'post' ? 'active' : '' }}">
                    <span class="dot"></span> Danh mục bài viết
                </a>
            </div>

            {{-- Trang tĩnh --}}
            <a href="{{ route('admin.pages.index') }}"
               class="nav-item {{ request()->routeIs('admin.pages.*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-file-lines"></i></span>
                Trang Tĩnh
            </a>

            {{-- Thư viện --}}
            <button @click="open = open === 'media' ? '' : 'media'"
                    class="nav-item grp-media {{ $inMedia ? 'group-open' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-photo-film"></i></span>
                <span style="flex:1;">Thư Viện & Widget</span>
                <i class="fa-solid fa-chevron-down nav-chevron" :class="open==='media' ? 'open' : ''"></i>
            </button>
            <div class="sub-menu" x-show="open==='media'" x-cloak
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0">
                <a href="{{ route('admin.media.index') }}"
                   class="sub-item {{ request()->routeIs('admin.media.*') ? 'active' : '' }}">
                    <span class="dot"></span> Quản lý Media
                </a>
                <a href="{{ route('admin.widgets.index') }}"
                   class="sub-item {{ request()->routeIs('admin.widgets.*') ? 'active' : '' }}">
                    <span class="dot"></span> Widget
                </a>
            </div>

            <div class="nav-sep"></div>
            <p class="nav-label">Hệ thống</p>

            {{-- Cài đặt (bao gồm ngôn ngữ & dịch) --}}
            <button @click="open = open === 'settings' ? '' : 'settings'"
                    class="nav-item grp-system {{ $inSettings ? 'group-open' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-gear"></i></span>
                <span style="flex:1;">Cài Đặt</span>
                <i class="fa-solid fa-chevron-down nav-chevron" :class="open==='settings' ? 'open' : ''"></i>
            </button>
            <div class="sub-menu" x-show="open==='settings'" x-cloak
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0">
                <a href="{{ route('admin.settings.index') }}"
                   class="sub-item {{ request()->routeIs('admin.settings.index') || request()->routeIs('admin.settings.group') ? 'active' : '' }}">
                    <span class="dot"></span> Cài đặt hệ thống
                </a>
                <a href="{{ route('admin.languages.index') }}"
                   class="sub-item {{ request()->routeIs('admin.languages.*') ? 'active' : '' }}">
                    <span class="dot"></span> Ngôn ngữ
                </a>
                <a href="{{ route('admin.translations.index') }}"
                   class="sub-item {{ request()->routeIs('admin.translations.*') ? 'active' : '' }}">
                    <span class="dot"></span> Quản lý bản dịch
                </a>
            </div>

        </nav>

        {{-- User block --}}
        <div style="padding:14px 16px;border-top:1px solid rgba(255,255,255,.06);">
            <div style="display:flex;align-items:center;gap:12px;padding:12px 14px;border-radius:12px;background:rgba(255,255,255,.04);">
                <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#3b82f6,#8b5cf6);display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:700;color:#fff;flex-shrink:0;">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                </div>
                <div style="flex:1;min-width:0;">
                    <p style="font-size:13.5px;font-weight:600;color:#e2e8f0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ auth()->user()->name ?? 'Admin' }}</p>
                    <p style="font-size:11px;color:#475569;margin-top:1px;">
                        <a href="{{ route('admin.account') }}" style="color:#475569;text-decoration:none;" onmouseover="this.style.color='#93c5fd'" onmouseout="this.style.color='#475569'">Quản trị viên</a>
                    </p>
                </div>
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" title="Đăng xuất"
                        style="width:30px;height:30px;border-radius:8px;background:rgba(239,68,68,.1);border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;color:#f87171;transition:all .15s;flex-shrink:0;"
                        onmouseover="this.style.background='rgba(239,68,68,.2)'"
                        onmouseout="this.style.background='rgba(239,68,68,.1)'">
                        <i class="fa-solid fa-right-from-bracket" style="font-size:12px;"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- ══════════════════════════════════════════
         MAIN CONTENT
    ══════════════════════════════════════════ --}}
    <div style="flex:1;display:flex;flex-direction:column;overflow:hidden;min-width:0;">

        {{-- Topbar --}}
        <div id="topbar">
            <div>
                <div class="topbar-title">@yield('page-title', 'Bảng điều khiển')</div>
                @hasSection('page-subtitle')
                    <div class="topbar-sub">@yield('page-subtitle')</div>
                @endif
            </div>
            <div style="display:flex;align-items:center;gap:10px;">
                @yield('page-actions')
                <a href="@yield('preview-url', url('/shop'))" target="_blank" class="btn btn-ghost btn-sm">
                    <i class="fa-solid fa-arrow-up-right-from-square" style="font-size:11px;"></i> Xem trang
                </a>
            </div>
        </div>

        {{-- Flash messages --}}
        @if(session('success') || session('error') || $errors->any())
        <div style="padding:16px 28px 0;flex-shrink:0;display:flex;flex-direction:column;gap:8px;">
            @if(session('success'))
                <div class="flash flash-success">
                    <i class="fa-solid fa-circle-check" style="color:#22c55e;font-size:16px;flex-shrink:0;"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="flash flash-error">
                    <i class="fa-solid fa-circle-xmark" style="color:#ef4444;font-size:16px;flex-shrink:0;"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif
            @if($errors->any())
                <div class="flash flash-error" style="flex-direction:column;align-items:flex-start;">
                    <p style="font-weight:700;display:flex;align-items:center;gap:8px;margin-bottom:6px;">
                        <i class="fa-solid fa-triangle-exclamation" style="color:#ef4444;"></i> Vui lòng kiểm tra lại:
                    </p>
                    <ul style="list-style:disc;padding-left:22px;display:flex;flex-direction:column;gap:3px;font-size:13.5px;">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif
        </div>
        @endif

        {{-- Page content --}}
        <main id="main-scroll" style="flex:1;overflow-y:auto;padding:24px 28px;">
            @yield('content')
        </main>
    </div>
</div>

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script src="{{ asset('js/admin/seo-checklist.js') }}"></script>
@include('admin.components.media-picker')

@stack('modals')
@stack('scripts')
<script>
// Global helper: cập nhật preview ảnh
function updateImgPreview(previewId, url) {
    const el = document.getElementById(previewId);
    if (!el) return;
    if (url) {
        el.innerHTML = `<img src="${url}" style="height:80px;border-radius:8px;object-fit:cover;border:1px solid #e2e8f0;max-width:100%;" onerror="this.style.display='none'">`;
    } else {
        el.innerHTML = '';
    }
}
</script>
</body>
</html>
