<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="Grocery, Store, stores">
    <title>{{ setting('site_name', 'VietTinMart') }} - @yield('title', 'Ekomart-Grocery-Store')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('theme/images/fav.png') }}">

    <!-- Theme Styles (Stable Static Distribution Mode) -->
    <link rel="stylesheet preload" href="{{ asset('theme/css/combie.css') }}" as="style">
    <link rel="stylesheet preload" href="{{ asset('theme/css/plugins.css') }}" as="style">
    <link rel="stylesheet preload" href="{{ asset('theme/css/style.css') }}" as="style">
    <link rel="stylesheet" href="{{ asset('theme/css/update.css') }}">

    @stack('styles')
    <link
        href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <style>
        :root {
            /* Core Design System Tokens */
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

            /* Social Tokens */
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

            /* Font Weights */
            --p-light: 300;
            --p-regular: 400;
            --p-medium: 500;
            --p-semi-bold: 600;
            --p-bold: 700;
            --p-extra-bold: 800;
            --p-black: 900;

            --s-light: 300;
            --s-regular: 400;
            --s-medium: 500;
            --s-semi-bold: 600;
            --s-bold: 700;
            --s-extra-bold: 800;
            --s-black: 900;

            --transition: 0.3s;

            /* Global Typography */
            --font-primary: 'Be Vietnam Pro', sans-serif;
            --font-secondary: 'Be Vietnam Pro', sans-serif;
            --font-three: "FontAwesome";

            --font-size-b1: 16px;
            --font-size-b2: 16px;
            --font-size-b3: 14px;
            --line-height-b1: 1.3;
            --line-height-b2: 1.3;
            --line-height-b3: 1.3;
            --h1: 60px;
            --h2: 30px;
            --h3: 26px;
            --h4: 18px;
            --h5: 16px;
            --h6: 15px;
        }
    </style>
</head>

<body class="@yield('body_class', 'shop-main-h')">

    @include('layouts.partials.header')

    <main>
        @yield('content')
    </main>

    @include('layouts.partials.footer')

    <!-- progress area start -->
    <div class="progress-wrap">
        <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
            <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98"
                style="transition: stroke-dashoffset 10ms linear 0s; stroke-dasharray: 307.919, 307.919; stroke-dashoffset: 307.919;">
            </path>
        </svg>
    </div>
    <!-- progress area end -->

    <!-- Quick View Modal Container -->
    <div id="quick-view-modal-container"></div>
    <!-- Theme Overlay -->
    <div id="anywhere-home"></div>

    <!-- plugins js -->
    <script src="{{ asset('theme/js/plugins.js') }}"></script>
    <script src="{{ asset('theme/js/main.js') }}"></script>
    <script src="{{ asset('theme/js/update.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



    @stack('scripts')

</body>

</html>