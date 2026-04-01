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
                                <a href="#" class="number">+258 3692 2569</a>
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
                        <h3 class="footer-title">Our Stores</h3>
                        <div class="footer-nav">
                            <ul>
                                <li><a href="{{ route('shop.show', ['slug' => 'delivery-info']) }}">Delivery
                                        Information</a></li>
                                <li><a href="{{ route('shop.show', ['slug' => 'privacy-policy']) }}">Privacy Policy</a>
                                </li>
                                <li><a href="{{ route('shop.show', ['slug' => 'terms-conditions']) }}">Terms &
                                        Conditions</a></li>
                                <li><a href="{{ route('contact.index') }}">Support Center</a></li>
                                <li><a href="{{ route('shop.show', ['slug' => 'careers']) }}">Careers</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="single-footer-wized">
                        <h3 class="footer-title">Shop Categories</h3>
                        <div class="footer-nav">
                            <ul>
                                <li><a href="{{ route('contact.index') }}">Contact Us</a></li>
                                <li><a href="{{ route('about') }}">Information</a></li>
                                <li><a href="{{ route('about') }}">About Us</a></li>
                                <li><a href="{{ route('shop.show', ['slug' => 'careers']) }}">Careers</a></li>
                                <li><a href="{{ route('blog.index') }}">Nest Stories</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="single-footer-wized">
                        <h3 class="footer-title">Useful Links</h3>
                        <div class="footer-nav">
                            <ul>
                                <li><a href="{{ route('shop.show', ['slug' => 'cancellation-returns']) }}">Cancellation
                                        & Returns</a></li>
                                <li><a href="{{ route('shop.show', ['slug' => 'cancellation-returns']) }}">Report
                                        Infringement</a></li>
                                <li><a href="{{ route('shop.show', ['slug' => 'payments']) }}">Payments</a></li>
                                <li><a href="{{ route('shop.show', ['slug' => 'shipping']) }}">Shipping</a></li>
                                <li><a href="{{ route('faq') }}">FAQ</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="single-footer-wized">
                        <h3 class="footer-title">Our Newsletter</h3>
                        <p class="disc-news-letter">Subscribe to the mailing list to receive updates on the new arrivals
                            and other discounts</p>
                        <form class="footersubscribe-form" action="" method="POST">
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
                            <li><a href="#"><i class="fa-brands fa-facebook-f"></i></a></li>
                            <li><a href="#"><i class="fa-brands fa-twitter"></i></a></li>
                            <li><a href="#"><i class="fa-brands fa-youtube"></i></a></li>
                            <li><a href="#"><i class="fa-brands fa-whatsapp"></i></a></li>
                            <li><a href="#"><i class="fa-brands fa-instagram"></i></a></li>
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