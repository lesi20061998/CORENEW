<!-- rts header area start -->
<div class="rts-header-one-area-one">
    @if(setting('topbar_show', '0') == '1')
        <div class="header-top-area"
            style="background-color: var(--topbar-bg); color: var(--topbar-text); {{ setting('topbar_bg_image') ? 'background-image: url(' . asset(setting('topbar_bg_image')) . '); background-size: cover;' : '' }}">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="bwtween-area-header-top">
                            <div class="discount-area">
                                <p class="disc" style="color: inherit;">
                                    {{ setting('topbar_welcome', 'FREE delivery & 40% Discount for next 3 orders! Place your 1st order in.') }}
                                </p>
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
                                        <a href="tel:{{ str_replace(' ', '', setting('contact_phone', '+258 3268 21485')) }}"
                                            style="color: inherit;">{{ setting('contact_phone', '+258 3268 21485') }}</a>
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
                                <li><a href="{{ url('gioi-thieu') }}">Về chúng tôi</a></li>
                                <li><a
                                        href="{{ url('profile') }}">{{ Auth::check() ? Auth::user()->name : 'Tài khoản của tôi' }}</a>
                                </li>
                                <li><a href="{{ url('wishlist') }}">Wishlist</a></li>
                            </ul>
                            <p class="para">{{ setting('topbar_welcome_text', 'We deliver to your everyday from 7:00 to 22:00') }}</p>
                        </div>
                        <div class="nav-sm-left">
                            <ul class="nav-h_top language">
                                <li class="category-hover-header language-hover">
                                    <a href="#">Tiếng Việt</a>
                                    <ul class="category-sub-menu">
                                        <li><a href="#" class="menu-item"><span>English</span></a></li>
                                    </ul>
                                </li>
                                
                                <li><a href="{{ route('order.track') }}">Track Order</a></li>
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
                            <x-theme-image name="logo" :default="setting('site_logo')" :alt="setting('site_name', 'VietTinMart')" class="logo" style="max-height: {{ setting('logo_height', '45') }}px; width: auto;" />
                        </a>
                        <div class="category-search-wrapper">
                            <div class="category-btn category-hover-header">
                                <x-theme-icon name="category" :default="setting('icon_category_bar')" class="parent" />
                                <span>Categories</span>
                                <ul class="category-sub-menu" id="category-active-four" style="max-height: 450px; overflow-y: auto;">
                                    @foreach(\App\Models\Category::where('is_active', true)->where('type', 'product')->orderBy('sort_order')->limit(20)->get() as $cat)
                                        <li>
                                            <a href="{{ route('shop.category', ['category_slug' => $cat->slug]) }}"
                                                class="menu-item">
                                                <x-theme-icon :name="$cat->icon ?: 'placeholder'" default="theme/images/icons/01.svg" />
                                                <span>{{ $cat->name }}</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <form action="{{ route('shop.index') }}" class="search-header" id="header-search-form" style="position: relative;">
                                <input name="q" type="text" id="header-search-input" placeholder="{{ setting('search_placeholder', 'Bạn muốn mua gì?') }}"
                                    required autocomplete="off">
                                <button type="submit" class="rts-btn btn-primary radious-sm with-icon">
                                    <div class="btn-text">Tìm kiếm</div>
                                    <div class="arrow-icon"><i class="fa-light fa-magnifying-glass"></i></div>
                                    <div class="arrow-icon"><i class="fa-light fa-magnifying-glass"></i></div>
                                </button>
                                <div id="search-results-dropdown" class="search-results-dropdown" style="display: none;">
                                    {{-- Results will be injected here --}}
                                </div>
                            </form>

                            <style>
                                .search-results-dropdown {
                                    position: absolute;
                                    top: 100%;
                                    left: 0;
                                    right: 0;
                                    background: #fff;
                                    border-radius: 0 0 10px 10px;
                                    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
                                    z-index: 1000;
                                    max-height: 500px;
                                    overflow-y: auto;
                                    border: 1px solid #eee;
                                    margin-top: 5px;
                                }
                                .search-result-item {
                                    display: flex;
                                    align-items: center;
                                    padding: 12px 15px;
                                    border-bottom: 1px solid #f5f5f5;
                                    transition: all 0.2s;
                                    text-decoration: none !important;
                                }
                                .search-result-item:last-child {
                                    border-bottom: none;
                                }
                                .search-result-item:hover {
                                    background: #f9f9f9;
                                }
                                .search-result-image {
                                    width: 50px;
                                    height: 50px;
                                    object-fit: contain;
                                    margin-left: 15px;
                                    order: 2;
                                    border: 1px solid #eee;
                                    border-radius: 4px;
                                }
                                .search-result-info {
                                    flex: 1;
                                    order: 1;
                                }
                                .search-result-name {
                                    display: block;
                                    font-size: 14px;
                                    font-weight: 600;
                                    color: #333;
                                    margin-bottom: 4px;
                                    white-space: nowrap;
                                    overflow: hidden;
                                    text-overflow: ellipsis;
                                }
                                .search-result-price {
                                    display: flex;
                                    align-items: center;
                                    gap: 10px;
                                }
                                .search-result-current-price {
                                    font-size: 14px;
                                    font-weight: 700;
                                    color: var(--color-primary);
                                }
                                .search-result-old-price {
                                    font-size: 12px;
                                    color: #999;
                                    text-decoration: line-through;
                                }
                                .search-result-category {
                                    display: block;
                                    font-size: 11px;
                                    color: #999;
                                    text-transform: uppercase;
                                    letter-spacing: 0.5px;
                                    margin-bottom: 2px;
                                }
                                .search-result-badge {
                                    font-size: 11px;
                                    background: #ff4d4d;
                                    color: #fff;
                                    padding: 1px 6px;
                                    border-radius: 4px;
                                    font-weight: 700;
                                    margin-left: 5px;
                                }
                            </style>

                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    const searchInput = document.getElementById('header-search-input');
                                    const resultsDropdown = document.getElementById('search-results-dropdown');
                                    let timeout = null;

                                    if (searchInput) {
                                        searchInput.addEventListener('input', function() {
                                            clearTimeout(timeout);
                                            const query = this.value.trim();

                                            if (query.length < 2) {
                                                resultsDropdown.style.display = 'none';
                                                return;
                                            }

                                            timeout = setTimeout(() => {
                                                fetch(`{{ route('shop.suggest') }}?q=${encodeURIComponent(query)}`)
                                                    .then(response => response.json())
                                                    .then(data => {
                                                        if (data.length > 0) {
                                                            let html = '';
                                                            data.forEach(product => {
                                                                html += `
                                                                     <a href="${product.url}" class="search-result-item">
                                                                         <div class="search-result-info">
                                                                             ${product.category ? `<span class="search-result-category">${product.category}</span>` : ''}
                                                                             <span class="search-result-name">${product.name}</span>
                                                                             <div class="search-result-price">
                                                                                 <span class="search-result-current-price">${product.formatted_price}</span>
                                                                                 ${product.has_discount ? `<span class="search-result-old-price">${product.formatted_old_price}</span>` : ''}
                                                                                 ${product.discount_percent ? `<span class="search-result-badge">-${product.discount_percent}%</span>` : ''}
                                                                             </div>
                                                                         </div>
                                                                         <img src="${product.thumbnail_url}" class="search-result-image" alt="${product.name}">
                                                                     </a>
                                                                `;
                                                            });
                                                            resultsDropdown.innerHTML = html;
                                                            resultsDropdown.style.display = 'block';
                                                        } else {
                                                            resultsDropdown.style.display = 'none';
                                                        }
                                                    });
                                            }, 300);
                                        });

                                        // Close dropdown when clicking outside
                                        document.addEventListener('click', function(e) {
                                            if (!searchInput.contains(e.target) && !resultsDropdown.contains(e.target)) {
                                                resultsDropdown.style.display = 'none';
                                            }
                                        });

                                        // Focus shows dropdown if has value
                                        searchInput.addEventListener('focus', function() {
                                            if (this.value.trim().length >= 2 && resultsDropdown.children.length > 0) {
                                                resultsDropdown.style.display = 'block';
                                            }
                                        });
                                    }
                                });
                            </script>
                        </div>
                        <div class="actions-area">
                            <div class="search-btn" id="searchs">
                                <x-theme-icon name="search" :default="setting('icon_search')" />
                            </div>
                            <div class="menu-btn" id="menu-btn">
                                <x-theme-icon name="category" :default="setting('icon_category_bar')" />
                            </div>
                        </div>
                        <div class="accont-wishlist-cart-area-header">
                            <a href="{{ route('profile') }}" class="btn-border-only account">
                                <x-theme-icon name="user" :default="setting('icon_user')" />
                                <span>{{ Auth::check() ? Auth::user()->name : 'Tài khoản' }}</span>
                            </a>
                            <a href="{{ route('wishlist') }}" class="btn-border-only wishlist">
                                <x-theme-icon name="wishlist" :default="setting('icon_wishlist')" />
                                <span class="text">Wishlist</span>
                                <span class="number">{{ count(session()->get('wishlist', [])) }}</span>
                            </a>
                            <div class="btn-border-only cart category-hover-header">
                                <x-theme-icon name="cart" :default="setting('icon_cart')" />
                                <span class="text">My Cart</span>
                                <span class="number">{{ count(session()->get('cart', [])) }}</span>
                                <div class="cart-dropdown-container">
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
    <div class="rts-header-nav-area-one header--sticky">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="nav-and-btn-wrapper">
                        <div class="nav-area">
                            <nav>
                                <ul class="parent-nav" id="main-navigation">
                                    @foreach(\App\Models\Widget::getMenu('header-menu') as $item)
                                        @if($item['label'] == 'Cửa hàng')
                                            <li class="parent with-megamenu">
                                                <a href="{{ $item['url'] }}">{{ $item['label'] }}</a>
                                                <div class="rts-megamenu">
                                                    <div class="wrapper">
                                                        <div class="row align-items-center">
                                                            <div class="col-lg-8">
                                                                <div class="megamenu-item-wrapper">
                                                                    <div class="single-megamenu-wrapper">
                                                                        <p class="title">Bố cục shop</p>
                                                                        <ul>
                                                                            <li><a href="{{ route('shop.index') }}">Lưới sản phẩm</a></li>
                                                                            <li><a href="{{ route('shop.index') }}">Danh sách sản phẩm</a></li>
                                                                        </ul>
                                                                    </div>
                                                                    <div class="single-megamenu-wrapper">
                                                                        <p class="title">Khác</p>
                                                                        <ul>
                                                                            <li><a href="{{ route('cart.page') }}">Giỏ hàng</a></li>
                                                                            <li><a href="{{ route('checkout.index') }}">Thanh toán</a></li>
                                                                            <li><a href="{{ route('order.track') }}">Theo dõi đơn hàng</a></li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        @else
                                            <li class="parent"><a class="nav-link" href="{{ $item['url'] }}">{{ $item['label'] }}</a></li>
                                        @endif
                                    @endforeach
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
                            @foreach(\App\Models\Widget::getMenu('header-menu') as $item)
                                <li><a href="{{ $item['url'] }}">{{ $item['label'] }}</a></li>
                            @endforeach
                        </ul>
                    </nav>
                </div>
            </div>
            <div class="tab-pane fade" id="nav-profile" role="tabpanel">
                <div class="category-btn category-hover-header mobile-menu-category-wrapper mt--30">
                    <ul class="category-sub-menu" id="category-active-four-mobile" style="max-height: 400px; overflow-y: auto;">
                        @foreach(\App\Models\Category::where('is_active', true)->where('type', 'product')->get() as $cat)
                            <li>
                                <a href="{{ route('shop.category', ['category_slug' => $cat->slug]) }}" class="menu-item">
                                    @if($cat->icon)
                                        <img src="{{ $cat->icon_url }}" alt="icons">
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
            <div class="phone"><i class="fa-light fa-headset"></i><a
                    href="tel:{{ setting('contact_phone') }}">{{ setting('contact_phone', '+258 3268 21485') }}</a>
            </div>
            <div class="phone"><i class="fa-light fa-envelope"></i><a href="mailto:{{ setting('contact_email') }}">{{
                    setting('contact_email', 'info@ekomart.com') }}</a></div>
        </div>
        <div class="buton-area-bottom">
            @auth
                <a href="{{ route('profile') }}" class="rts-btn btn-primary">My Account</a>
            @else
                <a href="{{ route('login') }}" class="rts-btn btn-primary">Sign In</a>
                <a href="{{ route('register') }}" class="rts-btn btn-primary">Sign Up</a>
            @endauth
        </div>
    </div>
</div>