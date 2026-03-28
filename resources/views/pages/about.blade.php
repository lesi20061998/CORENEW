@extends('layouts.app')

@section('title', 'Giới thiệu - VietTinMart')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb-area">
    <div class="container">
        <div class="breadcrumb-content">
            <ul class="breadcrumb-list">
                <li><a href="{{ route('home') }}">Trang chủ</a></li>
                <li class="active">Giới thiệu</li>
            </ul>
        </div>
    </div>
</div>

<!-- About Section -->
<section class="about-area section-padding-tb">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="about-img">
                    <img src="{{ asset('theme/images/about/about-1.jpg') }}" alt="Về VietTinMart" class="img-fluid rounded">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="about-content">
                    <span class="sub-title">Câu chuyện của chúng tôi</span>
                    <h2 class="title">Chào mừng đến với <span>VietTinMart</span></h2>
                    <p>VietTinMart là nền tảng thương mại điện tử chuyên cung cấp thực phẩm tươi sạch, hữu cơ và các sản phẩm tiêu dùng chất lượng cao trực tiếp đến tay người tiêu dùng Việt Nam.</p>
                    <p>Được thành lập với sứ mệnh mang đến nguồn thực phẩm an toàn, minh bạch về nguồn gốc và giá cả hợp lý, chúng tôi kết nối trực tiếp với các nông trại, nhà sản xuất uy tín trên khắp Việt Nam.</p>
                    <div class="about-feature mt-4">
                        <div class="row">
                            <div class="col-6">
                                <div class="feature-item">
                                    <i class="fa-solid fa-leaf text-success fs-2 mb-2"></i>
                                    <h5>100% Hữu cơ</h5>
                                    <p class="small">Sản phẩm đạt tiêu chuẩn hữu cơ quốc tế</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="feature-item">
                                    <i class="fa-solid fa-truck-fast text-primary fs-2 mb-2"></i>
                                    <h5>Giao hàng nhanh</h5>
                                    <p class="small">Giao trong ngày tại nội thành</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats -->
        <div class="row mt-5 text-center">
            <div class="col-6 col-md-3 mb-4">
                <div class="stat-box p-4 border rounded">
                    <h3 class="text-primary fw-bold">10.000+</h3>
                    <p class="mb-0">Khách hàng tin tưởng</p>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-4">
                <div class="stat-box p-4 border rounded">
                    <h3 class="text-primary fw-bold">500+</h3>
                    <p class="mb-0">Sản phẩm đa dạng</p>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-4">
                <div class="stat-box p-4 border rounded">
                    <h3 class="text-primary fw-bold">50+</h3>
                    <p class="mb-0">Nhà cung cấp uy tín</p>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-4">
                <div class="stat-box p-4 border rounded">
                    <h3 class="text-primary fw-bold">5 năm</h3>
                    <p class="mb-0">Kinh nghiệm hoạt động</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
