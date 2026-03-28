@php
    $title       = $config['title']       ?? 'Đăng ký nhận ưu đãi';
    $subtitle    = $config['subtitle']    ?? 'Nhận thông báo khuyến mãi mới nhất từ chúng tôi';
    $placeholder = $config['placeholder'] ?? 'Nhập địa chỉ email của bạn';
    $btnText     = $config['btn_text']    ?? 'Đăng ký ngay';
    $bgImage     = $config['bg_image']    ?? null;
    $bgColor     = $config['bg_color']    ?? '#f5f5f5';
@endphp

<div class="rts-newsletter-area rts-section-gap"
    style="{{ $bgImage ? 'background-image: url(' . asset('storage/' . $bgImage) . '); background-size: cover; background-position: center;' : 'background-color: ' . $bgColor . ';' }}">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="newsletter-inner text-center">
                    <h2 class="title">{{ $title }}</h2>
                    @if($subtitle)
                    <p class="disc">{{ $subtitle }}</p>
                    @endif
                    <form action="#" method="POST" class="newsletter-form mt--30" id="newsletter-form">
                        @csrf
                        <div class="input-wrapper">
                            <input type="email" name="email" placeholder="{{ $placeholder }}" required>
                            <button type="submit" class="rts-btn btn-primary radious-sm with-icon">
                                <div class="btn-text">{{ $btnText }}</div>
                                <div class="arrow-icon"><i class="fa-light fa-arrow-right"></i></div>
                                <div class="arrow-icon"><i class="fa-light fa-arrow-right"></i></div>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
