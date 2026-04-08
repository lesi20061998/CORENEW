<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- Basic -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $siteName = setting('site_name');
        $defaultTitle = setting('seo_meta_title', setting('meta_title', $siteName));
        $defaultDesc = setting('seo_meta_desc', setting('seo_meta_description', setting('meta_description', '')));
        $defaultKeys = setting('seo_meta_keywords', setting('meta_keywords', ''));
        $defaultOgImg = setting('seo_og_image', setting('og_image', asset('theme/images/logo/logo-01.svg')));

        $yieldedTitle = $__env->yieldContent('title');
        $yieldedDesc = $__env->yieldContent('meta_description');
        $yieldedKeys = $__env->yieldContent('meta_keywords');
        $yieldedOgImage = $__env->yieldContent('og_image');

        $currentTitle = $yieldedTitle ?: ($siteName . ' - ' . $defaultTitle);
        $currentDesc = $yieldedDesc ?: $defaultDesc;
        $currentKeys = $yieldedKeys ?: $defaultKeys;
        $currentOgImage = $yieldedOgImage ?: (filter_var($defaultOgImg, FILTER_VALIDATE_URL) ? $defaultOgImg : asset($defaultOgImg));
    @endphp

    <title>@yield('title', $currentTitle)</title>
    <meta name="description" content="@yield('meta_description', $currentDesc)">
    <meta name="keywords" content="@yield('meta_keywords', $currentKeys)">
    <meta name="robots" content="@yield('robots', 'index, follow')">
    <meta name="theme-color" content="{{ setting('theme_color', '#ffffff') }}">

    <!-- Canonical -->
    <link rel="canonical" href="@yield('canonical', url()->current())">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset(setting('site_favicon', 'favicon.ico')) }}">
    <link rel="apple-touch-icon" href="{{ asset(setting('site_apple_icon', 'apple-touch-icon.png')) }}">

    {{-- Open Graph (Facebook, Zalo, LinkedIn) --}}
    @if(seo_enabled('page', 'og'))
        <meta property="og:type" content="@yield('og_type', 'website')">
        <meta property="og:title" content="@yield('og_title', $currentTitle)">
        <meta property="og:description" content="@yield('og_description', $currentDesc)">
        <meta property="og:image" content="@yield('og_image', $currentOgImage)">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:site_name" content="{{ $siteName }}">
        <meta property="og:locale" content="vi_VN">
        @if(setting('seo_fb_app_id'))
            <meta property="fb:app_id" content="{{ setting('seo_fb_app_id') }}">
        @endif
    @endif

    {{-- Twitter Card --}}
    @if(seo_enabled('page', 'twitter'))
        <meta name="twitter:card" content="{{ setting('seo_twitter_card', 'summary_large_image') }}">
        <meta name="twitter:title" content="@yield('twitter_title', $currentTitle)">
        <meta name="twitter:description" content="@yield('twitter_description', $currentDesc)">
        <meta name="twitter:image" content="@yield('twitter_image', $currentOgImage)">
        @if(setting('seo_twitter_site'))
        <meta name="twitter:site" content="{{ setting('seo_twitter_site') }}">@endif
        @if(setting('seo_twitter_creator'))
        <meta name="twitter:creator" content="{{ setting('seo_twitter_creator') }}">@endif
    @endif

    {{-- SEO --}}
    <meta name="author" content="{{ setting('site_author', setting('site_name', 'VietTin Mart')) }}">
    <meta name="revisit-after" content="1 days">

    <!-- Preconnect -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Structured Data (Schema.org) -->


    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    <!-- Theme Styles -->
    <link rel="stylesheet" href="{{ asset('theme/css/plugins.css') }}">
    <link rel="stylesheet" href="{{ asset('theme/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('theme/css/update.css') }}">

    @stack('styles')
    <!-- Dynamic Fonts Import -->
    {!! setting('font_import_urls', '<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">') !!}
    <style>
        :root {
            /* CORE COLORS */
            --color-primary:
                {{ setting('color_primary', '#629D23') }}
            ;
            --color-secondary:
                {{ setting('color_secondary', '#1F1F25') }}
            ;
            --color-body:
                {{ setting('color_body', '#6E777D') }}
            ;
            --color-heading-1:
                {{ setting('color_heading_1', '#2C3C28') }}
            ;
            --site-bg:
                {{ setting('site_bg_color', '#ffffff') }}
            ;
            --color-white: #fff;

            /* DERIVED COLORS (AUTO-CALCULATED) */
            --color-primary-hover: color-mix(in srgb, var(--color-primary), black 10%);
            --color-primary-light: color-mix(in srgb, var(--color-primary), white 85%);
            --color-primary-alpha-10: color-mix(in srgb, var(--color-primary), transparent 90%);
            --color-primary-alpha-20: color-mix(in srgb, var(--color-primary), transparent 80%);
            --color-secondary-hover: color-mix(in srgb, var(--color-secondary), black 10%);

            /* FUNCTIONAL COLORS */
            --color-success:
                {{ setting('color_success', '#3EB75E') }}
            ;
            --color-danger:
                {{ setting('color_danger', '#DC2626') }}
            ;
            --color-warning:
                {{ setting('color_warning', '#FF8F3C') }}
            ;
            --color-info:
                {{ setting('color_info', '#1BA2DB') }}
            ;

            /* SOCIAL COLORS */
            --color-facebook:
                {{ setting('color_facebook', '#3B5997') }}
            ;
            --color-twitter:
                {{ setting('color_twitter', '#1BA1F2') }}
            ;
            --color-youtube:
                {{ setting('color_youtube', '#ED4141') }}
            ;
            --color-linkedin:
                {{ setting('color_linkedin', '#0077B5') }}
            ;
            --color-instagram:
                {{ setting('color_instagram', '#C231A1') }}
            ;
            --color-pinterest:
                {{ setting('color_pinterest', '#E60022') }}
            ;

            /* TYPOGRAPHY SYSTEM */
            --font-primary:
                {!! setting('font_main', 'Inter, sans-serif') !!}
            ;
            --font-secondary:
                {!! setting('font_heading', 'Inter, sans-serif') !!}
            ;
            --font-nav:
                {!! setting('nav_font', 'Inter, sans-serif') !!}
            ;
            --font-three: "FontAwesome";

            /* HEADING SIZES */
            --h1:
                {{ setting('h1', '60px') }}
            ;
            --h2:
                {{ setting('h2', '30px') }}
            ;
            --h3:
                {{ setting('h3', '26px') }}
            ;
            --h4:
                {{ setting('h4', '18px') }}
            ;
            --h5:
                {{ setting('h5', '16px') }}
            ;
            --h6:
                {{ setting('h6', '15px') }}
            ;

            /* BODY SIZES & LINE HEIGHTS */
            --font-size-b1:
                {{ setting('font_size_b1', '16px') }}
            ;
            --font-size-b2:
                {{ setting('font_size_b2', '16px') }}
            ;
            --font-size-b3:
                {{ setting('font_size_b3', '14px') }}
            ;
            --line-height-b1:
                {{ setting('line_height_b1', '1.3') }}
            ;
            --line-height-b2:
                {{ setting('line_height_b2', '1.3') }}
            ;
            --line-height-b3:
                {{ setting('line_height_b3', '1.3') }}
            ;

            /* WEIGHTS */
            --p-light:
                {{ setting('p_light', 300) }}
            ;
            --p-regular:
                {{ setting('p_regular', 400) }}
            ;
            --p-medium:
                {{ setting('p_medium', 500) }}
            ;
            --p-semi-bold:
                {{ setting('p_semi_bold', 600) }}
            ;
            --p-bold:
                {{ setting('p_bold', 700) }}
            ;
            --p-extra-bold:
                {{ setting('p_extra_bold', 800) }}
            ;
            --p-black:
                {{ setting('p_black', 900) }}
            ;

            --s-light:
                {{ setting('p_light', 300) }}
            ;
            --s-regular:
                {{ setting('p_regular', 400) }}
            ;
            --s-medium:
                {{ setting('p_medium', 500) }}
            ;
            --s-semi-bold:
                {{ setting('p_semi_bold', 600) }}
            ;
            --s-bold:
                {{ setting('p_bold', 700) }}
            ;
            --s-extra-bold:
                {{ setting('p_extra_bold', 800) }}
            ;
            --s-black:
                {{ setting('p_black', 900) }}
            ;

            /* OTHER */
            --transition: 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            --color-nav-font:
                {{ setting('nav_font_color', '#333333') }}
            ;
            --color-nav-hover:
                {{ setting('nav_font_color_hover', '#629D23') }}
            ;
            --nav-bg:
                {{ setting('nav_bg_color', 'transparent') }}
            ;

            /* CONTEXTUAL BACKGROUNDS (TOPBAR/HEADER) */
            --topbar-bg:
                {{ setting('topbar_bg_color', '#ffffff') }}
            ;
            --topbar-text:
                {{ setting('topbar_text_color', '#6E777D') }}
            ;
            --header-bg:
                {{ setting('header_bg_color', '#ffffff') }}
            ;

            /* ADVANCED TEXTURES & EFFECTS */
            --color-primary-gradient: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-hover) 100%);
            --primary-glow: 0 10px 20px -5px color-mix(in srgb, var(--color-primary), transparent 60%);
            --surface-glass: rgba(255, 255, 255, 0.7);
            --border-subtle: 1px solid color-mix(in srgb, var(--color-body), transparent 90%);
            --texture-bg: radial-gradient(circle at 50% 50%, color-mix(in srgb, var(--site-bg), white 50%) 0%, var(--site-bg) 100%);
        }

        /* PREMIUM UI REFINEMENTS */
        .rts-btn.btn-primary {
            background: var(--color-primary-gradient) !important;
            border: none !important;
            box-shadow: var(--primary-glow);
            transition: var(--transition) !important;
            position: relative;
            overflow: hidden;
            z-index: 1;
            color: #fff !important;
        }

        .rts-btn.btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px -5px color-mix(in srgb, var(--color-primary), transparent 40%);
            filter: brightness(1.1);
        }

        /* Harmonious Accent Usage */
        .primary-accent-border {
            border-left: 4.5px solid var(--color-primary);
        }

        .text-primary-gradient {
            background: var(--color-primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .badge-primary-soft {
            background: var(--color-primary-alpha-10);
            color: var(--color-primary);
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* TEXTURE: Subtle grain on body for premium feel */
        body {
            background: var(--texture-bg) !important;
            font-family: var(--font-primary) !important;
            -webkit-font-smoothing: antialiased;
        }

        h1, h2, h3, h4, h5, h6,
        .title, .title-left, .product-title,
        .rts-section-title, .section-title {
            font-family: var(--font-secondary) !important;
        }

        nav, .nav-area, .parent-nav, .mainmenu {
            font-family: var(--font-nav) !important;
        }
    </style>
    {!! setting('seo_script_header') !!}

    {{-- Google Site Verification --}}
    @if(setting('google_site_verification'))
    <meta name="google-site-verification" content="{{ setting('google_site_verification') }}">
    @endif

    {{-- Google Tag Manager (head) --}}
    @if(setting('gtm_id'))
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','{{ setting('gtm_id') }}');</script>
    @endif

    {{-- Google Analytics 4 --}}
    @if(setting('ga4_id'))
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ setting('ga4_id') }}"></script>
    <script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','{{ setting('ga4_id') }}');</script>
    @endif

    {{-- Facebook Pixel --}}
    @if(setting('fb_pixel_id'))
    <script>!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,document,'script','https://connect.facebook.net/en_US/fbevents.js');fbq('init','{{ setting('fb_pixel_id') }}');fbq('track','PageView');</script>
    <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id={{ setting('fb_pixel_id') }}&ev=PageView&noscript=1"/></noscript>
    @endif
