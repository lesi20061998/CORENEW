<div class="rts-newsletter-area rts-section-gapBottom" {!! $sectionStyles !!}>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="newsletter-one-wrapper" 
                     style="background-color: {{ $config['bg_color'] ?? '#f0f7e6' }}; @if(!empty($config['bg_image'])) background-image: url('{{ $config['bg_image'] }}') @endif">
                    <div class="newsletter-inner-content">
                        <h2 class="title">{{ $config['title'] ?? 'Đăng ký nhận ưu đãi độc quyền' }}</h2>
                        <p class="disc">{{ $config['subtitle'] ?? 'Nhận ngay Voucher cho đơn hàng đầu tiên khi đăng ký thành viên mới' }}</p>
                    </div>
                    <form action="#" class="newsletter-form">
                        <div class="input-wrapper">
                            <i class="fa-regular fa-envelope"></i>
                            <input type="email" placeholder="{{ $config['placeholder'] ?? 'Email của bạn...' }}" required>
                        </div>
                        <button type="submit" class="rts-btn btn-primary radious-sm">{{ $config['btn_text'] ?? 'Đăng ký ngay' }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
