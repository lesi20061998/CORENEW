@extends('admin.layouts.app')
@section('title', 'Sản phẩm: ' . $product->name)
@section('page-title', $product->name)
@section('page-actions')
    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-primary">
        <i class="fa-solid fa-pencil"></i> Chỉnh sửa
    </a>
    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary ml-2">
        <i class="fa-solid fa-arrow-left"></i> Quay lại
    </a>
@endsection

@section('content')
<div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

    {{-- Hình ảnh --}}
    <div>
        @if($product->image)
            <img src="{{ $product->image }}" class="w-full rounded-2xl object-cover ring-1 ring-gray-100 shadow-sm" alt="{{ $product->name }}">
        @else
            <div class="w-full aspect-square rounded-2xl bg-gray-100 flex flex-col items-center justify-center text-gray-400">
                <i class="fa-solid fa-image text-5xl mb-2 opacity-30"></i>
                <p class="text-sm">Chưa có hình ảnh</p>
            </div>
        @endif
    </div>

    {{-- Chi tiết --}}
    <div class="xl:col-span-2 space-y-5">
        <div class="card">
            <div class="card-header"><h3 class="text-sm font-bold text-gray-700">Thông tin sản phẩm</h3></div>
            <div class="card-body">
                <dl class="grid grid-cols-2 gap-x-6 gap-y-3 text-sm">
                    <div><dt class="text-gray-500 text-xs font-medium uppercase tracking-wide mb-0.5">Tên sản phẩm</dt><dd class="font-semibold text-gray-800">{{ $product->name }}</dd></div>
                    <div><dt class="text-gray-500 text-xs font-medium uppercase tracking-wide mb-0.5">Slug</dt><dd><code class="text-xs bg-gray-100 px-2 py-0.5 rounded-lg">{{ $product->slug }}</code></dd></div>
                    <div><dt class="text-gray-500 text-xs font-medium uppercase tracking-wide mb-0.5">Giá bán</dt><dd class="font-bold text-blue-600 text-base">{{ $product->formatted_price }}</dd></div>
                    <div><dt class="text-gray-500 text-xs font-medium uppercase tracking-wide mb-0.5">Tồn kho</dt>
                        <dd><span class="{{ $product->stock > 0 ? 'badge-green' : 'badge-red' }}">{{ $product->stock }} cái</span></dd>
                    </div>
                    <div><dt class="text-gray-500 text-xs font-medium uppercase tracking-wide mb-0.5">Trạng thái</dt>
                        <dd>
                            @if($product->status === 'active') <span class="badge-green">Đang bán</span>
                            @elseif($product->status === 'draft') <span class="badge-yellow">Nháp</span>
                            @else <span class="badge-gray">Ẩn</span>
                            @endif
                        </dd>
                    </div>
                    <div><dt class="text-gray-500 text-xs font-medium uppercase tracking-wide mb-0.5">Ngày tạo</dt><dd class="text-gray-700">{{ $product->created_at->format('d/m/Y H:i') }}</dd></div>
                </dl>
            </div>
        </div>

        @if($product->description)
        <div class="card">
            <div class="card-header"><h3 class="text-sm font-bold text-gray-700">Mô tả</h3></div>
            <div class="card-body text-sm text-gray-700 leading-relaxed">{{ $product->description }}</div>
        </div>
        @endif

        @if($product->attributeValues->count() > 0)
        <div class="card">
            <div class="card-header"><h3 class="text-sm font-bold text-gray-700">Thuộc tính</h3></div>
            <div class="card-body space-y-3">
                @foreach($product->attributeValues->groupBy('attribute.name') as $attrName => $values)
                <div class="flex items-center gap-3">
                    <span class="text-xs font-semibold text-gray-500 w-20 flex-shrink-0">{{ $attrName }}</span>
                    <div class="flex flex-wrap gap-1.5">
                        @foreach($values as $val)
                            @if($val->color_code)
                                <span class="w-6 h-6 rounded-full border-2 border-white shadow ring-1 ring-gray-200 inline-block" title="{{ $val->value }}" style="background:{{ $val->color_code }}"></span>
                            @else
                                <span class="badge-gray">{{ $val->value }}</span>
                            @endif
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <div class="flex gap-3">
            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-primary">
                <i class="fa-solid fa-pencil"></i> Chỉnh sửa
            </a>
            <form action="{{ route('admin.products.destroy', $product) }}" method="POST">
                @csrf @method('DELETE')
                <button onclick="return confirm('Xóa sản phẩm này vĩnh viễn?')" class="btn btn-danger">
                    <i class="fa-solid fa-trash"></i> Xóa sản phẩm
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
