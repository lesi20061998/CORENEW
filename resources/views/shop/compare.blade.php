@extends('layouts.app')

@section('title', 'So sánh sản phẩm')

@section('content')
<div class="rts-compare-area rts-section-gap">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="title-area-between mb--40">
                    <h2 class="title text-3xl font-bold">So sánh sản phẩm</h2>
                    <a href="{{ route('shop.index') }}" class="rts-btn btn-primary">Tiếp tục mua sắm</a>
                </div>
                
                @if($products->count() > 0)
                <div class="compare-table-wrapper table-responsive">
                    <table class="table table-bordered text-center align-middle">
                        <thead>
                            <tr class="bg-gray-50">
                                <th style="width: 200px;">Thông số</th>
                                @foreach($products as $product)
                                <th style="min-width: 250px;">
                                    <div class="product-compare-header p-3">
                                        <form action="{{ route('compare.remove') }}" method="POST" class="text-right mb-2">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <button type="submit" class="text-danger"><i class="fa-solid fa-xmark"></i> Xóa</button>
                                        </form>
                                        <a href="{{ route('shop.show', $product->slug) }}">
                                            <img src="{{ $product->thumbnail_url }}" alt="{{ $product->name }}" class="mx-auto mb-3" style="max-height: 150px;">
                                            <h4 class="text-sm font-bold h-12 overflow-hidden">{{ $product->name }}</h4>
                                        </a>
                                    </div>
                                </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="font-bold">Giá</td>
                                @foreach($products as $product)
                                <td class="text-primary font-bold text-lg">{{ $product->formatted_price }}</td>
                                @endforeach
                            </tr>
                            <tr>
                                <td class="font-bold">Danh mục</td>
                                @foreach($products as $product)
                                <td>{{ $product->categories->first()->name ?? 'N/A' }}</td>
                                @endforeach
                            </tr>
                            <tr>
                                <td class="font-bold">Mô tả</td>
                                @foreach($products as $product)
                                <td class="text-xs text-muted">
                                    <div class="max-h-24 overflow-y-auto">
                                        {{ Str::limit(strip_tags($product->description), 150) }}
                                    </div>
                                </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td class="font-bold">Hành động</td>
                                @foreach($products as $product)
                                <td>
                                    @if(!$product->has_contact_price)
                                        <a href="javascript:void(0)" onclick="cart.add({{ $product->id }}, this)" class="rts-btn btn-primary btn-sm py-2 px-3 w-100">
                                            Thêm vào giỏ
                                        </a>
                                    @else
                                        <a href="tel:{{ setting('hotline') }}" class="rts-btn btn-primary btn-sm py-2 px-3 w-100">
                                            Liên hệ
                                        </a>
                                    @endif
                                </td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-20 bg-gray-50 rounded">
                    <i class="fa-solid fa-arrows-retweet text-6xl text-gray-300 mb-4"></i>
                    <p class="text-xl text-gray-500">Chưa có sản phẩm nào trong danh sách so sánh.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
