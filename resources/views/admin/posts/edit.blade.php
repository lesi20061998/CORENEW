@extends('admin.layouts.app')
@section('title', 'Chỉnh sửa bài viết')
@section('page-title', 'Chỉnh sửa: ' . $post->title)
@section('page-subtitle', 'Cập nhật nội dung bài viết')
@if($post?->slug)
    @section('preview-url', url('/' . $post->slug))
@endif
@section('page-actions')
    <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary btn-sm">
        <i class="fa-solid fa-xmark"></i> Hủy
    </a>
    <button type="submit" form="post-form" class="btn btn-primary btn-sm">
        <i class="fa-solid fa-floppy-disk"></i> Lưu thay đổi
    </button>
@endsection

@section('content')
<form action="{{ route('admin.posts.update', $post) }}" method="POST" id="post-form">
    @csrf @method('PUT')
    @include('admin.posts._form', ['post' => $post])
</form>
@endsection
