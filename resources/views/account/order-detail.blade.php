@extends('layouts.app')

@section('body_class', 'shop-main-h')

@section('title', 'Chi tiết đơn hàng #' . $order->order_number)

@section('content')
    <x-breadcrumb :items="[
            ['label' => 'Tài khoản', 'url' => route('profile')],
            ['label' => 'Đơn hàng của tôi', 'url' => route('profile') . '?tab=orders'],
            ['label' => 'Chi tiết đơn hàng #' . $order->order_number]
        ]" />

    <div class="account-area rts-section-gap">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-3">
                    <div class="account-sidebar bg-white p-4 rounded-3xl border border-slate-100 shadow-sm">
                        <div class="user-info-brief mb-4 pb-4 border-bottom d-flex align-items-center gap-3">
                            <div class="avatar-circle text-white d-flex align-items-center justify-content-center fw-bold shadow-sm"
                                style="width: 50px; height: 50px; border-radius: 50%; font-size: 20px; background: var(--color-primary-gradient);">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <div>
                                <h6 class="mb-0 font-bold text-slate-800">{{ Auth::user()->name }}</h6>
                                <span class="text-muted small uppercase tracking-widest font-black" style="font-size: 9px;">Thành viên</span>
                            </div>
                        </div>
                        <ul class="account-nav list-unstyled m-0 p-0">
                            <li class="mb-2">
                                <a href="{{ route('profile') }}" class="account-nav-link">
                                    <i class="fa-light fa-user"></i> Thông tin cá nhân
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="{{ route('profile') . '?tab=orders' }}" class="account-nav-link active">
                                    <i class="fa-light fa-box"></i> Đơn hàng của tôi
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="{{ route('wishlist') }}" class="account-nav-link">
                                    <i class="fa-light fa-heart"></i> Danh sách yêu thích
                                </a>
                            </li>
                            <li class="mt-4 pt-4 border-top border-slate-100">
                                <button class="account-nav-link border-0 w-100 text-start bg-transparent text-rose-500" 
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fa-light fa-right-from-bracket"></i> Đăng xuất
                                </button>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="account-main-content bg-white p-5 md-p-4 rounded-3xl border border-slate-100 shadow-sm">
                        <div class="order-table-account">
                            <div class="d-flex justify-content-between align-items-center mb--40">
                                <h2 class="title mb--0 text-3xl font-black tracking-tighter uppercase">Chi tiết đơn hàng #{{ $order->order_number }}</h2>
                                @php
                                    $statusClasses = [
                                        'pending' => 'bg-amber-100 text-amber-700',
                                        'processing' => 'bg-blue-100 text-blue-700',
                                        'shipping' => 'badge-primary-soft',
                                        'delivered' => 'bg-emerald-100 text-emerald-700',
                                        'completed' => 'bg-slate-100 text-slate-700',
                                        'cancelled' => 'bg-rose-100 text-rose-700',
                                    ];
                                    $statusClass = $statusClasses[$order->status] ?? 'bg-slate-100 text-slate-700';
                                @endphp
                                <span class="badge {{ $statusClass }} py-2 px-3 rounded-pill font-bold uppercase tracking-widest text-[10px]">{{ $order->status_label }}</span>
                            </div>
                            <p class="text-muted mb--40 font-bold" style="margin-top: -30px;">
                                <i class="fa-light fa-calendar-lines me-1"></i> Ngày đặt: {{ $order->created_at->format('d/m/Y H:i') }}
                            </p>

                            <div class="row g-4 mb--40">
                                <div class="col-md-6">
                                    <div class="p-4 rounded-2xl border border-slate-50 bg-slate-50/30 height-100">
                                        <h6 class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4 pb-2 border-b border-white">Thông tin nhận hàng</h6>
                                        <p class="mb-2 font-black text-slate-800">{{ $order->customer_name }}</p>
                                        <p class="mb-2 text-slate-600"><i class="fa-light fa-phone me-2 opacity-50"></i> {{ $order->customer_phone }}</p>
                                        <p class="mb-2 text-slate-600"><i class="fa-light fa-location-dot me-2 opacity-50"></i> {{ $order->shipping_address }}</p>
                                        <p class="mb-0 text-slate-400 text-xs"><i class="fa-light fa-envelope me-2 opacity-50"></i> {{ $order->customer_email }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-4 rounded-2xl border border-slate-50 bg-slate-50/30 height-100">
                                        <h6 class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4 pb-2 border-b border-white">Thanh toán & Ghi chú</h6>
                                        <p class="mb-3 text-slate-600">Phương thức: <span class="badge bg-slate-100 text-slate-700 font-black px-2 py-1 ml-2">{{ strtoupper($order->payment_method) }}</span></p>
                                        <p class="mb-3 text-slate-600">Trạng thái: 
                                            @if($order->payment_status === 'paid')
                                                <span class="badge bg-emerald-50 text-emerald-600 font-bold px-2 py-1 ml-2"><i class="fa-solid fa-circle-check mr-1"></i> ĐÃ THANH TOÁN</span>
                                            @else
                                                <span class="badge bg-amber-50 text-amber-600 font-bold px-2 py-1 ml-2">CHỜ THANH TOÁN</span>
                                            @endif
                                        </p>
                                        @if($order->customer_note)
                                            <div class="mt-4 pt-3 border-top border-slate-100 italic text-slate-500 text-sm">
                                                <strong>Ghi chú:</strong> {{ $order->customer_note }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead class="bg-slate-50 text-slate-500 text-[10px] uppercase font-black tracking-widest">
                                        <tr>
                                            <th class="p-4 border-0 rounded-start-xl">Sản phẩm</th>
                                            <th class="p-4 border-0 text-center">Giá</th>
                                            <th class="p-4 border-0 text-center">SL</th>
                                            <th class="p-4 border-0 text-end rounded-end-xl">Tổng</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-slate-700">
                                        @foreach($order->items as $item)
                                            <tr>
                                                <td class="p-4">
                                                    <div class="d-flex align-items-center gap-4">
                                                        <img src="{{ $item->image_url }}" 
                                                            alt="{{ $item->product_name }}" 
                                                            class="rounded-xl shadow-sm"
                                                            style="width: 60px; height: 60px; object-fit: cover; border: 1px solid #f0f0f0;">
                                                        <div>
                                                            <span class="font-bold d-block text-slate-800">{{ $item->product_name }}</span>
                                                            @if($item->variant_label)
                                                                <span class="badge bg-slate-50 text-slate-400 font-black text-[9px] uppercase mt-1">{{ $item->variant_label }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="p-4 text-center font-bold">{{ number_format($item->price, 0, ',', '.') }}đ</td>
                                                <td class="p-4 text-center font-black text-slate-400">x{{ $item->quantity }}</td>
                                                <td class="p-4 text-end font-black text-slate-800">{{ number_format($item->total, 0, ',', '.') }}đ</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-8 p-6 rounded-3xl border border-slate-100 bg-slate-50/20">
                                <div class="row justify-content-end">
                                    <div class="col-lg-5">
                                        <div class="space-y-3">
                                            <div class="d-flex justify-content-between text-slate-500">
                                                <span class="text-xs uppercase font-black tracking-widest">Tạm tính:</span>
                                                <span class="font-bold">{{ number_format($order->subtotal, 0, ',', '.') }}đ</span>
                                            </div>
                                            @if($order->discount > 0)
                                                <div class="d-flex justify-content-between text-rose-500">
                                                    <span class="text-xs uppercase font-black tracking-widest">Giảm giá:</span>
                                                    <span class="font-black">-{{ number_format($order->discount, 0, ',', '.') }}đ</span>
                                                </div>
                                            @endif
                                            <div class="d-flex justify-content-between text-slate-500 pb-3 mb-3 border-b border-slate-100">
                                                <span class="text-xs uppercase font-black tracking-widest">Phí vận chuyển:</span>
                                                <span class="font-bold">{{ $order->shipping_fee > 0 ? number_format($order->shipping_fee, 0, ',', '.') . 'đ' : 'Miễn phí' }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center pt-2">
                                                <span class="text-sm font-black uppercase tracking-tighter text-slate-800">Tổng cộng:</span>
                                                <span class="text-3xl font-black text-primary-gradient" style="background: var(--color-primary-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">{{ number_format($order->total, 0, ',', '.') }}đ</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-12 text-center">
                                <a href="{{ route('profile') . '?tab=orders' }}" class="rts-btn btn-primary px-10 py-4 rounded-2xl">
                                    <i class="fa-light fa-arrow-left me-2"></i> Quay lại đơn hàng
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .account-nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 20px;
            border-radius: 16px;
            font-size: 14px;
            font-weight: 700;
            color: #64748b;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none !important;
            cursor: pointer;
        }
        .account-nav-link i {
            width: 20px;
            text-align: center;
            font-size: 16px;
        }
        .account-nav-link:hover {
            color: var(--color-primary);
            background: var(--color-primary-alpha-10);
            transform: translateX(5px);
        }
        .account-nav-link.active {
            background: var(--color-primary-gradient);
            color: white !important;
            box-shadow: var(--primary-glow);
        }
        .account-nav-link.active i { color: white; }
        
        .rounded-3xl { border-radius: 24px !important; }
        .rounded-2xl { border-radius: 16px !important; }
        .rounded-xl { border-radius: 12px !important; }
        
        .bg-slate-50\/30 { background-color: rgba(248, 250, 252, 0.3); }
        .bg-slate-50\/20 { background-color: rgba(248, 250, 252, 0.2); }
        .border-slate-50 { border-color: #f1f5f9; }
        .border-slate-100 { border-color: #f1f5f9; }
        .text-slate-400 { color: #94a3b8; }
        .text-slate-500 { color: #64748b; }
        .text-slate-600 { color: #475569; }
        .text-slate-700 { color: #334155; }
        .text-slate-800 { color: #1e293b; }
    </style>
@endsection