</head>

<body class="@yield('body_class', 'shop-main-h')">
    {!! setting('seo_script_body') !!}

    {{-- Google Tag Manager (body) --}}
    @if(setting('gtm_id'))
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ setting('gtm_id') }}" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    @endif

    @include('layouts.partials.header')

    <main>
        @yield('content')
    </main>

    @include('layouts.partials.footer')

    <div class="progress-wrap">
        <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
            <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98"
                style="transition: stroke-dashoffset 10ms linear 0s; stroke-dasharray: 307.919, 307.919; stroke-dashoffset: 307.919;">
            </path>
        </svg>
    </div>

    <!-- plugins js -->
    <script src="{{ asset('theme/js/plugins.js') }}"></script>
    <script src="{{ asset('theme/js/main.js') }}"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="{{ asset('theme/js/update.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        window.VTM_CONFIG = {
            baseUrl: "{{ url('/') }}",
            siteName: "{{ setting('site_name', 'VietTinMart') }}",
            hotline: "{{ setting('hotline', setting('contact_phone', '')) }}",
            address: "{{ setting('site_address', setting('address', '')) }}",
            currency: "{{ setting('currency_symbol', 'đ') }}",
            currencyPos: "{{ setting('currency_position', 'right') }}",
        };
    </script>

    @stack('scripts')

    {!! setting('seo_script_footer') !!}
</body>

</html>