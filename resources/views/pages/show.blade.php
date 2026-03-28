@extends('layouts.app')

@section('title', $page->meta_title ?? $page->title . ' - VietTinMart')
@section('meta_description', $page->meta_description ?? setting('seo_meta_desc', ''))
@section('meta_keywords', $page->meta_keywords ?? setting('seo_meta_keywords', ''))
@section('canonical', $page->canonical_url ?? url()->current())
@section('og_type', 'website')
@section('og_image', setting('seo_og_image', asset('theme/images/fav.png')))

@include('components.seo-schema', ['context' => 'page', 'model' => $page])

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb-area">
    <div class="container">
        <div class="breadcrumb-content">
            <ul class="breadcrumb-list">
                <li><a href="{{ route('home') }}">Trang chủ</a></li>
                <li class="active">{{ $page->title }}</li>
            </ul>
        </div>
    </div>
</div>

<!-- Page Content -->
<section class="page-area section-padding-tb">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <h1 class="page-title mb-4">{{ $page->title }}</h1>
                <div class="page-content">
                    {!! $page->content !!}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
