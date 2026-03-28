@extends('admin.layouts.app')
@section('title', 'Chinh sua san pham')
@section('page-title', 'Chỉnh sửa: ' . $product->name)
@section('page-subtitle', 'Cập nhật thông tin sản phẩm')
@if($product?->slug)
    @section('preview-url', url('/' . $product->slug))
@endif
@section('page-actions')
    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary btn-sm">
        <i class="fa-solid fa-xmark"></i> Hủy
    </a>
    <button type="submit" form="product-form" class="btn btn-primary btn-sm">
        <i class="fa-solid fa-floppy-disk"></i> Lưu thay đổi
    </button>
@endsection

@section('content')
<form action="{{ route('admin.products.update', $product) }}" method="POST" id="product-form">
    @csrf @method('PUT')
    @include('admin.products._form')
</form>
@endsection
