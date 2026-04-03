<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- Basic -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $siteName = setting('site_name');
        $defaultTitle = setting('seo_meta_title');
        $defaultDesc = setting('seo_meta_desc');
        $defaultKeys = setting('seo_meta_keywords');

        $yieldedTitle = $__env->yieldContent('title');
        $yieldedDesc = $__env->yieldContent('meta_description');
        $yieldedKeys = $__env->yieldContent('meta_keywords');
        $yieldedOgImage = $__env->yieldContent('og_image');

        $currentTitle = $yieldedTitle ?: ($siteName . ' - ' . $defaultTitle);
        $currentDesc = $yieldedDesc ?: $defaultDesc;
        $currentKeys = $yieldedKeys ?: $defaultKeys;
        $currentOgImage = $yieldedOgImage ?: asset(setting('site_og_image', 'theme/images/logo/logo-01.svg'));

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

    <!-- Open Graph (Facebook, Zalo, LinkedIn) -->
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:title" content="@yield('og_title', $currentTitle)">
    <meta property="og:description" content="@yield('og_description', $currentDesc)">
    <meta property="og:image" content="@yield('og_image', $currentOgImage)">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:locale" content="vi_VN">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('twitter_title', $currentTitle)">
    <meta name="twitter:description" content="@yield('twitter_description', $currentDesc)">
    <meta name="twitter:image" content="@yield('twitter_image', $currentOgImage)">

    <!-- SEO -->
    <meta name="author" content="{{ setting('site_author', 'VietTin Mart') }}">
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
    <link
        href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <style>
        :root {
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
            --color-white: #fff;
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
            --color-pinterest:
                {{ setting('color_pinterest', '#E60022') }}
            ;
            --color-instagram:
                {{ setting('color_instagram', '#C231A1') }}
            ;
            --color-vimeo:
                {{ setting('color_vimeo', '#00ADEF') }}
            ;
            --color-twitch:
                {{ setting('color_twitch', '#6441A3') }}
            ;
            --color-discord:
                {{ setting('color_discord', '#7289da') }}
            ;
            --transition: 0.3s;
            --font-primary: 'Be Vietnam Pro', sans-serif;
            --font-secondary: 'Be Vietnam Pro', sans-serif;
            --font-three: "FontAwesome";
        }
    </style>
    {!! setting('seo_script_header') !!}
</head>

<body class="@yield('body_class', 'shop-main-h')">
    {!! setting('seo_script_body') !!}

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
            baseUrl: "{{ url('/') }}"
        };
    </script>

    @stack('scripts')

    {!! setting('seo_script_footer') !!}
</body>

</html>