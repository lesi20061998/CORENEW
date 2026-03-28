<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- ===== SEO Core ===== --}}
    @php
        $siteName     = setting('site_name', 'VietTinMart');
        $fbAppId      = setting('seo_fb_app_id', '');
        $twitterSite  = setting('seo_twitter_site', '');
    @endphp

    <title>@yield('title', setting('seo_meta_title', $siteName))</title>
    <meta name="description" content="@yield('meta_description', setting('seo_meta_desc', setting('site_description', 'VietTinMart - Siêu thị trực tuyến')))">
    <meta name="keywords"    content="@yield('meta_keywords', setting('seo_meta_keywords', setting('site_keywords', 'siêu thị, mua sắm online, thực phẩm')))">
    <meta name="robots"      content="@yield('meta_robots', setting('seo_meta_robots', 'index, follow'))">
    <link rel="canonical"    href="@yield('canonical', url()->current())">

    {{-- ===== Open Graph (Facebook, Zalo, LinkedIn, Telegram…) ===== --}}
    <meta property="og:type"        content="@yield('og_type', 'website')">
    <meta property="og:url"         content="@yield('canonical', url()->current())">
    <meta property="og:title"       content="@yield('title', setting('seo_meta_title', $siteName))">
    <meta property="og:description" content="@yield('meta_description', setting('seo_meta_desc', 'VietTinMart - Siêu thị trực tuyến'))">
    <meta property="og:image"       content="@yield('og_image', setting('seo_og_image', setting('site_logo', asset('theme/images/fav.png'))))">
    <meta property="og:image:width"  content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:site_name"   content="{{ $siteName }}">
    <meta property="og:locale"      content="vi_VN">
    @if($fbAppId)
    <meta property="fb:app_id"      content="{{ $fbAppId }}">
    @endif

    {{-- ===== Twitter / X Card ===== --}}
    <meta name="twitter:card"        content="@yield('twitter_card', setting('seo_twitter_card', 'summary_large_image'))">
    <meta name="twitter:title"       content="@yield('title', setting('seo_meta_title', $siteName))">
    <meta name="twitter:description" content="@yield('meta_description', setting('seo_meta_desc', 'VietTinMart - Siêu thị trực tuyến'))">
    <meta name="twitter:image"       content="@yield('og_image', setting('seo_og_image', asset('theme/images/fav.png')))">
    @if($twitterSite)
    <meta name="twitter:site"        content="{{ $twitterSite }}">
    @endif

    {{-- ===== Favicon ===== --}}
    @php $favicon = setting('seo_favicon', asset('theme/images/fav.png')); @endphp
    <link rel="shortcut icon" type="image/x-icon" href="{{ $favicon }}">
    <link rel="icon"          type="image/x-icon" href="{{ $favicon }}">

    {{-- ===== Schema.org JSON-LD ===== --}}
    @stack('schema_json')

    {{-- ===== Script Header (từ admin SEO settings) ===== --}}
    {!! setting('seo_script_header') !!}

    <link rel="stylesheet preload" href="{{ asset('theme/css/plugins.css') }}" as="style">
    <link rel="stylesheet preload" href="{{ asset('theme/css/style.css') }}" as="style">
    @stack('styles')
