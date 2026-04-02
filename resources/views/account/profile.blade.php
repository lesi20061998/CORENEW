@extends('layouts.app')

@section('title', 'Thông tin tài khoản')

@section('content')
    <x-breadcrumb :items="[
            ['label' => 'Tài khoản', 'url' => route('profile')],
            ['label' => 'Thông tin cá nhân']
        ]" />

    <div class="account-tab-area-start rts-section-gap">
        <div class="container-2">
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

                        <div class="tab-pane fade" id="v-pills-settings" role="tabpanel"
                            aria-labelledby="v-pills-settings-tab" tabindex="0">
                            <div class="shipping-address-billing-address-account">
                                <div class="half">
                                    <h2 class="title">Địa chỉ mặc định</h2>
                                    <p class="address">
                                        {{ $user->address ?: 'Chưa cập nhật địa chỉ.' }}
                                    </p>
                                    <a href="#"
                                        onclick="document.getElementById('v-pills-settingsa-tab').click(); return false;">Sửa</a>
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

                                <div class="half-input-wrapper">
                                    <div class="single-input mb-4">
                                        <label>Tỉnh / Thành phố*</label>
                                        <select id="province" name="province_code" class="form-select" required>
                                            <option value="">Chọn Tỉnh / Thành phố</option>
                                        </select>
                                        <input type="hidden" name="province_name" id="province_name">
                                    </div>
                                    <div class="single-input mb-4">
                                        <label>Quận/Huyện - Phường/Xã*</label>
                                        <select id="ward" name="ward_code" class="form-select" required disabled>
                                            <option value="">Chọn địa chỉ</option>
                                        </select>
                                        <input type="hidden" name="ward_name" id="ward_name">
                                        <input type="hidden" name="district_name" id="district_name">
                                    </div>
                                </div>

                                <div class="single-input mb-4">
                                    <label>Địa chỉ chi tiết (Số nhà, tên đường...)</label>
                                    <input type="text" name="address_detail" id="address_detail"
                                        placeholder="Ví dụ: 123 Đường Nguyễn Trãi..." value="{{ old('address_detail') }}">
                                    @error('address') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                                <input type="hidden" name="address" id="full_address"
                                    value="{{ old('address', $user->address) }}">

                                {{-- Password field placeholders (optional, can be implemented later) --}}
                                {{-- <div class="border-top mt--30 pt--30">
                                    <h2 class="title mb-4">Đổi mật khẩu</h2>
                                    <div class="single-input mb-4 text-start">
                                        <input type="password" name="current_password" placeholder="Mật khẩu hiện tại">
                                    </div>
                                    <div class="input-half-area">
                                        <div class="single-input text-start">
                                            <input type="password" name="password" placeholder="Mật khẩu mới">
                                        </div>
                                        <div class="single-input text-start">
                                            <input type="password" name="password_confirmation"
                                                placeholder="Xác nhận mật khẩu mới">
                                        </div>
                                    </div>
                                </div> --}}

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

                // Address API Implementation
                function initSelect2(selector, placeholder) {
                    const $el = $(selector);
                    $el.select2({
                        placeholder: placeholder,
                        allowClear: false,
                        width: '100%',
                        language: { noResults: () => "Không tìm thấy kết quả" }
                    });
                }

                initSelect2('#province', 'Chọn Tỉnh / Thành phố');
                initSelect2('#ward', 'Chọn địa chỉ');

                // Load Provinces
                $.getJSON(`${API_BASE}/p/`, function (data) {
                    let html = '<option value=""></option>';
                    data.forEach(p => {
                        html += `<option value="${p.code}" data-name="${p.name}">${p.name}</option>`;
                    });
                    $('#province').html(html).trigger('change.select2');
                });

                // Province Change
                $('#province').on('change', function () {
                    const pCode = $(this).val();
                    const pName = $(this).find(':selected').data('name');
                    $('#province_name').val(pName);
                    updateFullAddress();

                    if (!pCode) {
                        $('#ward').html('<option value=""></option>').prop('disabled', true).trigger('change.select2');
                        return;
                    }

                    $('#ward').prop('disabled', true).html('<option value="">Đang tải...</option>').trigger('change.select2');

                    $.getJSON(`${API_BASE}/p/${pCode}?depth=3`, function (data) {
                        let html = '<option value=""></option>';
                        if (data.districts) {
                            data.districts.forEach(d => {
                                if (d.wards) {
                                    d.wards.forEach(w => {
                                        html += `<option value="${w.code}" data-name="${w.name}" data-district="${d.name}">${w.name} (${d.name})</option>`;
                                    });
                                }
                            });
                        }
                        $('#ward').prop('disabled', false).html(html).trigger('change.select2');
                    });
                });

                // Ward Change
                $('#ward').on('change', function () {
                    const selected = $(this).find(':selected');
                    $('#ward_name').val(selected.data('name'));
                    $('#district_name').val(selected.data('district'));
                    updateFullAddress();
                });

                // Detail Change
                $('#address_detail').on('input', function () {
                    updateFullAddress();
                });

                function updateFullAddress() {
                    const detail = $('#address_detail').val();
                    const ward = $('#ward_name').val();
                    const district = $('#district_name').val();
                    const province = $('#province_name').val();

                    let parts = [];
                    if (detail) parts.push(detail);
                    if (ward) parts.push(ward);
                    if (district) parts.push(district);
                    if (province) parts.push(province);

                    $('#full_address').val(parts.join(', '));
                }
            });
        </script>
    @endsection
@endsection