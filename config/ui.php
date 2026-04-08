<?php

return [
    /*
    |--------------------------------------------------------------------------
    | UI Assets Management
    |--------------------------------------------------------------------------
    |
    | Hết sức linh hoạt: Cho phép map key có nghĩa tới class icon hoặc link ảnh.
    | Dễ dàng thay đổi bộ icon (FontAwesome, Bootstrap, SVG) mà không cần sửa Blade.
    |
    */

    'icons' => [
        // Header Actions
        'cart'      => 'fa-sharp fa-regular fa-cart-shopping',
        'user'      => 'fa-light fa-user',
        'wishlist'  => 'fa-regular fa-heart',
        'search'    => 'fa-light fa-magnifying-glass',
        'category'  => 'fa-solid fa-bars',
        'plus'      => 'fa-sharp fa-regular fa-plus',
        'arrow-right' => 'fa-light fa-arrow-right',
        'arrow-left'  => 'fa-light fa-arrow-left',
        'chevron-left' => 'fa-regular fa-chevron-left',
        'chevron-right' => 'fa-regular fa-chevron-right',
        'chevron-up'    => 'fa-regular fa-chevron-up',
        'chevron-down'  => 'fa-regular fa-chevron-down',
        'times'       => 'far fa-times',
        'headset'     => 'fa-light fa-headset',
        'phone'       => 'fa-solid fa-phone-rotary',
        'envelope'    => 'fa-light fa-envelope',
        'clock'       => 'fa-light fa-clock',
        'folder'      => 'fa-regular fa-folder',
        'truck'       => 'fa-solid fa-truck-fast',
        'money'       => 'fa-solid fa-dollar-sign',
        'refresh'     => 'fa-solid fa-rotate-left',
        'tag'         => 'fa-solid fa-tag',
        'shield'      => 'fa-solid fa-shield-halved',
        'star'        => 'fa-solid fa-star',
        'quote'       => 'fa-solid fa-quote-right',
        'facebook'    => 'fa-brands fa-facebook-f',
        'twitter'     => 'fa-brands fa-twitter',
        'youtube'     => 'fa-brands fa-youtube',
        'whatsapp'    => 'fa-brands fa-whatsapp',
        'instagram'   => 'fa-brands fa-instagram',
        'heart'       => 'fa-light fa-heart',
        'eye'         => 'fa-regular fa-eye',
        'bookmark'    => 'fa-solid fa-bookmark',
        'arrows-retweet' => 'fa-solid fa-arrows-retweet',
        'box-open'    => 'fa-light fa-box-open',
        'pan'         => 'fa-solid fa-panorama',
        'images'      => 'fa-solid fa-images',
        'ad'          => 'fa-solid fa-rectangle-ad',

        // Default Fallback
        'fallback' => 'fas fa-question-circle',
    ],

    'images' => [
        'logo'      => 'theme/images/logo/logo-01.svg',
        'favicon'   => 'favicon.ico',
        'placeholder' => 'theme/images/icons/01.svg',
        'banner_default' => 'theme/images/banner/banner-01.png',
        'promo_default'  => 'theme/images/banner/promo-01.png',
        'payment_methods' => 'theme/images/payment/01.png',
        'app_store'       => 'theme/images/payment/02.png',
        'google_play'     => 'theme/images/payment/03.png',
        
        // Fallback
        'fallback' => 'images/default.png',
    ],

    /* Support for multi-themes */
    'themes' => [
        'default' => [
            'prefix' => 'theme/images/',
        ]
    ]
];
