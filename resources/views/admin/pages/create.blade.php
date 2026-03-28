@extends('admin.layouts.app')
@section('title', 'Thêm trang')
@section('page-title', 'Thêm trang mới')
@section('page-subtitle', 'Tạo trang nội dung tĩnh mới')
@section('content')
<form action="{{ route('admin.pages.store') }}" method="POST">
    @csrf
    @include('admin.pages._form', ['page' => null])
    <div class="flex justify-end gap-3 mt-6">
        <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary">
            <i class="fa-solid fa-arrow-left"></i> Hủy
        </a>
        <button type="submit" class="btn btn-primary">
            <i class="fa-solid fa-floppy-disk"></i> Tạo trang
        </button>
    </div>
</form>
@endsection
