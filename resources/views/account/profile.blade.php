@extends('layouts.app')

@section('body_class', 'shop-main-h')


@section('title', 'Thông tin tài khoản')

@section('content')
    <x-breadcrumb :items="[
            ['label' => 'Tài khoản', 'url' => route('profile')],
            ['label' => 'Thông tin cá nhân']
        ]" />

    <div class="account-area rts-section-gap bg-light">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="nav accout-dashborard-nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist"
                        aria-orientation="vertical">
                        <button class="nav-link active" id="v-pills-home-tab" data-bs-toggle="pill"
                            data-bs-target="#v-pills-home" type="button" role="tab" aria-controls="v-pills-home"
                            aria-selected="true"><i class="fa-regular fa-chart-line"></i>Bảng điều khiển</button>
                        <button class="nav-link" id="v-pills-profile-tab" data-bs-toggle="pill"
                            data-bs-target="#v-pills-profile" type="button" role="tab" aria-controls="v-pills-profile"
                            aria-selected="false"><i class="fa-regular fa-bag-shopping"></i>Đơn hàng</button>

                        <button class="nav-link" id="v-pills-settings-tab" data-bs-toggle="pill"
                            data-bs-target="#v-pills-settings" type="button" role="tab" aria-controls="v-pills-settings"
                            aria-selected="false"><i class="fa-sharp fa-regular fa-location-dot"></i>Địa chỉ của
                            tôi</button>
                        <button class="nav-link" id="v-pills-settingsa-tab" data-bs-toggle="pill"
                            data-bs-target="#v-pills-settingsa" type="button" role="tab" aria-controls="v-pills-settingsa"
                            aria-selected="false"><i class="fa-light fa-user"></i>Thông tin cá nhân</button>
                        <button class="nav-link text-danger" id="v-pills-logout-tab" type="button" role="tab"
                            aria-selected="false"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fa-light fa-right-from-bracket"></i> Đăng xuất
                        </button>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </div>
                <div class="col-lg-9 pl--50 pl_md--10 pl_sm--10 pt_md--30 pt_sm--30">
                    <div class="tab-content" id="v-pills-tabContent">
                        <div class="tab-pane fade active show" id="v-pills-home" role="tabpanel"
                            aria-labelledby="v-pills-home-tab" tabindex="0">
                            <div class="dashboard-account-area">
                                <h2 class="title">Xin chào {{ $user->name }}! <a href="javascript:void(0)"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                        class="ms-2 small fw-normal text-muted" style="font-size: 0.6em;">(Đăng xuất)</a>
                                </h2>
                                <p class="disc">
                                    Từ trang quản lý tài khoản, bạn có thể xem các đơn hàng gần đây, quản lý địa chỉ nhận
                                    hàng và thanh toán, cũng như chỉnh sửa mật khẩu và thông tin tài khoản cá nhân.
                                </p>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="v-pills-profile" role="tabpanel"
                            aria-labelledby="v-pills-profile-tab" tabindex="0">
                            <div class="order-table-account">
                                <div class="h2 title">Đơn hàng của bạn</div>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Mã đơn</th>
                                                <th>Ngày đặt</th>
                                                <th>Trạng thái</th>
                                                <th>Tổng cộng</th>
                                                <th>Hành động</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($orders as $order)
                                                <tr>
                                                    <td>#{{ $order->order_number }}</td>
                                                    <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                                    <td>
                                                        @php
                                                            $colors = [
                                                                'pending' => '#f59e0b',
                                                                'processing' => '#3b82f6',
                                                                'shipping' => '#8b5cf6',
                                                                'delivered' => '#10b981',
                                                                'completed' => '#059669',
                                                                'cancelled' => '#ef4444',
                                                            ];
                                                            $color = $colors[$order->status] ?? '#6b7280';
                                                        @endphp
                                                        <span class="badge"
                                                            style="background-color: {{ $color }}; color: white; border-radius: 4px; padding: 4px 8px;">{{ $order->status_label }}</span>
                                                    </td>
                                                    <td>{{ number_format($order->total, 0, ',', '.') }}đ</td>
                                                    <td><a href="{{ route('order.detail', $order) }}"
                                                            class="btn-small d-block">Xem</a></td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center py-4">Bạn chưa có đơn hàng nào.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-settings-tab" tabindex="0">
                            <div class="address-management">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h2 class="title mb-0">Địa chỉ của tôi</h2>
                                    <button class="rts-btn btn-primary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#addAddressForm">
                                        <i class="fa-regular fa-plus me-2"></i>Thêm địa chỉ mới
                                    </button>
                                </div>

                                {{-- Error Display --}}
                                @if ($errors->any())
                                    <div class="alert alert-danger mb-4">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                {{-- Success Display --}}
                                @if (session('success'))
                                    <div class="alert alert-success mb-4">
                                        {{ session('success') }}
                                    </div>
                                @endif

                                {{-- Add Address Form --}}
                                <div class="collapse mb-5" id="addAddressForm">
                                    <div class="card card-body border-0 shadow-sm p-4">
                                        <h3 class="h5 mb-4">Địa chỉ mới</h3>
                                        <form action="{{ route('address.store') }}" method="POST" id="newAddressForm">
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Tên người nhận*</label>
                                                    <input type="text" name="receiver_name" class="form-control" placeholder="Ví dụ: Nguyễn Văn A" required>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Số điện thoại*</label>
                                                    <input type="text" name="receiver_phone" class="form-control" placeholder="0123 456 789" required>
                                                </div>
                                            </div>

                                            <x-address-selector id="new-addr" />

                                            <div class="mb-3">
                                                <label class="form-label">Địa chỉ chi tiết (Sỗ nhà, tên đường...)*</label>
                                                <input type="text" name="address_detail" id="new_address_detail" class="form-control" placeholder="Ví dụ: 123 Đường Nguyễn Trãi" required>
                                            </div>

                                            <input type="hidden" name="full_address" id="new_full_address">

                                            <div class="form-check mb-4">
                                                <input class="form-check-input" type="checkbox" name="is_default" id="set_as_default">
                                                <label class="form-check-label" for="set_as_default">
                                                    Đặt làm địa chỉ mặc định
                                                </label>
                                            </div>

                                            <div class="d-flex gap-2">
                                                <button type="submit" class="rts-btn btn-primary">Lưu địa chỉ</button>
                                                <button type="button" class="rts-btn btn-secondary" data-bs-toggle="collapse" data-bs-target="#addAddressForm">Hủy</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                {{-- Address List --}}
                                <div class="row g-4">
                                    @forelse($user->addresses as $address)
                                        <div class="col-md-6">
                                            <div class="address-item p-4 border rounded bg-white position-relative {{ $address->is_default ? 'border-primary' : '' }}">
                                                @if($address->is_default)
                                                    <span class="badge bg-primary position-absolute top-0 end-0 m-3 px-2 py-1" style="font-size: 0.65rem;">Mặc định</span>
                                                @endif
                                                <h4 class="h6 mb-2">{{ $address->receiver_name }}</h4>
                                                <p class="text-muted small mb-1"><i class="fa-regular fa-phone me-2"></i>{{ $address->receiver_phone }}</p>
                                                <p class="text-dark small mb-3"><i class="fa-regular fa-location-dot me-2"></i>{{ $address->full_address }}</p>
                                                
                                                <div class="d-flex gap-3 align-items-center mt-auto pt-2 border-top">
                                                    @if(!$address->is_default)
                                                        <form action="{{ route('address.set-default', $address->id) }}" method="POST">
                                                            @csrf
                                                            <button type="submit" class="btn btn-link p-0 text-primary small text-decoration-none">Đặt mặc định</button>
                                                        </form>
                                                    @endif
                                                    <form action="{{ route('address.destroy', $address->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa địa chỉ này?')">
                                                        @csrf
                                                        <button type="submit" class="btn btn-link p-0 text-danger small text-decoration-none">Xóa</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-12 text-center py-5 bg-white border rounded">
                                            <i class="fa-light fa-location-dot fa-3x mb-3 text-muted"></i>
                                            <p class="text-muted">Bạn chưa có địa chỉ nào lưu lại.</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="v-pills-settingsa" role="tabpanel"
                            aria-labelledby="v-pills-settingsa-tab" tabindex="0">
                            <form action="{{ route('account.profile.update') }}" method="POST" class="account-details-area">
                                @csrf
                                @method('PUT')
                                <h2 class="title">Thông tin tài khoản</h2>

                                @if(session('success'))
                                    <div class="alert alert-success alert-dismissible fade show mb-4 small" role="alert">
                                        {{ session('success') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                    </div>
                                @endif

                                <div class="input-half-area">
                                    <div class="single-input">
                                        <label>Họ và tên*</label>
                                        <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                            placeholder="Ví dụ: Nguyễn Văn A" required>
                                        @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="single-input">
                                        <label>Số điện thoại</label>
                                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                                            placeholder="Số điện thoại của bạn">
                                        @error('phone') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="single-input mb-4">
                                    <label>Địa chỉ email*</label>
                                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                        placeholder="email@example.com" required>
                                    @error('email') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>

                                <x-address-selector 
                                    :selected-province="$user->province_code"
                                    :selected-ward="$user->ward_code"
                                />

                                <div class="single-input mb-4">
                                    <label>Địa chỉ chi tiết (Số nhà, tên đường...)</label>
                                    <input type="text" name="address_detail" id="address_detail"
                                        placeholder="Ví dụ: 123 Đường Nguyễn Trãi..." value="{{ old('address_detail', $user->address_detail) }}">
                                    @error('address_detail') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                                <input type="hidden" name="address" id="full_address"
                                    value="{{ old('address', $user->address) }}">

                                <div class="border-top mt--30 pt--30">
                                    <h2 class="title mb-4">Đổi mật khẩu (Chỉ nhập nếu muốn đổi)</h2>
                                    <div class="single-input mb-4">
                                        <label>Mật khẩu hiện tại</label>
                                        <input type="password" name="current_password" placeholder="Nhập mật khẩu hiện tại để xác nhận">
                                        @error('current_password') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="input-half-area">
                                        <div class="single-input">
                                            <label>Mật khẩu mới</label>
                                            <input type="password" name="password" placeholder="Mật khẩu mới">
                                            @error('password') <span class="text-danger small">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="single-input">
                                            <label>Xác nhận mật khẩu mới</label>
                                            <input type="password" name="password_confirmation"
                                                placeholder="Nhập lại mật khẩu mới">
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="rts-btn btn-primary">Lưu thay đổi</button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    @section('scripts')
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const API_BASE = 'https://provinces.open-api.vn/api';

                // Tabs auto-switcher
                const urlParams = new URLSearchParams(window.location.search);
                const tab = urlParams.get('tab');
                if (tab) {
                    let tabMap = {
                        'orders': 'v-pills-profile-tab',
                        'track': 'v-pills-messages-tab',
                        'address': 'v-pills-settings-tab',
                        'profile': 'v-pills-settingsa-tab'
                    };
                    let tabId = tabMap[tab];
                    if (tabId) {
                        let tabEl = document.getElementById(tabId);
                        if (tabEl) tabEl.click();
                    }
                }

                // Address Selector Integration (Both forms)
                // Use the custom 'address:changed' event from the component
                $('#newAddressForm .address-selector-container').on('address:changed', function(e) {
                    updateNewFullAddress();
                });

                $('.account-details-area .address-selector-container').on('address:changed', function(e) {
                    updateProfileFullAddress();
                });

                // Standard change listeners for nice-select or vanilla select as fallback
                $('#newAddressForm .address-province-select, #newAddressForm .address-ward-select').on('change', function() {
                    updateNewFullAddress();
                });

                $('.account-details-area .address-province-select, .account-details-area .address-ward-select').on('change', function() {
                    updateProfileFullAddress();
                });

                // Detail Change
                $('#new_address_detail').on('input', function () {
                    updateNewFullAddress();
                });

                $('#address_detail').on('input', function () {
                    updateProfileFullAddress();
                });

                function updateNewFullAddress() {
                    const container = $('#newAddressForm .address-selector-container');
                    const detail = $('#new_address_detail').val();
                    const province = container.find('.address-province-name').val();
                    const ward = container.find('.address-ward-name').val();
                    const district = container.find('.address-district-name').val();

                    let parts = [];
                    if (detail) parts.push(detail);
                    if (ward) parts.push(ward);
                    if (district) parts.push(district);
                    if (province) parts.push(province);

                    const full = parts.join(', ');
                    $('#new_full_address').val(full);
                }

                function updateProfileFullAddress() {
                    const container = $('.account-details-area .address-selector-container');
                    const detail = $('#address_detail').val();
                    const province = container.find('.address-province-name').val();
                    const ward = container.find('.address-ward-name').val();
                    const district = container.find('.address-district-name').val();

                    let parts = [];
                    if (detail) parts.push(detail);
                    if (ward) parts.push(ward);
                    if (district) parts.push(district);
                    if (province) parts.push(province);

                    const full = parts.join(', ');
                    $('#full_address').val(full);
                }

                // Initial sync for profile
                setTimeout(() => {
                    updateProfileFullAddress();
                }, 1000);
            });
        </script>
    @endsection
@endsection