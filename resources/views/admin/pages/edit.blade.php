@extends('admin.layouts.app')
@section('title', 'Chỉnh sửa trang')
@section('page-title', 'Chỉnh sửa: ' . $page->title)
@section('page-subtitle', 'Cập nhật nội dung trang')
@section('content')
<form action="{{ route('admin.pages.update', $page) }}" method="POST">
    @csrf @method('PUT')
    @include('admin.pages._form', ['page' => $page])
    <div class="flex justify-end gap-3 mt-6">
        <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary">
            <i class="fa-solid fa-arrow-left"></i> Hủy
        </a>
        <button type="submit" class="btn btn-primary">
            <i class="fa-solid fa-floppy-disk"></i> Lưu thay đổi
        </button>
    </div>
</form>
@endsection
