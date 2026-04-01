@extends('errors.layout')

@section('title', 'Truy cập bị từ chối')
@section('code', '403')
@section('color-start', '#f59e0b')
@section('color-end', '#b45309')
@section('message')
    Bạn không có quyền truy cập vào nội dung này. <br>
    Hãy chắc chắn rằng bạn đã đăng nhập với đúng tài khoản.
@endsection
