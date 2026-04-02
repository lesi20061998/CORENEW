@extends('layouts.app')

@section('title', 'Liên hệ')

@section('content')
    <x-breadcrumb :items="[
            ['label' => 'Liên hệ']
        ]" />

    <div class="rts-contact-area rts-section-gap">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="contact-inner">
                        <div class="row g-5">
                            <div class="col-lg-4">
                                <div class="contact-info bg_light-1 p-5 rounded">
                                    <h3 class="title mb-4">Thông tin liên hệ</h3>
                                    <div class="single-info mb-4">
                                        <div class="icon"><i class="fa-light fa-phone"></i></div>
                                        <div class="content">
                                            <p class="mb-0">Hotline</p>
                                            <h6 class="mb-0">{{ setting('hotline', '+84 123 456 789') }}</h6>
                                        </div>
                                    </div>
                                    <div class="single-info mb-4">
                                        <div class="icon"><i class="fa-light fa-envelope"></i></div>
                                        <div class="content">
                                            <p class="mb-0">Email</p>
                                            <h6 class="mb-0">{{ setting('email', 'contact@viettinmart.com') }}</h6>
                                        </div>
                                    </div>
                                    <div class="single-info">
                                        <div class="icon"><i class="fa-light fa-location-dot"></i></div>
                                        <div class="content">
                                            <p class="mb-0">Địa chỉ</p>
                                            <h6 class="mb-0">{{ setting('address', 'Hồ Chí Minh, Việt Nam') }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="contact-form-area p-5 rounded border">
                                    <h3 class="title mb-4">Gửi tin nhắn cho chúng tôi</h3>
                                    <form action="{{ route('contact.send') }}" method="POST">
                                        @csrf
                                        <div class="row g-4">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="mb-2">Họ và tên*</label>
                                                    <input type="text" name="name" class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="mb-2">Email*</label>
                                                    <input type="email" name="email" class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label class="mb-2">Chủ đề</label>
                                                    <input type="text" name="subject" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label class="mb-2">Tin nhắn*</label>
                                                    <textarea name="message" class="form-control" rows="5"
                                                        required></textarea>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <button type="submit" class="rts-btn btn-primary">Gửi tin nhắn</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection