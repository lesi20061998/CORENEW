@php
    $footerCategories = \App\Models\Category::where('type', 'product')->where('is_active', true)->limit(6)->get();
@endphp

{{-- rts footer one area start --}}
<div class="rts-footer-area pt--80 bg_light-1">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="footer-main-content-wrapper pb--70 pb_sm--30">

                    {{-- Cột 1: Thông tin công ty --}}
                    <div class="single-footer-wized">
                        <h3 class="footer-title">{{ setting('site_name', 'VietTinMart') }}</h3>
                        <div class="call-area">
                            <div class="icon">
                                <i class="fa-solid fa-phone-rotary"></i>
                            </div>
                            <div class="info">
                                <span>Hỗ trợ 24/7</span>
                                <a href="tel:{{ setting('phone', '1800 1234') }}" class="number">{{ setting('phone', '1800 1234') }}</a>
                            </div>
                        </div>
                        <div class="opening-hour">
                            <div class="single"><p>Thứ 2 - Thứ 6: <span>8:00 - 22:00</span></p></div>
                            <div class="single"><p>Thứ 7: <span>8:00 - 20:00</span></p></div>
                            <div class="single"><p>Chủ nhật: <span>9:00 - 18:00</span></p></div>
                        </div>
                    </div>

                    {{-- Cột 2: Danh mục cửa hàng --}}
                    <div class="single-footer-wized">
                        <h3 class="footer-title">Danh mục</h3>
                        <div class="footer-nav">
                            <ul>
                                @foreach($footerCategories as $cat)
                                <li><a href="{{ route('shop.index', ['category' => $cat->slug]) }}">{{ $cat->name }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    {{-- Cột 3: Hỗ trợ --}}
                    <div class="single-footer-wized">
                        <h3 class="footer-title">Hỗ trợ</h3>
                        <div class="footer-nav">
                            <ul>
                                <li><a href="{{ route('about') }}">Về chúng tôi</a></li>
                                <li><a href="{{ route('contact.index') }}">Liên hệ</a></li>
                                <li><a href="{{ route('faq') }}">Câu hỏi thường gặp</a></li>
                                <li><a href="#">Chính sách đổi trả</a></li>
                                <li><a href="#">Chính sách bảo mật</a></li>
                            </ul>
                        </div>
                    </div>

                    {{-- Cột 4: Tài khoản --}}
                    <div class="single-footer-wized">
                        <h3 class="footer-title">Tài khoản</h3>
                        <div class="footer-nav">
                            <ul>
                                @auth
                                <li><a href="{{ route('account.profile') }}">Thông tin cá nhân</a></li>
                                <li><a href="{{ route('account.orders') }}">Đơn hàng của tôi</a></li>
                                @else
                                <li><a href="{{ route('login') }}">Đăng nhập</a></li>
                                <li><a href="{{ route('auth.register') }}">Đăng ký</a></li>
                                @endauth
                                <li><a href="{{ route('cart.page') }}">Giỏ hàng</a></li>
                                <li><a href="{{ route('order.track') }}">Theo dõi đơn hàng</a></li>
                            </ul>
                        </div>
                    </div>

                    {{-- Cột 5: Newsletter --}}
                    <div class="single-footer-wized">
                        <h3 class="footer-title">Nhận ưu đãi</h3>
                        <p class="disc-news-letter">
                            Đăng ký nhận thông tin sản phẩm mới và ưu đãi độc quyền từ VietTinMart.
                        </p>
                        <form class="footersubscribe-form" action="#" method="POST">
                            @csrf
                            <input type="email" placeholder="Địa chỉ email của bạn" required>
                            <button type="submit" class="rts-btn btn-primary">Đăng ký</button>
                        </form>
                        <p class="dsic">Tôi muốn nhận tin tức và ưu đãi đặc biệt</p>
                    </div>

                </div>

                {{-- Social & Payment --}}
                <div class="social-and-payment-area-wrapper">
                    <div class="social-one-wrapper">
                        <span>Theo dõi:</span>
                        <ul>
                            <li><a href="{{ setting('social_links.facebook', '#') }}" target="_blank"><i class="fa-brands fa-facebook-f"></i></a></li>
                            <li><a href="{{ setting('social_links.instagram', '#') }}" target="_blank"><i class="fa-brands fa-instagram"></i></a></li>
                            <li><a href="{{ setting('social_links.youtube', '#') }}" target="_blank"><i class="fa-brands fa-youtube"></i></a></li>
                            <li><a href="{{ setting('social_links.tiktok', '#') }}" target="_blank"><i class="fa-brands fa-tiktok"></i></a></li>
                            <li><a href="{{ setting('social_links.zalo', '#') }}" target="_blank"><i class="fa-solid fa-phone"></i></a></li>
                        </ul>
                    </div>
                    <div class="payment-access">
                        <span>Thanh toán:</span>
                        <img src="{{ asset('theme/images/payment/01.png') }}" alt="Phương thức thanh toán">
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
{{-- rts footer one area end --}}

{{-- rts copyright-area start --}}
<div class="rts-copyright-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="copyright-between-1">
                    <p class="disc">
                        Copyright {{ date('Y') }} <a href="{{ url('/') }}">©{{ setting('site_name', 'VietTinMart') }}</a>. Bảo lưu mọi quyền.
                    </p>
                    <a href="#" class="playstore-app-area">
                        <span>Tải ứng dụng</span>
                        <img src="{{ asset('theme/images/payment/02.png') }}" alt="App Store">
                        <img src="{{ asset('theme/images/payment/03.png') }}" alt="Google Play">
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- rts copyright-area end --}}
