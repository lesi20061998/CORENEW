@extends('layouts.app')

@section('title', 'Ekomart-Grocery-Store(e-Commerce) HTML Template')

@section('content')
    {!! app(\App\Services\WidgetService::class)->renderArea('homepage') !!}
@endsection
