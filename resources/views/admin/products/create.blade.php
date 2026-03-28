@extends('admin.layouts.app')
@section('title', 'Them san pham')
@section('page-title', 'Thêm sản phẩm mới')
@section('page-subtitle', 'Tạo sản phẩm mới cho cửa hàng')
@section('page-actions')
    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary btn-sm">
        <i class="fa-solid fa-xmark"></i> Hủy
    </a>
    <button type="submit" form="product-form" class="btn btn-primary btn-sm">
        <i class="fa-solid fa-floppy-disk"></i> Lưu sản phẩm
    </button>
@endsection

@section('content')
<form action="{{ route('admin.products.store') }}" method="POST" id="product-form">
    @csrf
    @php $currentAttributes = []; @endphp
    @include('admin.products._form')
</form>
@endsection
