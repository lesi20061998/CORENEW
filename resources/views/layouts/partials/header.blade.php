<!-- rts header area start -->
<div class="rts-header-one-area-one">
    @if(setting('topbar_show', '0') == '1')
    <div class="header-top-area" style="background-color: var(--topbar-bg); color: var(--topbar-text); {{ setting('topbar_bg_image') ? 'background-image: url(' . asset(setting('topbar_bg_image')) . '); background-size: cover;' : '' }}">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="bwtween-area-header-top">
                        <div class="discount-area">
                            <p class="disc" style="color: inherit;">{{ setting('topbar_welcome', 'FREE delivery & 40% Discount for next 3 orders! Place your 1st order in.') }}</p>
                            @if(!setting('topbar_welcome'))
                            <div class="countdown">
                                <div class="countDown">10/05/2026 10:20:00</div>
                            </div>
                            @endif
                        </div>
                        <div class="contact-number-area">
                            <p style="color: inherit;">
                                @if(setting('topbar_right_text'))
                                    {{ setting('topbar_right_text') }}
                                @else
                                    Need help? Call Us: 
                                    <a href="tel:{{ str_replace(' ', '', setting('hotline', '+258 3268 21485')) }}" style="color: inherit;">{{ setting('hotline', '+258 3268 21485') }}</a>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    <div class="header-mid-one-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="header-mid-wrapper-between">
                        <div class="nav-sm-left">
                            <ul class="nav-h_top">
                                <li><a href="{{ route('about') }}">About Us</a></li>

                                <li><a href="{{ route('wishlist') }}">Wishlist</a></li>
                            </ul>
                            <p class="para">We deliver to your everyday from 7:00 to 22:00</p>
                        </div>
                        <div class="nav-sm-left">
                            <ul class="nav-h_top">
                                <li><a href="{{ route('order.track') }}">Track Order</a></li>
                                <li><a href="{{ route('faq') }}">FAQ</a></li>
                                <li><a href="{{ route('contact.index') }}">Contact</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="search-header-area-main">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="logo-search-category-wrapper">
                        <a href="{{ route('home') }}" class="logo-area">
                            @if(setting('site_logo'))
                                <img src="{{ asset(setting('site_logo')) }}" alt="{{ setting('site_name', 'VietTinMart') }}" class="logo" style="max-height: {{ setting('logo_height', '45') }}px; width: auto;">
                            @else
                                <img src="{{ asset('theme/images/logo/logo-01.svg') }}" alt="logo-main" class="logo">
                            @endif
                        </a>
                        <div class="category-search-wrapper">
                            <div class="category-btn category-hover-header">
                                <img class="parent" src="{{ asset('theme/images/icons/bar-1.svg') }}" alt="icons">
                                <span>Categories</span>
                                <ul class="category-sub-menu" id="category-active-four">
                                    @foreach(\App\Models\Category::where('is_active', true)->where('type', 'product')->orderBy('sort_order')->limit(20)->get() as $cat)
                                        <li>
                                            <a href="{{ route('shop.category', ['category_slug' => $cat->slug]) }}"
                                                class="menu-item">
                                                @if($cat->icon)
                                                    <img src="{{ asset($cat->icon) }}" alt="icons">
                                                @else
                                                    <img src="{{ asset('theme/images/icons/01.svg') }}" alt="icons">
                                                @endif
                                                <span>{{ $cat->name }}</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <form action="{{ route('shop.index') }}" class="search-header">
                                <input name="q" type="text" placeholder="Search for products, categories or brands"
                                    required>
                                <button type="submit" class="rts-btn btn-primary radious-sm with-icon">
                                    <div class="btn-text">Search</div>
                                    <div class="arrow-icon"><i class="fa-light fa-magnifying-glass"></i></div>
                                </button>
                            </form>
                        </div>
                        <div class="accont-wishlist-cart-area-header">
                            <a href="{{ route('profile') }}" class="btn-border-only account">
                                <i class="fa-light fa-user"></i>
                                <span>Account</span>
                            </a>
                            <a href="{{ route('wishlist') }}" class="btn-border-only wishlist">
                                <i class="fa-regular fa-heart"></i>
                                <span class="text">Wishlist</span>
                                <span class="number">{{ count(session()->get('wishlist', [])) }}</span>
                            </a>
                            <a href="{{ route('compare.index') }}" class="btn-border-only compare">
                                <i class="fa-solid fa-arrows-retweet"></i>
                                <span class="text">Compare</span>
                                <span class="number">{{ count(session()->get('compare', [])) }}</span>
                            </a>
                            <div class="btn-border-only cart category-hover-header">
                                <i class="fa-sharp fa-regular fa-cart-shopping"></i>
                                <span class="text">My Cart</span>
                                <span class="number">{{ count(session()->get('cart', [])) }}</span>
                                <div class="category-sub-menu card-number-show cart-dropdown-container">
                                    @include('layouts.partials.cart-dropdown')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="rts-header-nav-area-one header--sticky">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="nav-and-btn-wrapper">
                        <div class="nav-area">
                            <nav>
                                <ul class="parent-nav">
                                    <li class="parent"><a class="nav-link" href="{{ route('home') }}">Home</a></li>
                                    <li class="parent"><a href="{{ route('about') }}">About</a></li>
                                    <li class="parent"><a href="{{ route('shop.index') }}">Cửa hàng</a></li>
                                    <li class="parent"><a href="{{ route('blog.index') }}">Blog</a></li>
                                    <li class="parent"><a href="{{ route('contact.index') }}">Contact</a></li>
                                </ul>
                            </nav>
                        </div>
                        <div class="right-btn-area">
                            <a href="{{ route('shop.index', ['filter' => 'trending']) }}" class="btn-narrow">Trending
                                Products</a>
                            <button class="rts-btn btn-primary">Get 30% Discount Now <span>Sale</span></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="side-bar" class="side-bar header-two">
    <button class="close-icon-menu"><i class="far fa-times"></i></button>
    <form action="{{ route('shop.index') }}" class="search-input-area-menu mt--30">
        <input name="q" type="text" placeholder="Search..." required>
        <button><i class="fa-light fa-magnifying-glass"></i></button>
    </form>
    <div class="mobile-menu-nav-area tab-nav-btn mt--20">
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home"
                    type="button" role="tab">Menu</button>
                <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile"
                    type="button" role="tab">Category</button>
            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="nav-home" role="tabpanel">
                <div class="mobile-menu-main">
                    <nav class="nav-main mainmenu-nav mt--30">
                        <ul class="mainmenu metismenu" id="mobile-menu-active">
                            <li><a href="{{ route('home') }}">Home</a></li>
                            <li><a href="{{ route('about') }}">About</a></li>
                            <li><a href="{{ route('shop.index') }}">Shop</a></li>
                            <li><a href="{{ route('blog.index') }}">Blog</a></li>
                            <li><a href="{{ route('contact.index') }}">Contact Us</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
            <div class="tab-pane fade" id="nav-profile" role="tabpanel">
                <div class="category-btn category-hover-header mobile-menu-category-wrapper mt--30">
                    <ul class="category-sub-menu" id="category-active-four-mobile">
                        @foreach(\App\Models\Category::where('is_active', true)->where('type', 'product')->get() as $cat)
                            <li>
                                <a href="{{ route('shop.category', ['category_slug' => $cat->slug]) }}" class="menu-item">
                                    @if($cat->icon)
                                        <img src="{{ asset($cat->icon) }}" alt="icons">
                                    @else
                                        <img src="{{ asset('theme/images/icons/01.svg') }}" alt="icons">
                                    @endif
                                    <span>{{ $cat->name }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="button-area-main-wrapper-menuy-sidebar mt--50">
        <div class="contact-area">
            <div class="phone"><i class="fa-light fa-headset"></i><a href="#">+258 3268 21485</a></div>
            <div class="phone"><i class="fa-light fa-envelope"></i><a href="#">info@ekomart.com</a></div>
        </div>
        <div class="buton-area-bottom">
            <a href="{{ route('login') }}" class="rts-btn btn-primary">Sign In</a>
            <a href="{{ route('register') }}" class="rts-btn btn-primary">Sign Up</a>
        </div>
    </div>
</div>