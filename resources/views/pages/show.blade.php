@extends('layouts.app')

@section('title', ($page->meta_title ?: $page->title) . ' - ' . setting('site_name', 'VietTinMart'))
@section('meta_description', $page->meta_description)
@section('meta_keywords', $page->meta_keywords)

@section('content')
<!-- rts navigation bar area start -->
<div class="rts-navigation-area-breadcrumb">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="navigator-breadcrumb-wrapper text-center d-flex justify-content-center">
                    <a href="{{ route('home') }}">Home</a>
                    <i class="fa-regular fa-chevron-right"></i>
                    <a class="current" href="#">{{ $page->title ?? 'Page' }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- rts navigation bar area end -->

@php
    $template = $page->template ?? 'default';
@endphp

@includeIf('templates.' . $template, ['page' => $page])

@if(!view()->exists('templates.' . $template))
    @include('templates.default', ['page' => $page])
@endif

<style>
.entry-content p {
    margin-bottom: 20px;
}
.entry-content ul, .entry-content ol {
    margin-bottom: 20px;
    padding-left: 20px;
}
.entry-content h2, .entry-content h3 {
    margin-top: 30px;
    margin-bottom: 15px;
}
</style>
@endsection
