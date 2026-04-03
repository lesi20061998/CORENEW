<!-- rts footer one area start -->
<div class="rts-footer-area pt--80 bg_light-1">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="footer-main-content-wrapper pb--70 pb_sm--30">
                    <div class="single-footer-wized">
                        <h3 class="footer-title">About Company</h3>
                        <div class="call-area">
                            <div class="icon"><i class="fa-solid fa-phone-rotary"></i></div>
                            <div class="info">
                                <span>Have Question? Call Us 24/7</span>
                                <a href="tel:{{ setting('hotline') }}" class="number">{{ setting('hotline', '+258 3692 2569') }}</a>
                            </div>
                        </div>
                        <div class="opening-hour">
                            <div class="single">
                                <p>Monday - Friday: <span>8:00am - 6:00pm</span></p>
                            </div>
                            <div class="single">
                                <p>Saturday: <span>8:00am - 6:00pm</span></p>
                            </div>
                            <div class="single">
                                <p>Sunday: <span>Service Close</span></p>
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
                                <li><a href="{{ route('sitemap.html') }}">Sitemap</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="single-footer-wized">
                        <h3 class="footer-title">Our Newsletter</h3>
                        <p class="disc-news-letter">Subscribe to the mailing list to receive updates on the new arrivals and other discounts</p>
                        <form class="footersubscribe-form" action="{{ route('newsletter.subscribe') }}" method="POST">
                            @csrf
                            <input name="email" type="email" placeholder="Your email address" required>
                            <button class="rts-btn btn-primary" type="submit">Subscribe</button>
                        </form>
                        <p class="dsic">I would like to receive news and special offer</p>
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