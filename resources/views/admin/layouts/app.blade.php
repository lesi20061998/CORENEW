<!DOCTYPE html>
<html lang="vi" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Quản trị') | {{ get_setting('site_name', 'Kalles Store') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Ekomart standard plugins (includes FontAwesome 6 Pro) -->
    <link rel="stylesheet" href="{{ asset('theme/css/plugins.css') }}">
    <!-- Custom Admin Typography -->
    <link
        href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <style>
        [x-cloak] {
            display: none !important;
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html,
        body {
            height: 100%;
        }

        body {
            font-family: 'Be Vietnam Pro', sans-serif;
            font-size: 14px;
            background: #f8fafc;
            color: #0f172a;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            margin: 0;
        }

        input,
        button,
        select,
        textarea {
            font-family: inherit !important;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: inherit !important;
            color: #0f172a;
        }

        /* ════════════════════════════════
           SIDEBAR (MODERN DARK)
        ════════════════════════════════ */
        #sidebar {
            width: 250px;
            min-width: 250px;
            background: #0f172a;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            overflow-x: hidden;
            flex-shrink: 0;
            border-right: 1px solid rgba(255, 255, 255, 0.05);
        }

        #sidebar::-webkit-scrollbar {
            width: 3px;
        }

        #sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, .08);
        }

        .sb-logo {
            padding: 20px 18px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sb-logo-icon {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 6px 12px rgba(37, 99, 235, 0.25);
        }

        .sb-logo-icon i {
            color: #fff;
            font-size: 16px;
        }

        .sb-logo-text p {
            font-size: 15px;
            font-weight: 800;
            color: #fff;
            text-transform: uppercase;
        }

        .sb-logo-text span {
            font-size: 9px;
            color: #485b74;
            font-weight: 800;
            text-transform: uppercase;
            margin-top: 1px;
            display: block;
        }

        .nav-label {
            padding: 20px 24px 10px;
            font-size: 9.5px;
            font-weight: 700;
            text-transform: uppercase;
            color: #334155;
            opacity: 0.6;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 20px;
            font-size: 13.5px;
            font-weight: 600;
            color: #94a3b8;
            cursor: pointer;
            transition: all .2s;
            border: none;
            width: 100%;
            text-align: left;
            text-decoration: none;
            background: transparent;
        }

        .nav-item:hover {
            color: #fff;
        }

        .nav-item.active {
            color: #fff;
            background: rgba(255, 255, 255, 0.03);
            position: relative;
        }

        .nav-item.active::after {
            content: '';
            position: absolute;
            right: 0;
            top: 12px;
            bottom: 12px;
            width: 4px;
            background: #3b82f6;
            border-radius: 4px 0 0 4px;
            box-shadow: -4px 0 12px rgba(59, 130, 246, 0.6);
        }

        .nav-icon {
            width: 32px;
            height: 32px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 14px;
            background: rgba(255, 255, 255, .04);
            color: #64748b;
            transition: all .2s;
        }

        .nav-item:hover .nav-icon {
            background: rgba(255, 255, 255, 0.08);
            color: #cbd5e1;
        }

        .nav-item.active .nav-icon {
            background: #3b82f6;
            color: #fff;
            box-shadow: 0 4px 10px rgba(59, 130, 246, 0.3);
        }

        .sub-menu {
            background: rgba(15, 23, 42, 0.6);
            padding: 4px 0;
        }

        .sub-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 24px 8px 68px;
            font-size: 13px;
            font-weight: 500;
            color: #64748b;
            text-decoration: none;
            transition: all .2s;
        }

        .sub-item:hover {
            color: #cbd5e1;
        }

        .sub-item.active {
            color: #fff;
            font-weight: 700;
        }

        .sub-item .dot {
            width: 4px;
            height: 4px;
            border-radius: 50%;
            background: #334155;
        }

        .sub-item.active .dot {
            background: #3b82f6;
            box-shadow: 0 0 8px #3b82f6;
        }

        /* ════════════════════════════════
           TOPBAR (PREMIUM)
        ════════════════════════════════ */
        #topbar {
            background: #fff;
            border-bottom: 1px solid #f1f5f9;
            padding: 0 40px;
            height: 72px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .topbar-title {
            font-size: 20px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.04em;
            text-transform: uppercase;

        }

        .topbar-sub {
            font-size: 10px;
            text-transform: uppercase;
            font-weight: 800;
            color: #94a3b8;
            margin-top: 4px;
        }

        /* ════════════════════════════════
           PREMIUM COMPONENTS
        ════════════════════════════════ */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 12px 28px;
            border-radius: 12px;
            border: none;
            font-size: 11.5px;
            font-weight: 800;
            cursor: pointer;
            transition: all .2s;
            text-decoration: none;
            white-space: nowrap;
            text-transform: uppercase;
        }

        .btn-primary {
            background: #2563eb;
            color: #fff;
            box-shadow: 0 10px 20px rgba(37, 99, 235, 0.15);
        }

        .btn-primary:hover {
            background: #1d4ed8;
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(37, 99, 235, 0.25);
        }

        .btn-secondary {
            background: #f8fafc;
            color: #475569;
            border: 1.5px solid #f1f5f9;
        }

        .btn-secondary:hover {
            background: #f1f5f9;
            border-color: #e2e8f0;
        }

        .card {
            background: #fff;
            border-radius: 40px;
            border: 1px solid #f1f5f9;
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.03);
            overflow: hidden;
        }

        .card-header {
            padding: 32px 40px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-title {
            font-size: 12px;
            font-weight: 800;
            color: #64748b;
            text-transform: uppercase;
        }

        .card-body {
            padding: 0 40px 40px;
        }

        /* Premium Table */
        .tbl-th {
            padding: 16px 24px;
            font-size: 10px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            text-align: left;
            border-bottom: 2px solid #f8fafc;
        }

        .tbl-td {
            padding: 18px 24px;
            font-size: 13.5px;
            font-weight: 600;
            color: #1e293b;
            vertical-align: middle;
            border-bottom: 1px solid #f8fafc;
        }

        tr:hover .tbl-td {
            background: #fafbfc;
        }

        .form-input,
        .form-select {
            width: 100%;
            border: 1.5px solid #f1f5f9;
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 14px;
            font-weight: 500;
            color: #0f172a;
            outline: none;
            transition: all .2s;
            background: #fff;
        }

        .form-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
            background: #fff;
        }

        .form-label {
            display: block;
            font-size: 10px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            margin-bottom: 8px;
            padding-left: 2px;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .badge-green {
            background: #dcfce7;
            color: #15803d;
        }

        .badge-rose {
            background: #fee2e2;
            color: #be123c;
        }

        [x-cloak] {
            display: none !important;
        }

        .seo-tab {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 12px 20px;
            font-size: 11px;
            font-weight: 800;
            color: #94a3b8;
            text-transform: uppercase;
            border-bottom: 3px solid transparent;
            transition: all .2s;
            background: transparent;
            cursor: pointer;
        }

        .seo-tab.active {
            color: #3b82f6;
            border-bottom-color: #3b82f6;
        }

        .seo-check-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 10px 0;
            font-size: 14px;
            font-weight: 500;
            color: #334155;
        }

        .custom-scroll::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scroll::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scroll::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 10px;
        }
    </style>
    @stack('styles')
