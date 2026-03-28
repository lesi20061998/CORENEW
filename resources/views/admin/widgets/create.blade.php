@extends('admin.layouts.app')
@section('title', 'Thêm Widget')
@section('page-title', 'Thêm Widget mới')
@section('page-subtitle', 'Chọn loại widget và cấu hình nội dung')
@section('page-actions')
    <a href="{{ route('admin.widgets.index') }}" class="btn btn-ghost btn-sm">
        <i class="fa-solid fa-arrow-left"></i> Quay lại
    </a>
@endsection

@section('content')
@include('admin.widgets._form', ['widget' => null, 'action' => route('admin.widgets.store'), 'method' => 'POST'])
@endsection
