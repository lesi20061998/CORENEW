@extends('admin.layouts.app')
@section('title', 'Sửa Widget')
@section('page-title', 'Chỉnh sửa: ' . $widget->name)
@section('page-subtitle', 'Cập nhật cấu hình widget')
@section('page-actions')
    <a href="{{ route('admin.widgets.index') }}" class="btn btn-ghost btn-sm">
        <i class="fa-solid fa-arrow-left"></i> Quay lại
    </a>
@endsection

@section('content')
@include('admin.widgets._form', [
    'widget' => $widget,
    'action' => route('admin.widgets.update', $widget),
    'method' => 'PUT',
])
@endsection
