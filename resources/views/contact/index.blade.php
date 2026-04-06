@extends('layouts.app')

@section('title', 'Liên hệ')

@section('content')
    <x-breadcrumb :items="[
            ['label' => 'Liên hệ']
        ]" />

    <div class="rts-contact-main-wrapper-banner bg_image" style="background-image: url('{{ asset(setting('contact_banner_bg', 'theme/images/contact/01.jpg')) }}')">
        <div class="container">
            <div class="row">
                <div class="co-lg-12">
                    <div class="contact-banner-content text-center">
                        <h1 class="title">
                            {{ setting('contact_title', 'Liên hệ với chúng tôi') }}
                        </h1>
                        <p class="disc">
                            {{ setting('contact_subtitle', 'Chúng tôi luôn sẵn sàng lắng nghe và giải đáp mọi thắc mắc của bạn về dịch vụ và sản phẩm.') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="rts-map-contact-area rts-section-gap2">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <div class="contact-left-area-main-wrapper">
                        <h2 class="title">
                            {{ setting('contact_info_title', 'Thông tin liên hệ') }}
                        </h2>
                        <p class="disc">
                            {{ setting('contact_info_desc', 'Bạn có thể gửi câu hỏi hoặc góp ý cho chúng tôi. Đội ngũ hỗ trợ sẽ phản hồi bạn trong thời gian sớm nhất.') }}
                        </p>
                        <div class="location-single-card">
                            <div class="icon">
                                <i class="fa-light fa-location-dot"></i>
                            </div>
                            <div class="information">
                                <h3 class="title animated fadeIn">{{ setting('site_name', 'VietTinMart') }}</h3>
                                <p>{{ setting('site_address', setting('address', 'Chưa cập nhật địa chỉ...')) }}</p>
                                <a href="tel:{{ setting('hotline') }}" class="number">{{ setting('hotline', 'Chưa cập nhật hotline...') }}</a>
                                <a href="mailto:{{ setting('contact_email', setting('email', '')) }}" class="email">{{ setting('contact_email', setting('email', 'Chưa cập nhật email...')) }}</a>
                            </div>
                        </div>
                        
                        @if(setting('store_name_2'))
                        <div class="location-single-card">
                            <div class="icon">
                                <i class="fa-light fa-location-dot"></i>
                            </div>
                            <div class="information">
                                <h3 class="title animated fadeIn">{{ setting('store_name_2') }}</h3>
                                <p>{{ setting('address_2') }}</p>
                                <a href="tel:{{ setting('hotline_2') }}" class="number">{{ setting('hotline_2') }}</a>
                                <a href="mailto:{{ setting('email_2') }}" class="email">{{ setting('email_2') }}</a>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="col-lg-8 pl--50 pl_sm--5 pl_md--5">
                    <div class="contact-map-area">
                        {!! setting('google_maps_iframe', '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.5096836440156!2d106.6791963!3d10.7722744!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752f211a20768b%3A0xb9d114b308c0ef0!2zSOG7kyBDaMOtIE1pbmgsIFZp4buHdCBOYW0!5e0!3m2!1svi!2s!4v1716725338558!5m2!1svi!2s" width="100%" height="540" style="border:0;" allowfullscreen="" loading="lazy"></iframe>') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="rts-contact-form-area rts-section-gapBottom">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="bg_light-1 contact-form-wrapper-bg">
                        <div class="row">
                            <div class="col-lg-7 pr--30 pr_md--10 pr_sm--5">
                                <div class="contact-form-wrapper-1">
                                    <h3 class="title mb--50 animated fadeIn">{{ setting('contact_form_title', 'Gửi tin nhắn cho chúng tôi') }}</h3>
                                    <form action="{{ route('contact.send') }}" method="POST" class="contact-form-1">
                                        @csrf
                                        <div class="contact-form-wrapper--half-area">
                                            <div class="single">
                                                <input type="text" name="name" placeholder="Họ và tên*" required>
                                            </div>
                                            <div class="single">
                                                <input type="email" name="email" placeholder="Email*" required>
                                            </div>
                                        </div>
                                        <div class="single">
                                            <input type="text" name="subject" placeholder="Chủ đề*" required style="width: 100%; padding: 15px; border-radius: 5px; border: 1px solid #eee; margin-bottom: 20px;">
                                        </div>
                                        <textarea name="message" placeholder="Nội dung tin nhắn..." required></textarea>
                                        <button type="submit" class="rts-btn btn-primary mt--20">Gửi tin nhắn</button>
                                    </form>
                                </div>
                            </div>
                            <div class="col-lg-5 mt_md--30 mt_sm--30">
                                <div class="thumbnail-area">
                                    <img src="{{ asset(setting('contact_image', 'theme/images/contact/02.jpg')) }}" alt="contact_form">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @include('layouts.partials.service-bar')

@endsection