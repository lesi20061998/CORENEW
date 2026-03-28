@extends('layouts.app')

@section('title', setting('seo_meta_title', setting('site_name', 'VietTinMart') . ' - Siêu thị trực tuyến'))
@section('meta_description', setting('seo_meta_desc', setting('site_description', 'Mua sắm thực phẩm tươi sạch, giao hàng tận nơi')))
@section('meta_keywords', setting('seo_meta_keywords', 'siêu thị, mua sắm online, thực phẩm tươi sạch, giao hàng tận nơi'))
@section('meta_robots', 'index, follow')
@section('canonical', url('/'))
@section('og_type', 'website')
@section('og_image', setting('seo_og_image', setting('site_logo', asset('theme/images/fav.png'))))

@push('schema_json')
@php
    $schemaJson = json_encode([
        '@context'        => 'https://schema.org',
        '@type'           => 'WebSite',
        'name'            => setting('site_name', 'VietTinMart'),
        'url'             => url('/'),
        'description'     => setting('seo_meta_desc', setting('site_description', 'VietTinMart - Siêu thị trực tuyến')),
        'potentialAction' => [
            '@type'       => 'SearchAction',
            'target'      => url('/shop') . '?q={search_term_string}',
            'query-input' => 'required name=search_term_string',
        ],
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
@endphp
{!! '<script type="application/ld+json">' . $schemaJson . '</script>' !!}
@endpush

@section('content')
    @widgetArea('homepage_v1')
@endsection

@push('styles')
<style>
/* ── Product horizontal scroll row ── */
.vtm-product-scroll-row {
    display: flex;
    flex-wrap: nowrap;
    gap: 16px;
    overflow-x: auto;
    overflow-y: visible;
    padding-bottom: 12px;
    /* smooth scrolling on touch */
    -webkit-overflow-scrolling: touch;
    scroll-snap-type: x mandatory;
}

/* hide scrollbar on desktop, keep functional */
.vtm-product-scroll-row::-webkit-scrollbar {
    height: 5px;
}
.vtm-product-scroll-row::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 10px;
}
.vtm-product-scroll-row::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
}
.vtm-product-scroll-row::-webkit-scrollbar-thumb:hover {
    background: #629D23;
}

.vtm-product-scroll-item {
    flex: 0 0 220px;
    min-width: 220px;
    scroll-snap-align: start;
}

/* deal countdown — items slightly wider */
.vtm-deal-scroll .vtm-product-scroll-item {
    flex: 0 0 260px;
    min-width: 260px;
}

.vtm-product-scroll-empty {
    padding: 24px;
    color: #94a3b8;
    font-size: 14px;
}

/* ensure card fills its scroll item */
.vtm-product-scroll-item .single-shopping-card-one {
    height: 100%;
}
</style>
@endpush