</head>
<body class="{{ $bodyClass ?? 'shop-main-h' }}">

    @include('layouts.partials.header')
    @include('layouts.partials.mobile-menu')

    <main id="main-content">
        @yield('content')
    </main>

    @include('layouts.partials.footer')

    {{-- Quick View Modal --}}
    @include('layouts.partials.quickview-modal')

    {{-- Search Overlay --}}
    <div class="search-input-area">
        <div class="container">
            <div class="search-input-inner">
                <div class="input-div">
                    <input id="searchInput1" class="search-input" type="text" placeholder="Tìm kiếm sản phẩm...">
                    <button><i class="far fa-search"></i></button>
                </div>
            </div>
        </div>
        <div id="close" class="search-close-icon"><i class="far fa-times"></i></div>
    </div>

    {{-- Overlay backdrop --}}
    <div id="anywhere-home" class="anywere"></div>

    {{-- Scroll to top --}}
    <div class="progress-wrap">
        <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
            <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98"
                style="transition: stroke-dashoffset 10ms linear 0s; stroke-dasharray: 307.919, 307.919; stroke-dashoffset: 307.919;">
            </path>
        </svg>
    </div>

    <script src="{{ asset('theme/js/plugins.js') }}"></script>
    <script src="{{ asset('theme/js/main.js') }}"></script>

    {{-- ===== Global Add-to-Cart ===== --}}
    <script>
    (function () {
        const CART_ADD_URL = '{{ route("cart.add") }}';
        const CSRF = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';

        // Toast notification
        function showToast(msg, type) {
            let wrap = document.getElementById('vtm-toast-wrap');
            if (!wrap) {
                wrap = document.createElement('div');
                wrap.id = 'vtm-toast-wrap';
                wrap.style.cssText = 'position:fixed;top:20px;right:20px;z-index:99999;display:flex;flex-direction:column;gap:8px;';
                document.body.appendChild(wrap);
            }
            const t = document.createElement('div');
            t.style.cssText = `background:${type==='success'?'#629D23':'#e74c3c'};color:#fff;padding:12px 20px;border-radius:8px;font-size:14px;box-shadow:0 4px 12px rgba(0,0,0,.15);opacity:0;transition:opacity .3s;min-width:220px;`;
            t.textContent = msg;
            wrap.appendChild(t);
            requestAnimationFrame(() => { t.style.opacity = '1'; });
            setTimeout(() => { t.style.opacity = '0'; setTimeout(() => t.remove(), 300); }, 2800);
        }

        // Update cart count badge
        function updateCartCount(count) {
            document.querySelectorAll('.cart-count-badge, .cart-item-count, [data-cart-count], .btn-border-only.cart > .number').forEach(el => {
                el.textContent = count;
                el.style.display = count > 0 ? '' : 'none';
            });
        }

        // Refresh cart dropdown HTML from server
        function refreshCartDropdown() {
            fetch('{{ route("cart.dropdown") }}')
                .then(r => r.text())
                .then(html => {
                    const el = document.getElementById('header-cart-dropdown');
                    if (el) el.innerHTML = html;
                    // update badge from the new h5 text
                    const h5 = el ? el.querySelector('.shopping-cart-number') : null;
                    if (h5) {
                        const match = h5.textContent.match(/\d+/);
                        if (match) updateCartCount(parseInt(match[0]));
                    }
                })
                .catch(() => {});
        }

        // Core add-to-cart function
        window.addToCart = function(productId, qty, btn, variantId) {
            if (btn) {
                btn.disabled = true;
                const txt = btn.querySelector('.btn-text');
                if (txt) txt.textContent = '...';
            }
            fetch(CART_ADD_URL, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
                body: JSON.stringify({ 
                    product_id: productId, 
                    variant_id: variantId || '',
                    qty: qty || 1 
                }),
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    showToast('Đã thêm vào giỏ hàng!', 'success');
                    updateCartCount(data.count);
                    refreshCartDropdown();
                } else {
                    showToast('Có lỗi xảy ra, vui lòng thử lại.', 'error');
                }
            })
            .catch(() => showToast('Có lỗi kết nối.', 'error'))
            .finally(() => {
                if (btn) {
                    btn.disabled = false;
                    const txt = btn.querySelector('.btn-text');
                    if (txt) txt.textContent = 'Thêm';
                }
            });
        };

        // Delegate click on .add-to-cart-btn
        document.addEventListener('click', function (e) {
            const btn = e.target.closest('.add-to-cart-btn');
            if (!btn) return;
            
            // If it's the main button on product page, we let that specific page script handle it or we check attributes
            if (btn.id === 'add-to-cart-main') return; 

            e.preventDefault();

            const productId = btn.dataset.productId;
            const variantId = btn.dataset.variantId || '';
            if (!productId) return;

            // Tìm qty input gần nhất trong cùng wrapper
            const wrapper = btn.closest('.cart-counter-action, .cart-action-wrapper, .single-shopping-card-one');
            let qty = 1;
            if (wrapper) {
                const qtyInput = wrapper.querySelector('.input, .qty-input, #product-qty');
                if (qtyInput) qty = parseInt(qtyInput.value) || 1;
            }

            window.addToCart(productId, qty, btn, variantId);
        });

        // Remove item from header dropdown
        document.addEventListener('click', function (e) {
            const btn = e.target.closest('.dropdown-cart-remove');
            if (!btn) return;
            e.preventDefault();
            e.stopPropagation();

            const key = btn.dataset.id;
            if (!key) return;

            fetch('{{ route("cart.remove") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
                body: JSON.stringify({ rowId: key }),
            })
            .then(r => r.json())
            .then(() => refreshCartDropdown())
            .catch(() => {});
        });

        // Qty buttons in product cards (+ / -)
        document.addEventListener('click', function (e) {
            const btn = e.target.closest('.button-wrapper-action .button');
            if (!btn) return;
            const wrapper = btn.closest('.quantity-edit');
            if (!wrapper) return;
            const input = wrapper.querySelector('.input');
            if (!input) return;
            let val = parseInt(input.value) || 1;
            if (btn.classList.contains('plus')) val++;
            else val = Math.max(1, val - 1);
            input.value = val;
        });
    })();
    </script>

    <script>
    (function () {
        const input    = document.getElementById('header-search-input');
        const catHidden = document.getElementById('header-search-category');
        const datalist = document.getElementById('search-suggestions');
        if (!input || !datalist) return;

        let timer;
        const suggest = () => {
            const q   = input.value.trim();
            const cat = catHidden ? catHidden.value : '';
            if (q.length < 1) { datalist.innerHTML = ''; return; }

            clearTimeout(timer);
            timer = setTimeout(() => {
                const url = '{{ route("shop.suggest") }}?q=' + encodeURIComponent(q) + '&category=' + encodeURIComponent(cat);
                fetch(url)
                    .then(r => r.json())
                    .then(items => {
                        datalist.innerHTML = items.map(p =>
                            `<option value="${p.name}"></option>`
                        ).join('');
                    });
            }, 250);
        };

        input.addEventListener('input', suggest);

        // Khi click vào category trong dropdown, set hidden input và re-suggest
        document.querySelectorAll('#category-active-four .menu-item').forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                const url    = new URL(this.href);
                const slug   = url.searchParams.get('category') || '';
                const label  = this.querySelector('span') ? this.querySelector('span').textContent : '';
                if (catHidden) catHidden.value = slug;
                const spanBtn = document.querySelector('.category-btn > span');
                if (spanBtn) spanBtn.textContent = slug ? label : 'Danh mục';
                suggest();
                input.focus();
            });
        });
    })();
    </script>
    @stack('scripts')
</body>
</html>
