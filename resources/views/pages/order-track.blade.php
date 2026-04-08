@extends('layouts.app')

@section('title', 'Theo dõi đơn hàng')

@section('content')
<div class="rts-navigation-area-breadcrumb bg_light-1">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="navigator-breadcrumb-wrapper">
                    <a href="{{ route('home') }}">Trang chủ</a>
                    <i class="fa-regular fa-chevron-right"></i>
                    <a class="current" href="#">Theo dõi đơn hàng</a>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- track order area start -->
<div class="track-order-area rts-section-gap py--80">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-8 col-lg-10">
                <div class="tracing-order-account">
                    <h2 class="title text-center mb--15" style="font-size: 32px; font-weight: 700;">Theo dõi đơn hàng</h2>
                    <p class="text-center mb--40 text-muted mx-auto" style="max-width: 700px; font-size: 16px; line-height: 1.6;">
                        Vui lòng nhập mã đơn hàng và email bạn đã sử dụng để kiểm tra trạng thái đơn hàng hiện tại.
                    </p>

                    @if(isset($error))
                        <div class="alert alert-danger mb--30 text-center shadow-sm border-0 rounded-pill p-3 animate__animated animate__fadeIn">
                            <i class="fa-solid fa-circle-exclamation me-2"></i> {{ $error }}
                        </div>
                    @endif

                    <div class="tracking-form-card p--40 mb--50 shadow-sm border-radius-12 bg-white border border-light">
                        <form action="{{ route('order.track') }}" method="GET" class="order-tracking">
                            <div class="row g-4 d-flex align-items-end">
                                <div class="col-md-5">
                                    <div class="single-input">
                                        <label for="order-id" class="mb--10 font-bold text-heading small opacity-75">MÃ ĐƠN HÀNG</label>
                                        <div class="input-wrapper position-relative">
                                            <i class="fa-light fa-hashtag position-absolute start-0 top-50 translate-middle-y ms-3 text-muted"></i>
                                            <input name="order_id" value="{{ request('order_id') }}" id="order-id" type="text" placeholder="ORD-20240401-0001" required="" style="width: 100%; padding: 13px 15px 13px 40px; border-radius: 8px; border: 1px solid #eee; background: #fcfcfc;">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="single-input">
                                        <label for="order-idt" class="mb--10 font-bold text-heading small opacity-75">EMAIL THANH TOÁN</label>
                                        <div class="input-wrapper position-relative">
                                            <i class="fa-light fa-envelope position-absolute start-0 top-50 translate-middle-y ms-3 text-muted"></i>
                                            <input name="email" value="{{ request('email') }}" id="order-idt" type="email" placeholder="example@email.com" required="" style="width: 100%; padding: 13px 15px 13px 40px; border-radius: 8px; border: 1px solid #eee; background: #fcfcfc;">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2 text-center text-md-start">
                                    <button type="submit" class="rts-btn btn-primary px-3 py-3 rounded shadow-sm w-100" style="height: 50px;">
                                        KIỂM TRA
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    @if(isset($order))
                        <div class="order-tracking-result bg-white border-radius-12 shadow-lg overflow-hidden animate__animated animate__fadeInUp">
                            <!-- Result Header -->
                            <div class="result-header p--40 text-white d-flex justify-content-between align-items-center flex-wrap gap-4" style="background: linear-gradient(135deg, var(--color-primary) 0%, #4a7d1a 100%); min-height: 120px;">
                                <div class="header-content">
                                    <span class="text-uppercase small fw-bold opacity-75 mb-2 d-block" style="font-size: 12px; letter-spacing: 1.5px;">TRẠNG THÁI ĐƠN HÀNG</span>
                                    <h2 class="mb-0 text-white fw-bold" style="font-size: 28px;">{{ $order->order_number }}</h2>
                                </div>
                                <div class="header-status">
                                    <span class="badge bg-white text-dark rounded-pill px-4 py-3 fw-bold shadow-sm d-inline-flex align-items-center" style="font-size: 14px;">
                                        <i class="fa-solid fa-circle me-2 text-{{ $order->status_color }}" style="font-size: 10px;"></i>
                                        {{ $order->status_label }}
                                    </span>
                                </div>
                            </div>

                            <!-- Tracking Stepper -->
                            <div class="tracking-stepper p--50 border-bottom bg-white">
                                @php
                                    $steps = [
                                        'pending' => ['icon' => 'fa-file-alt', 'label' => 'Chờ xác nhận'],
                                        'confirmed' => ['icon' => 'fa-check-circle', 'label' => 'Đã xác nhận'],
                                        'processing' => ['icon' => 'fa-box', 'label' => 'Đang xử lý'],
                                        'shipping' => ['icon' => 'fa-shipping-fast', 'label' => 'Đang giao'],
                                        'delivered' => ['icon' => 'fa-home', 'label' => 'Đã giao']
                                    ];
                                    
                                    $currentStatus = $order->status;
                                    // Map terminal or other statuses to closest flow step
                                    if ($currentStatus == 'completed') $currentStatus = 'delivered';
                                    
                                    $orderIndex = array_search($currentStatus, array_keys($steps));
                                    // If status is not in flow (e.g. cancelled, refunded), handle visualization
                                    if ($orderIndex === false) {
                                        // Special case for refunded/cancelled
                                        $orderIndex = -1; // No steps fully highlighted in the main flow
                                    }
                                @endphp

                                <div class="stepper-wrapper d-flex justify-content-between position-relative px-3">
                                    <div class="stepper-line position-absolute w-100" style="height: 6px; background: #f4f4f4; top: 24px; left: 0; z-index: 0; border-radius: 10px;"></div>
                                    @if($orderIndex >= 0)
                                    <div class="stepper-line-active position-absolute" style="height: 6px; background: var(--color-primary); top: 24px; left: 0; z-index: 0; width: {{ ($orderIndex / (count($steps) - 1)) * 100 }}%; transition: width 1s ease-in-out; border-radius: 10px;"></div>
                                    @endif
                                    
                                    @foreach($steps as $key => $step)
                                        @php 
                                            $isActive = $orderIndex !== false && array_search($key, array_keys($steps)) <= $orderIndex;
                                            $isExact = $orderIndex !== false && array_search($key, array_keys($steps)) == $orderIndex;
                                        @endphp
                                        <div class="step-item text-center position-relative z-index-3 {{ $isActive ? 'active' : '' }} {{ $isExact ? 'exact' : '' }}">
                                            <div class="step-icon mx-auto d-flex align-items-center justify-content-center rounded-circle" style="width: 54px; height: 54px; font-size: 18px; transition: all 0.3s ease;">
                                                <i class="fa-solid {{ $step['icon'] }}"></i>
                                            </div>
                                            <p class="step-label mt-4 fw-bold mb-0 text-uppercase text-nowrap" style="font-size: 11px; letter-spacing: 0.5px;">{{ $step['label'] }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="p--40 bg-white">
                                <div class="row g-5">
                                    <!-- Order Info -->
                                    <div class="col-lg-7">
                                        <h6 class="mb--30 section-title fw-bold text-uppercase d-flex align-items-center">
                                            <i class="fa-light fa-list-check me-2 text-primary"></i> Danh sách sản phẩm
                                        </h6>
                                        <div class="product-list pe-lg-3">
                                            @foreach($order->items as $item)
                                                <div class="product-item d-flex align-items-center p-3 border-radius-8 mb-3 border border-light-2 hover-shadow transition">
                                                    <div class="product-thumb border-radius-5 overflow-hidden me-3 shadow-sm border" style="width: 70px; height: 70px; flex-shrink: 0; background: #fff;">
                                                        @if($item->image)
                                                            <img src="{{ $item->image_url }}" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                                                        @else
                                                            <img src="{{ asset('theme/images/no-image.png') }}" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                                                        @endif
                                                    </div>
                                                    <div class="product-info flex-grow-1">
                                                        <h6 class="mb-1 text-heading fw-bold" style="font-size: 15px;">{{ $item->product_name }}</h6>
                                                        @if($item->variant_label)
                                                            <small class="text-muted d-block mb-1">{{ $item->variant_label }}</small>
                                                        @endif
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <span class="text-muted small fw-medium">{{ number_format($item->price, 0, ',', '.') }}đ × {{ $item->quantity }}</span>
                                                            <span class="fw-bold text-primary" style="font-size: 16px;">{{ number_format($item->total, 0, ',', '.') }}đ</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Summary Sidebar -->
                                    <div class="col-lg-5">
                                        <!-- Shipping Info -->
                                        <div class="info-card p-4 border-radius-10 bg-light-soft mb-4 border border-faint-1 shadow-sm">
                                            <h6 class="mb-4 text-uppercase small text-muted font-bold d-flex align-items-center" style="font-size: 12px; letter-spacing: 1px;">
                                                <i class="fa-light fa-truck me-2"></i> Giao nhận & Thanh toán
                                            </h6>
                                            <div class="d-flex mb--25 gap-3">
                                                <div class="icon-circle text-primary" style="font-size: 20px; width: 24px;"><i class="fa-light fa-location-dot"></i></div>
                                                <div>
                                                    <p class="mb-1 text-heading fw-bold small">Địa chỉ nhận hàng</p>
                                                    <p class="mb-0 text-muted small lh-base">{{ $order->shipping_address }}</p>
                                                </div>
                                            </div>
                                            <div class="d-flex gap-3">
                                                <div class="icon-circle text-primary" style="font-size: 20px; width: 24px;"><i class="fa-light fa-credit-card"></i></div>
                                                <div>
                                                    <p class="mb-1 text-heading fw-bold small">Phương thức & Trạng thái</p>
                                                    <p class="mb-1 text-muted small">Cổng: <span class="text-heading fw-bold">{{ strtoupper($order->payment_method) }}</span></p>
                                                    <p class="mb-0 text-muted small">Tình trạng: <span class="text-{{ $order->payment_status == 'paid' ? 'success' : 'danger' }} fw-bold">{{ $order->payment_status_label }}</span></p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Summary Card -->
                                        <div class="summary-card p-4 border-radius-10 bg_primary-light border-0 shadow-sm">
                                            <h6 class="mb-4 text-uppercase small text-primary font-bold text-center" style="font-size: 12px; letter-spacing: 1px;">TỔNG KẾT HÓA ĐƠN</h6>
                                            <div class="d-flex justify-content-between mb-3 text-dark small">
                                                <span class="opacity-70 fw-medium">Tạm tính ({{ count($order->items) }} món)</span>
                                                <span class="fw-bold">{{ number_format($order->subtotal, 0, ',', '.') }}đ</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-3 text-dark small">
                                                <span class="opacity-70 fw-medium">Phí vận chuyển</span>
                                                <span class="fw-bold">{{ number_format($order->shipping_fee, 0, ',', '.') }}đ</span>
                                            </div>
                                            @if($order->discount > 0)
                                                <div class="d-flex justify-content-between mb-3 text-danger small">
                                                    <span class="opacity-70 fw-medium">Khuyến mãi áp dụng</span>
                                                    <span class="fw-bold">-{{ number_format($order->discount, 0, ',', '.') }}đ</span>
                                                </div>
                                            @endif
                                            <div class="mt-4 pt-3 border-top border-primary border-opacity-10 d-flex justify-content-between align-items-end">
                                                <h6 class="mb-0 fw-bold text-dark">THÀNH TIỀN</h6>
                                                <h4 class="mb-0 text-primary fw-extrabold" style="font-size: 26px;">{{ number_format($order->total, 0, ',', '.') }}đ</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Result Footer -->
                            <div class="p--30 bg-light-soft text-center border-top">
                                <p class="mb-0 text-muted small">Mọi khiếu nại hoặc thắc mắc, vui lòng gọi Hotline: <a href="tel:{{ setting('hotline', '02345697871') }}" class="text-primary font-bold ms-1" style="font-size: 15px;">{{ setting('hotline', '02345697871') }}</a></p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<!-- track order area end -->
@endsection

@push('styles')
<style>
    /* Theme Color Mapping */
    .text-yellow { color: var(--color-warning); }
    .text-blue { color: var(--color-info); }
    .text-purple { color: var(--color-secondary); }
    .text-green { color: var(--color-success); }
    .text-red { color: var(--color-danger); }
    .text-gray { color: var(--color-body); }

    /* Custom Helpers */
    .bg-light-soft { background-color: #fbfcfa; }
    .bg_primary-light { background-color: rgba(98, 157, 35, 0.04); }
    .border-light-2 { border: 1px solid #f2f2f2; }
    .border-faint-1 { border: 1px solid #f8f8f8; }
    .border-radius-12 { border-radius: 12px; }
    .border-radius-10 { border-radius: 10px; }
    .border-radius-8 { border-radius: 8px; }
    .border-radius-5 { border-radius: 5px; }
    .hover-shadow:hover { box-shadow: 0 8px 20px rgba(0,0,0,0.05); }
    .transition { transition: all 0.3s ease; }
    .fw-extrabold { font-weight: 800; }
    
    /* Tracking Form */
    .tracking-form-card .single-input input:focus {
        border-color: var(--color-primary);
        box-shadow: 0 0 0 4px rgba(98, 157, 35, 0.1);
        background: #fff;
    }

    /* Result UI */
    .section-title {
        display: inline-block;
        position: relative;
        padding-bottom: 12px;
        font-size: 16px;
        letter-spacing: 0.5px;
        color: var(--color-heading-1);
    }
    .section-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 40px;
        height: 3px;
        background: var(--color-primary);
        border-radius: 5px;
    }

    /* Stepper Alignment and Colors */
    .stepper-line {
        background: #f0f0f0;
    }
    .step-item .step-icon {
        border: 4px solid #f0f0f0;
        background-color: #fff;
        color: var(--color-primary);
        z-index: 5;
        box-shadow: 0 4px 10px rgba(0,0,0,0.03);
    }
    .step-item .step-label {
        color: #999; /* Forced default for inactive labels to avoid theme colors */
        font-weight: 500;
        transition: color 0.3s ease;
    }
    .step-item.active .step-icon {
        border-color: var(--color-primary);
        background-color: var(--color-primary);
        color: #fff;
        box-shadow: 0 0 0 8px rgba(98, 157, 35, 0.15);
    }
    .step-item.active .step-label {
        color: var(--color-primary);
        font-weight: 700;
    }
    .step-item.exact .step-icon {
        animation: pulse-primary 1.2s infinite ease-in-out;
    }
    
    @keyframes pulse-primary {
        0% { box-shadow: 0 0 0 0 rgba(98, 157, 35, 0.6); }
        70% { box-shadow: 0 0 0 15px rgba(98, 157, 35, 0); }
        100% { box-shadow: 0 0 0 0 rgba(98, 157, 35, 0); }
    }

    /* Custom Spacers */
    .p--50 { padding: 50px !important; }
    .p--40 { padding: 40px !important; }
    .p--30 { padding: 30px !important; }
    .py--80 { padding-top: 80px; padding-bottom: 80px; }
    .mb--25 { margin-bottom: 25px !important; }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .result-header { padding: 30px !important; text-align: center; justify-content: center !important; }
        .p--50, .p--40 { padding: 25px !important; }
        .stepper-wrapper { flex-direction: column; gap: 40px; padding-left: 50px; align-items: flex-start; }
        .stepper-line, .stepper-line-active { width: 6px !important; height: 100% !important; top: 0 !important; left: 24px !important; }
        .step-item { display: flex; align-items: center; text-align: left; gap: 20px; }
        .step-item .step-label { margin-top: 0 !important; font-size: 13px !important; }
        .step-item .step-icon { width: 44px !important; height: 44px !important; font-size: 16px !important; }
    }
</style>
@endpush