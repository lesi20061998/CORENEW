@extends('layouts.app')

@section('title', 'Danh sách yêu thích - ' . setting('site_name'))

@section('content')
    <!-- rts breadcrumb area start -->
    <x-breadcrumb :items="[['label' => 'Danh sách yêu thích']]" />
    <!-- rts breadcrumb area end -->
    <div class="rts-cart-area rts-section-gap bg_light-1">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-12">
                    <div class="rts-cart-list-area wishlist">
                        @if($products->isEmpty())
                            <div class="text-center py-5">
                                <h4 class="title">Danh sách yêu thích đang trống</h4>
                                <a href="{{ route('shop.index') }}" class="rts-btn btn-primary radious-sm mt--20">Tiếp tục mua hàng</a>
                            </div>
                        @else
                            <div class="single-cart-area-list head">
                                <div class="product-main">
                                    <p>Sản phẩm</p>
                                </div>
                                <div class="price">
                                    <p>Giá</p>
                                </div>
                                <div class="quantity">
                                    <p>Tình trạng</p>
                                </div>
                                <div class="subtotal">
                                    <p>Hành động</p>
                                </div>
                                <div class="button-area">
                                    <!-- Empty for layout alignment -->
                                </div>
                            </div>
                            @foreach($products as $product)
                                <div class="single-cart-area-list main item-parent" x-data="{ qty: 1 }">
                                    <div class="product-main-cart">
                                        <form action="{{ route('wishlist.remove') }}" method="POST" id="remove-wishlist-{{ $product->id }}">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <div class="close" onclick="document.getElementById('remove-wishlist-{{ $product->id }}').submit();" style="cursor: pointer;">
                                                <i class="fa-regular fa-xmark"></i>
                                            </div>
                                        </form>
                                        <div class="thumbnail">
                                            <img src="{{ $product->thumbnail_url ?: asset('theme/images/shop/01.png') }}" alt="{{ $product->name }}">
                                        </div>
                                        <div class="information">
                                            <h6 class="title"><a href="{{ route('shop.show', $product->slug) }}">{{ $product->name }}</a></h6>
                                            <span>SKU: {{ $product->sku ?? 'N/A' }}</span>
                                        </div>
                                    </div>
                                    <div class="price">
                                        <p>{{ $product->formatted_price }}</p>
                                    </div>
                                    <div class="quantity">
                                        <span class="badge {{ $product->stock > 0 ? 'instock' : 'outstock' }}" 
                                              style="background: {{ $product->stock > 0 ? '#629d23' : '#dc2626' }}; color: #fff; padding: 5px 12px; border-radius: 20px; font-size: 12px;">
                                            {{ $product->stock > 0 ? 'Còn hàng' : 'Hết hàng' }}
                                        </span>
                                    </div>
                                    <div class="subtotal">
                                        <div class="quantity-edit" style="width: 100px;">
                                            <input type="text" class="input" x-model="qty" readonly>
                                            <div class="button-wrapper-action">
                                                <button class="button" @click="qty > 1 ? qty-- : 1"><i class="fa-regular fa-chevron-down"></i></button>
                                                <button class="button plus" @click="qty++"><i class="fa-regular fa-chevron-up"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="button-area">
                                        @if($product->stock > 0 && !$product->has_contact_price)
                                            <a href="javascript:void(0);" @click="cart.add({{ $product->id }}, $event.target, qty)" class="rts-btn btn-primary radious-sm with-icon">
                                                <div class="btn-text">Thêm vào giỏ</div>
                                                <div class="arrow-icon"><i class="fa-regular fa-cart-shopping"></i></div>
                                                <div class="arrow-icon"><i class="fa-regular fa-cart-shopping"></i></div>
                                            </a>
                                        @else
                                            <a href="tel:{{ setting('hotline') }}" class="rts-btn btn-primary radious-sm">Liên hệ ngay</a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection