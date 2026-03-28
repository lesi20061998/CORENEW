@extends('admin.layouts.app')
@section('title', 'Thêm bài viết')
@section('page-title', 'Thêm bài viết mới')
@section('page-subtitle', 'Tạo bài viết mới cho hệ thống')
@section('page-actions')
    <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary btn-sm">
        <i class="fa-solid fa-xmark"></i> Hủy
    </a>
    <button type="submit" form="post-form" class="btn btn-primary btn-sm">
        <i class="fa-solid fa-floppy-disk"></i> Lưu bài viết
    </button>
@endsection

@section('content')
<form action="{{ route('admin.posts.store') }}" method="POST" id="post-form">
    @csrf
    @include('admin.posts._form', ['post' => null])
</form>
@endsection
