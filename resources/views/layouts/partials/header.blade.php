@php
    $categories = \App\Models\Category::where('type','product')->where('is_active', true)->orderBy('sort_order')->limit(10)->get();
    $cartItems  = session('cart', []);
    $cartCount  = array_sum(array_column($cartItems, 'qty'));
    $cartTotal  = collect($cartItems)->sum(fn($i) => ($i['price'] ?? 0) * ($i['qty'] ?? 1));
    $freeShipMin = 500000;
    $remaining   = max(0, $freeShipMin - $cartTotal);
    $progress    = $freeShipMin > 0 ? min(100, round($cartTotal / $freeShipMin * 100)) : 0;
@endphp

<!-- rts header area start -->
<div class="rts-header-one-area-one">

    {{-- ── Top Bar ── --}}
    <div class="header-top-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="bwtween-area-header-top">
                        <div class="discount-area">
                            <p class="disc">{{ setting('header_promo_text', 'Miễn phí giao hàng & Giảm 40% cho 3 đơn tiếp theo!') }}</p>
                            @if(setting('header_countdown'))
                            <div class="countdown">
                                <div class="countDown">{{ setting('header_countdown') }}</div>
                            </div>
                            @endif
                        </div>
                        <div class="contact-number-area">
                            <p>Cần hỗ trợ? Gọi: <a href="tel:{{ setting('phone','1800 1234') }}">{{ setting('phone','1800 1234') }}</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Mid Bar ── --}}
    <div class="header-mid-one-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="header-mid-wrapper-between">
                        <div class="nav-sm-left">
                            <ul class="nav-h_top">
                                <li><a href="{{ route('about') }}">Về chúng tôi</a></li>
                                @auth
                                <li><a href="{{ route('account.profile') }}">Tài khoản</a></li>
                                @else
                                <li><a href="{{ route('login') }}">Đăng nhập</a></li>
                                @endauth
                                <li><a href="#">Yêu thích</a></li>
                            </ul>
                            <p class="para">Giao hàng mỗi ngày từ 7:00 đến 22:00</p>
                        </div>
                        <div class="nav-sm-left">
                            <ul class="nav-h_top language">
                                <li><a href="{{ route('order.track') }}">Theo dõi đơn hàng</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Search Bar ── --}}
    <div class="search-header-area-main">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="logo-search-category-wrapper">

                        {{-- Logo --}}
                        <a href="{{ url('/') }}" class="logo-area">
                            @if(setting('logo'))
                                <img src="{{ asset('storage/'.setting('logo')) }}" alt="{{ setting('site_name','VietTinMart') }}" class="logo">
                            @else
                                <img src="{{ asset('theme/images/logo/logo-01.svg') }}" alt="{{ setting('site_name','VietTinMart') }}" class="logo">
                            @endif
                        </a>

                        {{-- Category + Search --}}
                        <div class="category-search-wrapper">
                            <div class="category-btn category-hover-header">
                                <img class="parent" src="{{ asset('theme/images/icons/bar-1.svg') }}" alt="icons">
                                <span>Danh mục</span>
                                <ul class="category-sub-menu" id="category-active-four">
                                    <li>
                                        <a href="{{ route('shop.index') }}" class="menu-item" id="cat-item-all">
                                            <img src="{{ asset('theme/images/icons/bar-1.svg') }}" alt="Tất cả">
                                            <span>Tất cả sản phẩm</span>
                                        </a>
                                    </li>
                                    @foreach($categories as $cat)
                                    <li>
                                        <a href="{{ route('shop.index', ['category' => $cat->slug]) }}" class="menu-item">
                                            @if($cat->icon)
                                                <img src="{{ Str::startsWith($cat->icon,'http') ? $cat->icon : asset('storage/'.$cat->icon) }}" alt="{{ $cat->name }}">
                                            @elseif($cat->image)
                                                <img src="{{ Str::startsWith($cat->image,'http') ? $cat->image : asset('storage/'.$cat->image) }}" alt="{{ $cat->name }}">
                                            @else
                                                <img src="{{ asset('theme/images/icons/bar-1.svg') }}" alt="{{ $cat->name }}">
                                            @endif
                                            <span>{{ $cat->name }}</span>
                                        </a>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>

                            <form action="{{ route('shop.index') }}" method="GET" class="search-header" id="header-search-form">
                                <input type="hidden" name="category" id="header-search-category" value="{{ request('category') }}">
                                <input type="text" name="q" id="header-search-input"
                                    value="{{ request('q') }}"
                                    placeholder="Tìm kiếm sản phẩm..."
                                    autocomplete="off"
                                    list="search-suggestions">
                                <datalist id="search-suggestions"></datalist>
                                <button type="submit" class="rts-btn btn-primary radious-sm with-icon">
                                    <div class="btn-text">Tìm kiếm</div>
                                    <div class="arrow-icon"><i class="fa-light fa-magnifying-glass"></i></div>
                                    <div class="arrow-icon"><i class="fa-light fa-magnifying-glass"></i></div>
                                </button>
                            </form>
                        </div>

                        {{-- Mobile icons --}}
                        <div class="actions-area">
                            <div class="search-btn" id="search">
                                <svg width="17" height="16" viewBox="0 0 17 16" fill="none"><path d="M15.75 14.7188L11.5625 10.5312C12.4688 9.4375 12.9688 8.03125 12.9688 6.5C12.9688 2.9375 10.0312 0 6.46875 0C2.875 0 0 2.9375 0 6.5C0 10.0938 2.90625 13 6.46875 13C7.96875 13 9.375 12.5 10.5 11.5938L14.6875 15.7812C14.8438 15.9375 15.0312 16 15.25 16C15.4375 16 15.625 15.9375 15.75 15.7812C16.0625 15.5 16.0625 15.0312 15.75 14.7188ZM1.5 6.5C1.5 3.75 3.71875 1.5 6.5 1.5C9.25 1.5 11.5 3.75 11.5 6.5C11.5 9.28125 9.25 11.5 6.5 11.5C3.71875 11.5 1.5 9.28125 1.5 6.5Z" fill="#1F1F25"/></svg>
                            </div>
                            <div class="menu-btn" id="menu-btn">
                                <svg width="20" height="16" viewBox="0 0 20 16" fill="none"><rect y="14" width="20" height="2" fill="#1F1F25"/><rect y="7" width="20" height="2" fill="#1F1F25"/><rect width="20" height="2" fill="#1F1F25"/></svg>
                            </div>
                        </div>

                        {{-- Account / Wishlist / Cart --}}
                        <div class="accont-wishlist-cart-area-header">
                            @auth
                            <a href="{{ route('account.profile') }}" class="btn-border-only account">
                                <i class="fa-light fa-user"></i>
                                <span>{{ Auth::user()->name }}</span>
                            </a>
                            @else
                            <a href="{{ route('login') }}" class="btn-border-only account">
                                <i class="fa-light fa-user"></i>
                                <span>Đăng nhập</span>
                            </a>
                            @endauth

                            <a href="#" class="btn-border-only wishlist">
                                <i class="fa-regular fa-heart"></i>
                                <span class="text">Yêu thích</span>
                                <span class="number">0</span>
                            </a>

                            <div class="btn-border-only cart category-hover-header">
                                <i class="fa-sharp fa-regular fa-cart-shopping"></i>
                                <span class="text">Giỏ hàng</span>
                                <span class="number">{{ $cartCount }}</span>

                                <div class="category-sub-menu card-number-show" id="header-cart-dropdown">
                                    @include('layouts.partials.cart-dropdown')
                                </div>
                                <a href="{{ route('cart.page') }}" class="over_link"></a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Main Nav ── --}}
    <div class="rts-header-nav-area-one header--sticky">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="nav-and-btn-wrapper">
                        <div class="nav-area">
                            <nav>
                                <ul class="parent-nav">
                                    <li class="parent {{ request()->is('/') ? 'active' : '' }}">
                                        <a href="{{ url('/') }}">Trang chủ</a>
                                    </li>
                                    <li class="parent {{ request()->is('gioi-thieu') ? 'active' : '' }}">
                                        <a href="{{ route('about') }}">Giới thiệu</a>
                                    </li>
                                    <li class="parent with-megamenu {{ request()->is('shop*','san-pham*') ? 'active' : '' }}">
                                        <a href="{{ route('shop.index') }}">Cửa hàng</a>
                                        <div class="rts-megamenu">
                                            <div class="wrapper">
                                                <div class="row align-items-center">
                                                    <div class="col-lg-8">
                                                        <div class="megamenu-item-wrapper">
                                                            <div class="single-megamenu-wrapper">
                                                                <p class="title">Danh mục sản phẩm</p>
                                                                <ul>
                                                                    @foreach($categories->take(8) as $cat)
                                                                    <li><a href="{{ route('shop.index', ['category' => $cat->slug]) }}">{{ $cat->name }}</a></li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <a href="{{ route('shop.index') }}" class="feature-add-megamenu-area">
                                                            <img src="{{ asset('theme/images/feature/05.jpg') }}" alt="shop">
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="parent {{ request()->is('blog*') ? 'active' : '' }}">
                                        <a href="{{ route('blog.index') }}">Blog</a>
                                    </li>
                                    <li class="parent {{ request()->is('lien-he') ? 'active' : '' }}">
                                        <a href="{{ route('contact.index') }}">Liên hệ</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                        <div class="right-location-area">
                            <i class="fa-solid fa-location-dot"></i>
                            <p>Giao hàng: <a href="#">{{ setting('address','Toàn quốc') }}</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- rts header area end -->
