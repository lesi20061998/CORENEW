<!-- rts footer one area start -->
<div class="rts-footer-area pt--80 bg_light-1">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="footer-main-content-wrapper pb--70 pb_sm--30">
                    <div class="single-footer-wized">
                        <h3 class="footer-title">{{ setting('footer_about_title', 'Về chúng tôi') }}</h3>
                        <div class="call-area">
                            <div class="icon"><i class="fa-solid fa-phone-rotary"></i></div>
                            <div class="info">
                                <span>{{ setting('footer_hotline_label', 'Hotline hỗ trợ 24/7') }}</span>
                                <a href="tel:{{ setting('hotline', setting('contact_phone', '')) }}" class="number">{{ setting('hotline', setting('contact_phone', '+258 3692 2569')) }}</a>
                            </div>
                        </div>
                        <div class="opening-hour">
                            <div class="single">
                                <p>{{ setting('footer_hours_weekday', 'Thứ 2 - Thứ 6') }}: <span>{{ setting('footer_hours_weekday_time', '8:00am - 6:00pm') }}</span></p>
                            </div>
                            <div class="single">
                                <p>{{ setting('footer_hours_saturday', 'Thứ 7') }}: <span>{{ setting('footer_hours_saturday_time', '8:00am - 6:00pm') }}</span></p>
                            </div>
                            <div class="single">
                                <p>{{ setting('footer_hours_sunday', 'Chủ nhật') }}: <span>{{ setting('footer_hours_sunday_time', 'Nghỉ') }}</span></p>
                            </div>
                        </div>
                    </div>
                    <div class="single-footer-wized">
                        <h3 class="footer-title">{{ setting('footer_title_1', 'Thông tin') }}</h3>
                        <div class="footer-nav">
                            <ul>
                                @foreach(\App\Models\Widget::getMenu('footer-info') as $item)
                                    <li><a href="{{ $item['url'] }}">{{ $item['label'] }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="single-footer-wized">
                        <h3 class="footer-title">{{ setting('footer_title_2', 'Danh mục cửa hàng') }}</h3>
                        <div class="footer-nav">
                            <ul>
                                @foreach(\App\Models\Widget::getMenu('footer-categories') as $item)
                                    <li><a href="{{ $item['url'] }}">{{ $item['label'] }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="single-footer-wized">
                        <h3 class="footer-title">{{ setting('footer_title_3', 'Liên kết hữu ích') }}</h3>
                        <div class="footer-nav">
                            <ul>
                                @foreach(\App\Models\Widget::getMenu('footer-links') as $item)
                                    <li><a href="{{ $item['url'] }}">{{ $item['label'] }}</a></li>
                                @endforeach
                                @if(Route::has('sitemap_html'))
                                    <li><a href="{{ route('sitemap_html') }}">Sitemap</a></li>
                                @else
                                    <li><a href="/sitemap">Sitemap</a></li>
                                @endif
                            </ul>
                        </div>
                    </div>
                    <div class="single-footer-wized">
                        <h3 class="footer-title">{{ setting('footer_newsletter_title', 'Bản tin của chúng tôi') }}</h3>
                        <p class="disc-news-letter">{{ setting('footer_newsletter_desc', 'Đăng ký nhận thông báo về sản phẩm mới và ưu đãi đặc biệt.') }}</p>
                        <form class="footersubscribe-form" action="{{ route('newsletter.subscribe') }}" method="POST">
                            @csrf
                            <input name="email" type="email" placeholder="{{ setting('footer_newsletter_placeholder', 'Địa chỉ email của bạn') }}" required>
                            <button class="rts-btn btn-primary" type="submit">{{ setting('footer_newsletter_btn', 'Đăng ký') }}</button>
                        </form>
                        <p class="dsic">{{ setting('footer_newsletter_note', 'Tôi muốn nhận tin tức và ưu đãi đặc biệt.') }}</p>
                    </div>
                </div>
                <div class="social-and-payment-area-wrapper">
                    <div class="social-one-wrapper">
                        <span>Follow Us:</span>
                        <ul>
                            <li><a href="{{ setting('facebook_url', '#') }}"><i class="fa-brands fa-facebook-f"></i></a></li>
                            <li><a href="{{ setting('twitter_url', '#') }}"><i class="fa-brands fa-twitter"></i></a></li>
                            <li><a href="{{ setting('youtube_url', '#') }}"><i class="fa-brands fa-youtube"></i></a></li>
                            <li><a href="{{ setting('whatsapp_url', '#') }}"><i class="fa-brands fa-whatsapp"></i></a></li>
                            <li><a href="{{ setting('instagram_url', '#') }}"><i class="fa-brands fa-instagram"></i></a></li>
                        </ul>
                    </div>
                    <div class="payment-access">
                        <span>Payment Accepts:</span>
                        <img src="{{ asset('theme/images/payment/01.png') }}" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- rts footer one area end -->

<!-- rts copyright-area start -->
<div class="rts-copyright-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="copyright-between-1">
                    <p class="disc">{!! setting('site_copyright', 'Copyright 2024 <a href="#">©Ekomart</a>. All rights reserved.') !!}</p>
                    <a href="#" class="playstore-app-area">
                        <span>Download App</span>
                        <img src="{{ asset('theme/images/payment/02.png') }}" alt="">
                        <img src="{{ asset('theme/images/payment/03.png') }}" alt="">
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- rts copyright-area end -->

<!-- Quick View Modal Container -->
<div id="quick-view-modal-container"></div>
<!-- Theme Overlay -->
<div id="anywhere-home"></div>