@extends('layouts.app')

@section('body_class', 'shop-main-h')

@section('title', 'Thông tin tài khoản')

@section('content')
    <x-breadcrumb :items="[
            ['label' => 'Tài khoản', 'url' => route('profile')],
            ['label' => 'Thông tin cá nhân']
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
                        
                        <div class="nav accout-dashborard-nav flex-column gap-2" id="v-pills-tab" role="tablist">
                            <button class="account-nav-link active w-100 border-0 text-start" id="v-pills-home-tab" data-bs-toggle="pill"
                                data-bs-target="#v-pills-home" type="button" role="tab"><i class="fa-regular fa-chart-line"></i> Bảng điều khiển</button>
                            
                            <button class="account-nav-link w-100 border-0 text-start" id="v-pills-profile-tab" data-bs-toggle="pill"
                                data-bs-target="#v-pills-profile" type="button" role="tab"><i class="fa-regular fa-bag-shopping"></i> Đơn hàng</button>

                            <button class="account-nav-link w-100 border-0 text-start" id="v-pills-settings-tab" data-bs-toggle="pill"
                                data-bs-target="#v-pills-settings" type="button" role="tab"><i class="fa-sharp fa-regular fa-location-dot"></i> Địa chỉ của tôi</button>
                            
                            <button class="account-nav-link w-100 border-0 text-start" id="v-pills-settingsa-tab" data-bs-toggle="pill"
                                data-bs-target="#v-pills-settingsa" type="button" role="tab"><i class="fa-light fa-user"></i> Thông tin cá nhân</button>
                            
                            <div class="mt-4 pt-4 border-top border-slate-100">
                                <button class="account-nav-link w-100 border-0 text-start bg-transparent text-rose-500" 
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fa-light fa-right-from-bracket"></i> Đăng xuất
                                </button>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-9">
                    <div class="account-main-content bg-white p-5 md-p-4 rounded-3xl border border-slate-100 shadow-sm min-h-[500px]">
                        <div class="tab-content" id="v-pills-tabContent">
                            {{-- Dashboard --}}
                            <div class="tab-pane fade active show" id="v-pills-home" role="tabpanel">
                                <div class="dashboard-account-area">
                                    <h2 class="title text-3xl font-black tracking-tighter uppercase mb-4">Xin chào {{ $user->name }}!</h2>
                                    <p class="text-slate-500 leading-relaxed max-w-2xl">
                                        Từ trang quản lý tài khoản, bạn có thể dễ dàng theo dõi các <span class="text-primary font-bold">đơn hàng gần đây</span>, 
                                        quản lý các <span class="text-primary font-bold">địa chỉ nhận hàng</span>, cũng như cập nhật thông tin cá nhân và mật khẩu bảo mật.
                                    </p>
                                    
                                    <div class="row g-4 mt-8">
                                        <div class="col-md-4">
                                            <div class="p-6 rounded-2xl border border-slate-50 bg-slate-50/30 text-center hover-transform transition-all">
                                                <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center mx-auto mb-4">
                                                    <i class="fa-regular fa-bag-shopping text-xl"></i>
                                                </div>
                                                <h4 class="text-2xl font-black text-slate-800 mb-1">{{ count($orders) }}</h4>
                                                <p class="text-xs font-black uppercase tracking-widest text-slate-400">Đơn hàng</p>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="p-6 rounded-2xl border border-slate-50 bg-slate-50/30 text-center hover-transform transition-all">
                                                <div class="w-12 h-12 bg-amber-100 text-amber-600 rounded-xl flex items-center justify-center mx-auto mb-4">
                                                    <i class="fa-sharp fa-regular fa-location-dot text-xl"></i>
                                                </div>
                                                <h4 class="text-2xl font-black text-slate-800 mb-1">{{ count($user->addresses) }}</h4>
                                                <p class="text-xs font-black uppercase tracking-widest text-slate-400">Địa chỉ</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Orders --}}
                            <div class="tab-pane fade" id="v-pills-profile" role="tabpanel">
                                <div class="order-table-account">
                                    <h2 class="title text-2xl font-black tracking-tighter uppercase mb-6">Đơn hàng của bạn</h2>
                                    <div class="table-responsive">
                                        <table class="table align-middle">
                                            <thead class="bg-slate-50 text-slate-500 text-[10px] uppercase font-black tracking-widest">
                                                <tr>
                                                    <th class="p-4 border-0 rounded-start-xl">Mã đơn</th>
                                                    <th class="p-4 border-0">Ngày đặt</th>
                                                    <th class="p-4 border-0">Trạng thái</th>
                                                    <th class="p-4 border-0">Tổng cộng</th>
                                                    <th class="p-4 border-0 text-center rounded-end-xl">Hành động</th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-slate-700">
                                                @forelse($orders as $order)
                                                    <tr>
                                                        <td class="p-4"><span class="font-black text-slate-800">#{{ $order->order_number }}</span></td>
                                                        <td class="p-4 text-slate-500 font-bold">{{ $order->created_at->format('d/m/Y') }}</td>
                                                        <td class="p-4">
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
                                                            <span class="badge {{ $statusClass }} py-1 px-2 rounded-pill font-bold uppercase tracking-widest text-[9px]">{{ $order->status_label }}</span>
                                                        </td>
                                                        <td class="p-4 font-black">{{ number_format($order->total, 0, ',', '.') }}đ</td>
                                                        <td class="p-4 text-center">
                                                            <a href="{{ route('order.detail', $order) }}"
                                                                class="rts-btn btn-primary d-inline-flex px-4 py-2 text-[11px] font-black uppercase rounded-lg">Xem chi tiết</a>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="p-12 text-center">
                                                            <div class="opacity-20 mb-4"><i class="fa-light fa-bag-shopping fa-3x"></i></div>
                                                            <p class="text-slate-400 font-bold">Bạn chưa có đơn hàng nào.</p>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            {{-- Addresses --}}
                            <div class="tab-pane fade" id="v-pills-settings" role="tabpanel">
                                <div class="address-management">
                                    <div class="d-flex justify-content-between align-items-center mb-8">
                                        <h2 class="title text-2xl font-black tracking-tighter uppercase mb-0">Địa chỉ của tôi</h2>
                                        <button class="rts-btn btn-primary px-4 py-3 rounded-xl shadow-sm text-sm" type="button" data-bs-toggle="collapse" data-bs-target="#addAddressForm">
                                            <i class="fa-regular fa-plus me-2"></i> Thêm địa chỉ mới
                                        </button>
                                    </div>

                                    @if (session('success'))
                                        <div class="alert badge-primary-soft border-0 mb-6 p-4 rounded-2xl flex items-center gap-3">
                                            <i class="fa-solid fa-circle-check text-xl"></i>
                                            <span class="font-bold">{{ session('success') }}</span>
                                        </div>
                                    @endif

                                    <div class="collapse mb-8" id="addAddressForm">
                                        <div class="p-6 rounded-3xl border border-slate-100 bg-slate-50/20 shadow-inner">
                                            <h3 class="text-lg font-black uppercase tracking-widest text-slate-800 mb-6">Địa chỉ mới</h3>
                                            <form action="{{ route('address.store') }}" method="POST" id="newAddressForm" class="space-y-4">
                                                @csrf
                                                <div class="row g-4 text-start">
                                                    <div class="col-md-6">
                                                        <div class="single-input">
                                                            <label class="font-black text-[11px] uppercase tracking-widest text-slate-400 mb-2">Tên người nhận*</label>
                                                            <input type="text" name="receiver_name" class="rounded-xl border-slate-100 bg-white" placeholder="Nguyễn Văn A" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="single-input">
                                                            <label class="font-black text-[11px] uppercase tracking-widest text-slate-400 mb-2">Số điện thoại*</label>
                                                            <input type="text" name="receiver_phone" class="rounded-xl border-slate-100 bg-white" placeholder="0123 456 789" required>
                                                        </div>
                                                    </div>
                                                </div>

                                                <x-address-selector id="new-addr" />

                                                <div class="single-input">
                                                    <label class="font-black text-[11px] uppercase tracking-widest text-slate-400 mb-2">Địa chỉ chi tiết (Sỗ nhà, tên đường...)*</label>
                                                    <input type="text" name="address_detail" id="new_address_detail" class="rounded-xl border-slate-100 bg-white" placeholder="123 Đường Nguyễn Trãi" required>
                                                </div>

                                                <input type="hidden" name="full_address" id="new_full_address">

                                                <div class="flex items-center gap-2 mb-4">
                                                    <input type="checkbox" name="is_default" id="set_as_default" class="w-4 h-4 rounded border-slate-200 text-primary focus:ring-primary">
                                                    <label class="text-sm font-bold text-slate-600" for="set_as_default">Đặt làm địa chỉ mặc định</label>
                                                </div>

                                                <div class="d-flex gap-3">
                                                    <button type="submit" class="rts-btn btn-primary px-8">Lưu địa chỉ</button>
                                                    <button type="button" class="rts-btn bg-slate-100 text-slate-500 hover:bg-slate-200 px-6 rounded-xl font-bold" data-bs-toggle="collapse" data-bs-target="#addAddressForm">Hủy</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    <div class="row g-4">
                                        @forelse($user->addresses as $address)
                                            <div class="col-md-6">
                                                <div class="p-6 rounded-3xl border {{ $address->is_default ? 'border-primary-alpha-20 bg-primary-alpha-10' : 'border-slate-100 bg-white' }} position-relative height-100 transition-all hover:shadow-md">
                                                    @if($address->is_default)
                                                        <span class="badge-primary-soft position-absolute top-0 end-0 mt-4 me-4 text-[9px]">MẶC ĐỊNH</span>
                                                    @endif
                                                    <h4 class="text-base font-black text-slate-800 mb-3">{{ $address->receiver_name }}</h4>
                                                    <p class="text-slate-500 text-sm mb-2"><i class="fa-regular fa-phone me-2 opacity-50"></i> {{ $address->receiver_phone }}</p>
                                                    <p class="text-slate-600 text-sm mb-4 leading-relaxed"><i class="fa-regular fa-location-dot me-2 opacity-50"></i> {{ $address->full_address }}</p>
                                                    
                                                    <div class="d-flex gap-4 align-items-center mt-auto pt-4 border-top border-slate-100/50">
                                                        @if(!$address->is_default)
                                                            <form action="{{ route('address.set-default', $address->id) }}" method="POST">
                                                                @csrf
                                                                <button type="submit" class="text-primary text-[11px] font-black uppercase tracking-widest hover:underline border-0 bg-transparent">Đặt mặc định</button>
                                                            </form>
                                                        @endif
                                                        <form action="{{ route('address.destroy', $address->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa địa chỉ này?')">
                                                            @csrf
                                                            <button type="submit" class="text-rose-500 text-[11px] font-black uppercase tracking-widest hover:underline border-0 bg-transparent">Xóa địa chỉ</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="col-12 text-center py-16 bg-slate-50/30 rounded-3xl border border-dashed border-slate-200">
                                                <div class="opacity-20 mb-4"><i class="fa-light fa-location-dot fa-3x"></i></div>
                                                <p class="text-slate-400 font-bold">Bạn chưa có địa chỉ nào lưu lại.</p>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>

                            {{-- Personal Info --}}
                            <div class="tab-pane fade" id="v-pills-settingsa" role="tabpanel">
                                <form action="{{ route('account.profile.update') }}" method="POST" class="account-details-area space-y-8">
                                    @csrf
                                    @method('PUT')
                                    <h2 class="title text-2xl font-black tracking-tighter uppercase mb-6">Thông tin tài khoản</h2>

                                    @if(session('success'))
                                        <div class="alert badge-primary-soft border-0 p-4 rounded-2xl flex items-center gap-3">
                                            <i class="fa-solid fa-circle-check text-xl"></i>
                                            <span class="font-bold">{{ session('success') }}</span>
                                        </div>
                                    @endif

                                    <div class="row g-4 text-start">
                                        <div class="col-md-6">
                                            <div class="single-input">
                                                <label class="font-black text-[11px] uppercase tracking-widest text-slate-400 mb-2">Họ và tên*</label>
                                                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="rounded-xl border-slate-100" placeholder="Nguyễn Văn A" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="single-input">
                                                <label class="font-black text-[11px] uppercase tracking-widest text-slate-400 mb-2">Số điện thoại</label>
                                                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="rounded-xl border-slate-100" placeholder="Số điện thoại của bạn">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="single-input text-start">
                                        <label class="font-black text-[11px] uppercase tracking-widest text-slate-400 mb-2">Địa chỉ email*</label>
                                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="rounded-xl border-slate-100 bg-slate-50/50" readonly required>
                                        <span class="text-[9px] font-bold text-slate-400 uppercase mt-2 block"><i class="fa-light fa-lock-alt"></i> Liên hệ CSKH để thay đổi Email</span>
                                    </div>

                                    <div class="p-6 rounded-3xl border border-slate-50 bg-slate-50/20 text-start">
                                        <h3 class="text-sm font-black uppercase tracking-widest text-slate-800 mb-4">Địa chỉ mặc định</h3>
                                        <x-address-selector 
                                            :selected-province="$user->province_code"
                                            :selected-ward="$user->ward_code"
                                        />
                                        <div class="single-input mt-4">
                                            <label class="font-black text-[11px] uppercase tracking-widest text-slate-400 mb-2">Địa chỉ chi tiết</label>
                                            <input type="text" name="address_detail" id="address_detail" class="rounded-xl border-slate-100" placeholder="123 Đường Nguyễn Trãi..." value="{{ old('address_detail', $user->address_detail) }}">
                                        </div>
                                        <input type="hidden" name="address" id="full_address" value="{{ old('address', $user->address) }}">
                                    </div>

                                    <div class="p-6 rounded-3xl border border-rose-50 bg-rose-50/10 text-start">
                                        <h3 class="text-sm font-black uppercase tracking-widest text-rose-500 mb-6">Đổi mật khẩu bảo mật</h3>
                                        <div class="single-input mb-4">
                                            <label class="font-black text-[11px] uppercase tracking-widest text-rose-300 mb-2">Mật khẩu hiện tại</label>
                                            <input type="password" name="current_password" class="rounded-xl border-rose-100" placeholder="Xác nhận mật khẩu cũ">
                                        </div>
                                        <div class="row g-4">
                                            <div class="col-md-6">
                                                <div class="single-input">
                                                    <label class="font-black text-[11px] uppercase tracking-widest text-slate-400 mb-2">Mật khẩu mới</label>
                                                    <input type="password" name="password" class="rounded-xl border-slate-100" placeholder="Nhập mật khẩu mới">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="single-input">
                                                    <label class="font-black text-[11px] uppercase tracking-widest text-slate-400 mb-2">Xác nhận mật khẩu mới</label>
                                                    <input type="password" name="password_confirmation" class="rounded-xl border-slate-100" placeholder="Nhập lại mật khẩu mới">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <button type="submit" class="rts-btn btn-primary px-10 py-4 rounded-2xl w-100">Cập nhật thông tin tài khoản</button>
                                </form>
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
        .bg-slate-50\/10 { background-color: rgba(248, 250, 252, 0.1); }
        .bg-rose-50\/10 { background-color: rgba(255, 241, 242, 0.1); }
        .border-slate-50 { border-color: #f1f5f9; }
        .border-slate-100 { border-color: #f1f5f9; }
        .border-rose-50 { border-color: #fff1f2; }
        .border-rose-100 { border-color: #ffe4e6; }
        .text-slate-400 { color: #94a3b8; }
        .text-slate-500 { color: #64748b; }
        .text-slate-600 { color: #475569; }
        .text-slate-700 { color: #334155; }
        .text-slate-800 { color: #1e293b; }
        
        .hover-transform:hover { transform: translateY(-5px); }
        .min-h-\[500px\] { min-height: 500px; }
    </style>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const urlParams = new URLSearchParams(window.location.search);
            const tab = urlParams.get('tab');
            if (tab) {
                let tabMap = {
                    'orders': 'v-pills-profile-tab',
                    'address': 'v-pills-settings-tab',
                    'profile': 'v-pills-settingsa-tab'
                };
                let tabId = tabMap[tab];
                if (tabId) {
                    let tabEl = document.getElementById(tabId);
                    if (tabEl) {
                        const bsTab = new bootstrap.Tab(tabEl);
                        bsTab.show();
                    }
                }
            }

            const updateNewFullAddress = () => {
                const container = $('#newAddressForm .address-selector-container');
                const detail = $('#new_address_detail').val();
                const province = container.find('.address-province-name').val();
                const ward = container.find('.address-ward-name').val();
                const district = container.find('.address-district-name').val();
                let parts = [detail, ward, district, province].filter(Boolean);
                $('#new_full_address').val(parts.join(', '));
            }

            const updateProfileFullAddress = () => {
                const container = $('.account-details-area .address-selector-container');
                const detail = $('#address_detail').val();
                const province = container.find('.address-province-name').val();
                const ward = container.find('.address-ward-name').val();
                const district = container.find('.address-district-name').val();
                let parts = [detail, ward, district, province].filter(Boolean);
                $('#full_address').val(parts.join(', '));
            }

            $('#newAddressForm .address-selector-container, .account-details-area .address-selector-container').on('address:changed', function() {
                updateNewFullAddress();
                updateProfileFullAddress();
            });
            $('#new_address_detail, #address_detail').on('input', function() {
                updateNewFullAddress();
                updateProfileFullAddress();
            });

            setTimeout(updateProfileFullAddress, 1000);
        });
    </script>
@endsection