</head>

<body>

    @php
        $inShop = request()->routeIs('admin.products.*') || request()->routeIs('admin.attributes.*') || (request()->routeIs('admin.categories.*') && request()->get('type', 'product') === 'product');
        $inContent = request()->routeIs('admin.posts.*') || request()->routeIs('admin.pages.*') || (request()->routeIs('admin.categories.*') && request()->get('type') === 'post');
        $inMedia = request()->routeIs('admin.media.*') || request()->routeIs('admin.widgets.*');
        $inSettings = request()->routeIs('admin.settings.*') || request()->routeIs('admin.languages.*') || request()->routeIs('admin.translations.*');
    @endphp

    <div style="display:flex;height:100vh;overflow:hidden;"
        x-data="{ open: '{{ $inShop ? 'shop' : ($inContent ? 'content' : ($inMedia ? 'media' : ($inSettings ? 'settings' : ''))) }}' }">

        {{-- ══════════════════════════════════════════
        SIDEBAR
        ══════════════════════════════════════════ --}}
        <aside id="sidebar" class="custom-scroll">
            <div class="sb-logo">
                <div class="sb-logo-icon">
                    <i class="fa-solid fa-bolt"></i>
                </div>
                <div class="sb-logo-text">
                    <p>VietTin Mart</p>
                    <span>Premium Admin</span>
                </div>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 py-4">
                <p class="nav-label">General Overview</p>
                <a href="{{ route('admin.dashboard') }}"
                    class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fa-solid fa-gauge-high"></i></span>
                    Dashboards
                </a>

                <p class="nav-label">Shop Management</p>
                <button @click="open = open === 'shop' ? '' : 'shop'" class="nav-item {{ $inShop ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fa-solid fa-box"></i></span>
                    <span class="flex-1">Quản lý Kho</span>
                    <i class="fa-solid fa-chevron-down nav-chevron text-[10px]"
                        :class="open==='shop' ? 'rotate-180' : ''"></i>
                </button>
                <div class="sub-menu shadow-inner bg-slate-900/40" x-show="open==='shop'" x-cloak x-collapse>
                    <a href="{{ route('admin.products.index') }}"
                        class="sub-item {{ request()->routeIs('admin.products.index') ? 'active' : '' }}">
                        <span class="dot"></span> Danh sách sản phẩm
                    </a>
                    <a href="{{ route('admin.products.create') }}"
                        class="sub-item {{ request()->routeIs('admin.products.create') ? 'active' : '' }}">
                        <span class="dot"></span> Thêm sản phẩm
                    </a>
                    <a href="{{ route('admin.categories.index', ['type' => 'product']) }}"
                        class="sub-item {{ request()->routeIs('admin.categories.*') && request()->get('type', 'product') === 'product' ? 'active' : '' }}">
                        <span class="dot"></span> Chuyên mục
                    </a>
                    <a href="{{ route('admin.attributes.index') }}"
                        class="sub-item {{ request()->routeIs('admin.attributes.*') ? 'active' : '' }}">
                        <span class="dot"></span> Thuộc tính
                    </a>
                </div>

                <a href="{{ route('admin.orders.index') }}"
                    class="nav-item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fa-solid fa-shopping-cart"></i></span>
                    Đơn Hàng
                </a>

                <a href="{{ route('admin.flash-sales.index') }}"
                    class="nav-item {{ request()->routeIs('admin.flash-sales.*') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fa-solid fa-bolt-lightning text-orange-400"></i></span>
                    Flash Sales
                </a>

                <a href="{{ route('admin.coupons.index') }}"
                    class="nav-item {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fa-solid fa-ticket text-blue-400"></i></span>
                    Mã giảm giá
                </a>

                <p class="nav-label">Content & UX</p>
                <button @click="open = open === 'content' ? '' : 'content'"
                    class="nav-item {{ $inContent ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fa-solid fa-file-signature"></i></span>
                    <span class="flex-1">Bài Viết & Trang</span>
                    <i class="fa-solid fa-chevron-down nav-chevron text-[10px]"
                        :class="open==='content' ? 'rotate-180' : ''"></i>
                </button>
                <div class="sub-menu shadow-inner bg-slate-900/40" x-show="open==='content'" x-cloak x-collapse>
                    <a href="{{ route('admin.posts.index') }}"
                        class="sub-item {{ request()->routeIs('admin.posts.*') ? 'active' : '' }}">
                        <span class="dot"></span> Blog Posts
                    </a>
                    <a href="{{ route('admin.pages.index') }}"
                        class="sub-item {{ request()->routeIs('admin.pages.*') ? 'active' : '' }}">
                        <span class="dot"></span> Static Pages
                    </a>
                    <a href="{{ route('admin.categories.index', ['type' => 'post']) }}"
                        class="sub-item {{ request()->routeIs('admin.categories.*') && request()->get('type') === 'post' ? 'active' : '' }}">
                        <span class="dot"></span> Chuyên mục tin
                    </a>
                </div>

                <button @click="open = open === 'appearance' ? '' : 'appearance'"
                    class="nav-item {{ $inMedia ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fa-solid fa-palette"></i></span>
                    <span class="flex-1">Giao Diện</span>
                    <i class="fa-solid fa-chevron-down nav-chevron text-[10px]"
                        :class="open==='appearance' ? 'rotate-180' : ''"></i>
                </button>
                <div class="sub-menu shadow-inner bg-slate-900/40" x-show="open==='appearance'" x-cloak x-collapse>
                    <a href="{{ route('admin.settings.group', 'appearance') }}"
                        class="sub-item {{ request()->is('admin/settings/group/appearance') ? 'active' : '' }}">
                        <span class="dot"></span> Cấu hình UX
                    </a>
                    <a href="{{ route('admin.menus.index') }}"
                        class="sub-item {{ request()->routeIs('admin.menus.*') ? 'active' : '' }}">
                        <span class="dot"></span> Menu Builder
                    </a>
                    <a href="{{ route('admin.widgets.index') }}"
                        class="sub-item {{ request()->routeIs('admin.widgets.*') ? 'active' : '' }}">
                        <span class="dot"></span> Widget Builder
                    </a>
                    <a href="{{ route('admin.media.index') }}"
                        class="sub-item {{ request()->routeIs('admin.media.*') ? 'active' : '' }}">
                        <span class="dot"></span> Media Assets
                    </a>
                </div>

                <p class="nav-label">System Config</p>
                <a href="{{ route('admin.settings.index') }}"
                    class="nav-item {{ request()->routeIs('admin.settings.index') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fa-solid fa-sliders"></i></span>
                    Cài Đặt Tổng Thể
                </a>
                <a href="{{ route('admin.languages.index') }}"
                    class="nav-item {{ request()->routeIs('admin.languages.*') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fa-solid fa-language"></i></span>
                    Languages
                </a>
            </nav>

            {{-- User block --}}
            <div class="p-6 border-t border-white/5">
                <div class="flex items-center gap-4 bg-white/5 p-4 rounded-3xl">
                    <div
                        class="w-10 h-10 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center font-black text-white shrink-0">
                        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-black text-white tracking-tight truncate">
                            {{ auth()->user()->name ?? 'Admin' }}
                        </p>
                        <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest mt-0.5">Admin Level</p>
                    </div>
                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="w-8 h-8 rounded-xl bg-orange-500/10 text-orange-500 hover:bg-orange-500 hover:text-white transition-all">
                            <i class="fa-solid fa-power-off text-[10px]"></i>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        {{-- ══════════════════════════════════════════
        MAIN CONTENT
        ══════════════════════════════════════════ --}}
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <header id="topbar">
                <div>
                    <h1 class="topbar-title">@yield('page-title', 'Dashboard')</h1>
                    @hasSection('page-subtitle')
                        <div class="topbar-sub">@yield('page-subtitle')</div>
                    @endif
                </div>
                <div class="flex items-center gap-4">
                    @yield('page-actions')
                    <a href="@yield('preview-url', url('/shop'))" target="_blank" class="btn btn-secondary border-none">
                        <i class="fa-solid fa-external-link text-[10px]"></i> Live Site
                    </a>
                </div>
            </header>

            <main id="main-scroll" class="flex-1 overflow-y-auto p-10 bg-[#f8fafc] custom-scroll">
                @yield('content')
            </main>
        </div>

        {{-- Global Toast Component --}}
        <div x-data x-cloak>
            <template x-if="$store.toast.show">
                <div x-show="$store.toast.show" x-transition:enter="transition ease-out duration-300 transform"
                    x-transition:enter-start="translate-y-[-100%] opacity-0"
                    x-transition:enter-end="translate-y-0 opacity-100"
                    x-transition:leave="transition ease-in duration-200 transform"
                    x-transition:leave-start="translate-y-0 opacity-100"
                    x-transition:leave-end="translate-y-[-100%] opacity-0"
                    class="fixed top-5 right-5 z-[9999] flex items-center gap-3 px-6 py-3 rounded-xl shadow-2xl min-w-[300px]"
                    :class="$store.toast.type === 'error' ? 'bg-rose-600 text-white shadow-rose-500/20' : 'bg-emerald-600 text-white shadow-emerald-500/20'">
                    <div class="flex-shrink-0">
                        <template x-if="$store.toast.type === 'error'">
                            <i class="fa-solid fa-triangle-exclamation text-xl"></i>
                        </template>
                        <template x-if="$store.toast.type !== 'error'">
                            <i class="fa-solid fa-circle-check text-xl"></i>
                        </template>
                    </div>
                    <div>
                        <p class="font-black text-[11px] uppercase tracking-widest leading-none mb-1"
                            x-text="$store.toast.title"></p>
                        <p class="text-[10px] opacity-90 font-medium" x-text="$store.toast.message"></p>
                    </div>
                </div>
            </template>
        </div>

        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.store('toast', {
                    show: false,
                    title: '',
                    message: '',
                    type: 'success',
                    fire(title, message, type = 'success') {
                        this.title = title;
                        this.message = message;
                        this.type = type;
                        this.show = true;
                        setTimeout(() => { this.show = false; }, 4000);
                    }
                });
            });

            window.adminToast = (title, message, type = 'success') => {
                if (window.Alpine) {
                    Alpine.store('toast').fire(title, message, type);
                }
            };

            @if(session('success'))
                window.addEventListener('load', () => adminToast('Thành công', "{{ session('success') }}", 'success'));
            @endif
            @if(session('error'))
                window.addEventListener('load', () => adminToast('Có lỗi xảy ra', "{{ session('error') }}", 'error'));
            @endif
            @if($errors->any())
                window.addEventListener('load', () => adminToast('Thông tin chưa đúng', "{{ $errors->first() }}", 'error'));
            @endif

            // Smart Efficiency Order Poller
            (function () {
                let lastSeenId = parseInt(localStorage.getItem('last_order_id')) || 0;
                let pollerInterval = 60000; // Low frequency (1 minute) for background sync
                let isPolling = false;

                function checkNewOrders() {
                    if (isPolling) return;
                    isPolling = true;

                    // Fetch URL with Cache Buster
                    fetch(`{{ route('admin.orders.new-check') }}?after=${lastSeenId}&_t=${Date.now()}`, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    })
                        .then(res => {
                            if (res.status === 404) {
                                console.warn('ADMIN SYSTEM: Vui lòng F5 để cập nhật route mới nhất.');
                                return { orders: [], latest_id: lastSeenId };
                            }
                            return res.json();
                        })
                        .then(data => {
                            const newOrders = data.orders || [];
                            const serverLatestId = parseInt(data.latest_id);

                            if (lastSeenId === 0) {
                                lastSeenId = serverLatestId;
                                localStorage.setItem('last_order_id', lastSeenId);
                                return;
                            }

                            if (newOrders.length > 0) {
                                const activityList = document.getElementById('recent-activity-list');

                                newOrders.forEach((order, index) => {
                                    setTimeout(() => {
                                        adminToast('🔔 ĐƠN HÀNG MỚI!', `#${order.order_number} - ${order.customer_name}`, 'success');

                                        // 1. Play sound
                                        if (index === 0) {
                                            try { new Audio('https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3').play(); } catch (e) { }
                                        }

                                        // 2. Update dashboard list if on dashboard
                                        if (activityList) {
                                            const newItem = document.createElement('div');
                                            newItem.className = 'flex items-center justify-between anim-new-order';
                                            newItem.style.opacity = '0';
                                            newItem.style.transform = 'translateX(-10px)';
                                            newItem.style.transition = 'all 0.5s ease-out';
                                            newItem.innerHTML = `
                                            <div class="flex items-center gap-2">
                                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500 shadow-sm animate-pulse"></span>
                                                <span class="text-[10px] font-bold">#${order.order_number}</span>
                                            </div>
                                            <span class="text-[9px] text-slate-400">Vừa xong</span>
                                        `;
                                            activityList.prepend(newItem);
                                            // Fade in
                                            requestAnimationFrame(() => {
                                                newItem.style.opacity = '1';
                                                newItem.style.transform = 'translateX(0)';
                                            });

                                            // Keep list to 8 items max
                                            if (activityList.children.length > 8) {
                                                activityList.removeChild(activityList.lastChild);
                                            }
                                        }
                                    }, index * 800);
                                });
                            }

                            if (serverLatestId > lastSeenId) {
                                lastSeenId = serverLatestId;
                                localStorage.setItem('last_order_id', lastSeenId);
                            }
                        })
                        .catch(err => { })
                        .finally(() => { isPolling = false; });
                }

                // 1. Initial boot
                setTimeout(checkNewOrders, 500);

                // 2. Slow periodic sync
                setInterval(checkNewOrders, pollerInterval);

                // 3. SMART TRIGGER: Instant check when user switches back to this tab
                document.addEventListener('visibilitychange', () => {
                    if (document.visibilityState === 'visible') {
                        console.log('Admin tab activated - Performing instant order sync...');
                        checkNewOrders();
                    }
                });

                // 4. Also check on Window Focus
                window.addEventListener('focus', checkNewOrders);
            })();
        </script>

        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <script src="{{ asset('js/admin/seo-checklist.js') }}"></script>
        @include('components.admin.media-picker')

        @stack('modals')
        @stack('scripts')
        <script>
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
    </div>
</body>

</html>