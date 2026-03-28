@extends('layouts.app')

@section('title', 'Liên hệ - VietTinMart')

@section('content')
<div class="breadcrumb-area">
    <div class="container">
        <div class="breadcrumb-content">
            <ul class="breadcrumb-list">
                <li><a href="{{ route('home') }}">Trang chủ</a></li>
                <li class="active">Liên hệ</li>
            </ul>
        </div>
    </div>
</div>

<section class="contact-area section-padding-tb">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-5">
                <div class="contact-info">
                    <h3 class="mb-4">Thông tin liên hệ</h3>
                    <div class="info-item d-flex gap-3 mb-4">
                        <i class="fa-solid fa-location-dot fs-4 text-primary mt-1"></i>
                        <div>
                            <h6>Địa chỉ</h6>
                            <p class="text-muted mb-0">{{ setting('address', '123 Đường ABC, Quận 1, TP.HCM') }}</p>
                        </div>
                    </div>
                    <div class="info-item d-flex gap-3 mb-4">
                        <i class="fa-solid fa-phone fs-4 text-primary mt-1"></i>
                        <div>
                            <h6>Điện thoại</h6>
                            <p class="text-muted mb-0"><a href="tel:{{ setting('phone', '1800 1234') }}">{{ setting('phone', '1800 1234') }}</a></p>
                        </div>
                    </div>
                    <div class="info-item d-flex gap-3 mb-4">
                        <i class="fa-solid fa-envelope fs-4 text-primary mt-1"></i>
                        <div>
                            <h6>Email</h6>
                            <p class="text-muted mb-0"><a href="mailto:{{ setting('email', 'info@viettinmart.vn') }}">{{ setting('email', 'info@viettinmart.vn') }}</a></p>
                        </div>
                    </div>
                    <div class="info-item d-flex gap-3">
                        <i class="fa-solid fa-clock fs-4 text-primary mt-1"></i>
                        <div>
                            <h6>Giờ làm việc</h6>
                            <p class="text-muted mb-0">Thứ 2 - Thứ 7: 8:00 - 22:00<br>Chủ nhật: 9:00 - 20:00</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="contact-form-wrap p-4 border rounded shadow-sm">
                    <h3 class="mb-4">Gửi tin nhắn cho chúng tôi</h3>

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form action="{{ route('contact.send') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                    placeholder="Họ và tên *" value="{{ old('name') }}">
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                    placeholder="Email *" value="{{ old('email') }}">
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <input type="text" name="subject" class="form-control"
                                    placeholder="Tiêu đề" value="{{ old('subject') }}">
                            </div>
                            <div class="col-12">
                                <textarea name="message" rows="5" class="form-control @error('message') is-invalid @enderror"
                                    placeholder="Nội dung tin nhắn *">{{ old('message') }}</textarea>
                                @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <button type="submit" class="rts-btn btn-primary">
                                    Gửi tin nhắn <i class="fa-solid fa-paper-plane ms-2"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
