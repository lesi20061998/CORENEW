@php
    $categories = $categories ?? \App\Models\Category::where('type','product')->where('is_active', true)->orderBy('sort_order')->limit(10)->get();
@endphp

<!-- header style two / mobile sidebar -->
<div id="side-bar" class="side-bar header-two">
    <button class="close-icon-menu"><i class="far fa-times"></i></button>

    <form action="{{ route('shop.index') }}" method="GET" class="search-input-area-menu mt--30">
        <input type="text" name="q" placeholder="Tìm kiếm..." required>
        <button type="submit"><i class="fa-light fa-magnifying-glass"></i></button>
    </form>

    <div class="mobile-menu-nav-area tab-nav-btn mt--20">
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <button class="nav-link active" id="nav-home-tab"
                    data-bs-toggle="tab" data-bs-target="#nav-home"
                    type="button" role="tab" aria-controls="nav-home" aria-selected="true">Menu</button>
                <button class="nav-link" id="nav-profile-tab"
                    data-bs-toggle="tab" data-bs-target="#nav-profile"
                    type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Danh mục</button>
            </div>
        </nav>

        <div class="tab-content" id="nav-tabContent">

            {{-- Tab Menu --}}
            <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab" tabindex="0">
                <div class="mobile-menu-main">
                    <nav class="nav-main mainmenu-nav mt--30">
                        <ul class="mainmenu metismenu" id="mobile-menu-active">
                            <li>
                                <a href="{{ url('/') }}" class="main">Trang chủ</a>
                            </li>
                            <li>
                                <a href="{{ route('about') }}" class="main">Giới thiệu</a>
                            </li>
                            <li class="has-droupdown">
                                <a href="{{ route('shop.index') }}" class="main">Cửa hàng</a>
                                <ul class="submenu mm-collapse">
                                    @foreach($categories as $cat)
                                    <li><a class="mobile-menu-link" href="{{ route('shop.index', ['category' => $cat->slug]) }}">{{ $cat->name }}</a></li>
                                    @endforeach
                                </ul>
                            </li>
                            <li class="has-droupdown">
                                <a href="#" class="main">Trang</a>
                                <ul class="submenu mm-collapse">
                                    <li><a class="mobile-menu-link" href="{{ route('about') }}">Về chúng tôi</a></li>
                                    <li><a class="mobile-menu-link" href="{{ route('faq') }}">Câu hỏi thường gặp</a></li>
                                    <li><a class="mobile-menu-link" href="{{ route('order.track') }}">Theo dõi đơn hàng</a></li>
                                    <li><a class="mobile-menu-link" href="{{ route('cart.page') }}">Giỏ hàng</a></li>
                                    <li><a class="mobile-menu-link" href="{{ route('checkout.index') }}">Thanh toán</a></li>
                                </ul>
                            </li>
                            <li class="has-droupdown">
                                <a href="{{ route('blog.index') }}" class="main">Blog</a>
                                <ul class="submenu mm-collapse">
                                    <li><a class="mobile-menu-link" href="{{ route('blog.index') }}">Tất cả bài viết</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="{{ route('contact.index') }}" class="main">Liên hệ</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>

            {{-- Tab Category --}}
            <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab" tabindex="0">
                <div class="category-btn category-hover-header menu-category mt--20">
                    <form action="{{ route('shop.index') }}" method="GET" id="mobile-category-form">
                        <select name="category" class="form-select" onchange="this.form.submit()">
                            <option value="">-- Tất cả danh mục --</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->slug }}" {{ request('category') === $cat->slug ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>

        </div>
    </div>

    {{-- Contact + Buttons --}}
    <div class="button-area-main-wrapper-menuy-sidebar mt--50">
        <div class="contact-area">
            <div class="phone">
                <i class="fa-light fa-headset"></i>
                <a href="tel:{{ setting('phone','1800 1234') }}">{{ setting('phone','1800 1234') }}</a>
            </div>
            <div class="phone">
                <i class="fa-light fa-envelope"></i>
                <a href="mailto:{{ setting('email','info@viettinmart.vn') }}">{{ setting('email','info@viettinmart.vn') }}</a>
            </div>
        </div>
        <div class="buton-area-bottom">
            @auth
            <a href="{{ route('account.profile') }}" class="rts-btn btn-primary">Tài khoản</a>
            <form action="{{ route('auth.logout') }}" method="POST" style="display:inline">
                @csrf
                <button type="submit" class="rts-btn btn-primary">Đăng xuất</button>
            </form>
            @else
            <a href="{{ route('login') }}" class="rts-btn btn-primary">Đăng nhập</a>
            <a href="{{ route('auth.register') }}" class="rts-btn btn-primary">Đăng ký</a>
            @endauth
        </div>
    </div>
</div>
