@extends('layouts.app')

@section('title', 'Danh sách yêu thích - ' . setting('site_name'))

@section('content')
<!-- rts breadcrumb area start -->
<div class="rts-breadcrumb-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-inner-area">
                    <h1 class="title">Danh sách yêu thích</h1>
                    <div class="navigation-area">
                        <a href="{{ route('home') }}">Trang chủ</a>
                        <i class="fa-solid fa-chevron-right"></i>
                        <span>Wishlist</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- rts breadcrumb area end -->

<!-- rts wishlist area start -->
<div class="rts-wishlist-area rts-section-gap">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                @if($products->isEmpty())
                    <div class="text-center py-5 border rounded-3xl bg-light">
                        <div class="mb-4"><i class="fa-light fa-heart h1 text-muted opacity-20"></i></div>
                        <h4 class="mb-3">Danh sách yêu thích đang trống</h4>
                        <p class="text-muted mb-4">Hãy thêm những sản phẩm bạn yêu thích để dễ dàng mua sắm sau này.</p>
                        <a href="{{ route('shop.index') }}" class="rts-btn btn-primary px-5 rounded-pill">Khám phá cửa hàng</a>
                    </div>
                @else
                    <div class="wishlist-table-area table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Giá tiền</th>
                                    <th>Trình trạng</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                <tr>
                                    <td>
                                        <div class="product-item">
                                            <div class="product-thumb">
                                                <img src="{{ $product->image ? asset($product->image) : asset('theme/images/shop/01.png') }}" alt="{{ $product->name }}" style="width: 80px; height: 80px; object-fit: cover; border-radius: 10px;">
                                            </div>
                                            <div class="product-info ml--20">
                                                <h4 class="title"><a href="{{ route('shop.show', $product->slug) }}">{{ $product->name }}</a></h4>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="price text-primary font-bold">{{ $product->formatted_price }}</span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $product->stock_status == 'out_of_stock' ? 'outstock' : 'instock' }}">
                                            {{ $product->stock_status == 'out_of_stock' ? 'Hết hàng' : 'Còn hàng' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="actions d-flex gap-3 align-items-center">
                                            @if($product->stock_status != 'out_of_stock' && !$product->has_contact_price)
                                                <button onclick="cart.add({{ $product->id }}, this)" class="rts-btn btn-primary btn-sm rounded-pill px-4">Thêm vào giỏ</button>
                                            @else
                                                <a href="tel:{{ setting('hotline') }}" class="rts-btn btn-secondary btn-sm rounded-pill px-4">Liên hệ</a>
                                            @endif
                                            
                                            <form action="{{ route('wishlist.remove') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                <button type="submit" class="action-btn remove p-2 border-0 bg-transparent text-muted hover:text-danger"><i class="fa-regular fa-x"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="wishlist-action-area mt--30">
                        <a href="{{ route('shop.index') }}" class="rts-btn btn-primary rounded-pill px-5">Tiếp tục mua hàng</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
<!-- rts wishlist area end -->
@endsection
