@extends('layouts.app')

@section('title', ($page->title ?? 'Page') . ' - Ekomart-Grocery-Store')

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

<div class="rts-page-detail-area rts-section-gap">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-content-wrapper p-4 border rounded bg-white shadow-sm">
                    <h1 class="title mb--30">{{ $page->title ?? 'Untitled Page' }}</h1>
                    <div class="content entry-content">
                        {!! $page->content ?? 'No content available for this page.' !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
