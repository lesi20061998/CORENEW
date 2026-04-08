@extends('admin.layouts.app')
@section('title', 'Sản phẩm')
@section('page-title', 'Danh sách sản phẩm')
@section('page-subtitle', 'Quản lý toàn bộ sản phẩm trong cửa hàng')
@section('page-actions')
    <a href="{{ route('admin.products.trash') }}" class="btn btn-secondary me-2">
        <i class="fa-solid fa-trash-can"></i> THÙNG RÁC
    </a>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
        <i class="fa-solid fa-plus-circle"></i> THÊM SẢN PHẨM MỚI
    </a>
@endsection

@section('content')
    <div x-data="productTable()" class="relative">
        {{-- Bộ lọc chuyên sâu --}}
        <form method="GET" action="{{ route('admin.products.index') }}" class="card mb-8">
            <div class="p-8 flex flex-wrap gap-6 items-end">
                <div class="flex-1 min-w-[300px]">
                    <label class="form-label">Tìm kiếm mặt hàng</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Tên, SKU, Barcode..."
                            class="form-input pl-12 shadow-sm border-slate-100">
                        <i class="fa-solid fa-magnifying-glass absolute left-5 top-1/2 -translate-y-1/2 text-slate-300"></i>
                    </div>
                </div>
                <div class="w-64" x-data="{ open: false }">
                    <label class="form-label">Chuyên mục</label>
                    <div class="relative">
                        <button type="button" @click="open = !open" 
                                class="form-input text-left shadow-sm border-slate-100 flex items-center justify-between w-full h-[42px] bg-white group hover:border-blue-400 transition-all">
                            <span class="truncate text-sm font-bold text-slate-700">
                                @php
                                    $selectedCats = (array) request('category_ids', []);
                                    $countSelected = count($selectedCats);
                                @endphp
                                @if($countSelected > 0)
                                    {{ $countSelected }} danh mục đã chọn
                                @else
                                    Tất cả danh mục
                                @endif
                            </span>
                            <i class="fa-solid fa-chevron-down text-[10px] text-slate-300 group-hover:text-blue-500 transition-colors"></i>
                        </button>
                        
                        <div x-show="open" 
                             @click.away="open = false" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             style="display:none;"
                             class="absolute top-full left-0 right-0 mt-3 bg-white border border-slate-100 rounded-[24px] shadow-2xl z-[100] max-h-[400px] overflow-hidden flex flex-col">
                            
                            <div class="p-4 border-b border-slate-50 flex items-center justify-between bg-slate-50/50">
                                <span class="text-[10px] font-black uppercase text-slate-400 tracking-widest pl-1">Chọn chuyên mục</span>
                                <button type="button" @click="open = false" class="text-blue-600 text-[10px] font-black uppercase tracking-tighter hover:underline">Xong</button>
                            </div>

                            <div class="overflow-y-auto custom-scroll p-2 flex-1">
                                @foreach($categories as $cat)
                                    <label class="flex items-center gap-3 p-3 hover:bg-blue-50/50 rounded-xl cursor-pointer transition-all group/cat">
                                        <div class="relative flex items-center justify-center">
                                            <input type="checkbox" name="category_ids[]" value="{{ $cat->id }}" 
                                                   {{ in_array($cat->id, $selectedCats) ? 'checked' : '' }}
                                                   class="peer w-5 h-5 rounded-lg border-slate-200 text-blue-600 focus:ring-blue-100 appearance-none bg-white border checked:bg-blue-600 checked:border-blue-600 transition-all">
                                            <i class="fa-solid fa-check absolute text-white text-[10px] opacity-0 peer-checked:opacity-100 transition-opacity pointer-events-none"></i>
                                        </div>
                                        <span class="text-xs font-bold text-slate-600 group-hover/cat:text-blue-700 transition-colors">{{ $cat->label_indented }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @if($countSelected > 0)
                                <div class="p-3 border-t border-slate-50 text-center">
                                    <a href="{{ route('admin.products.index', request()->except('category_ids')) }}" class="text-[9px] font-black uppercase text-rose-500 hover:text-rose-600 transition-colors">
                                        <i class="fa-solid fa-rotate-left mr-1"></i> Xóa bộ lọc mục
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="w-56">
                    <label class="form-label">Trạng thái Hiển thị</label>
                    <select name="status" class="form-select shadow-sm border-slate-100">
                        <option value="">Tất cả trạng thái</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Đang công khai</option>
                        <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Bản nháp</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Tạm ẩn</option>
                    </select>
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="btn btn-primary px-8">
                        Lọc dữ liệu
                    </button>
                    @if(request()->hasAny(['search', 'category_id', 'category_ids', 'status']))
                        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary px-4">
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>
                    @endif
                </div>
            </div>
        </form>

        <div class="card">
            <div class="card-header border-b border-slate-50">
                <h3 class="card-title">Hệ thống kho vận ({{ $counts['all'] }})</h3>
                <div class="flex items-center gap-3">
                    {{-- Quick Tabs --}}
                    <div class="flex bg-slate-100 p-1.5 rounded-2xl gap-1">
                        <a href="{{ route('admin.products.index') }}"
                            class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all {{ !request()->routeIs('*.trash') ? 'bg-white shadow-sm text-blue-600' : 'text-slate-400 hover:text-slate-600' }}">
                            Đang bán
                        </a>
                        <a href="{{ route('admin.products.trash') }}"
                            class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all {{ request()->routeIs('*.trash') ? 'bg-white shadow-sm text-rose-600' : 'text-slate-400 hover:text-slate-600' }}">
                            Thùng rác ({{ $counts['trashed'] }})
                        </a>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto custom-scroll">
                <table class="w-full min-w-[1200px]">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="tbl-th w-14 text-center">
                                <input type="checkbox" @change="toggleAll($event)" :checked="allSelected"
                                    class="w-5 h-5 rounded-lg border-slate-200 text-blue-600 focus:ring-blue-100">
                            </th>
                            <th class="tbl-th w-[400px]">Chi tiết sản phẩm</th>
                            <th class="tbl-th">Phân loại</th>
                            <th class="tbl-th w-44">Thương vụ & Giá</th>
                            <th class="tbl-th w-32">Lưu kho</th>
                            <th class="tbl-th w-32 text-center">Ưu tiên</th>
                            <th class="tbl-th w-44 text-center">Hiển thị</th>
                            <th class="tbl-th w-24 text-right pr-10">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 bg-white">
                        @forelse($products as $product)
                            <tr class="group transition-all hover:bg-slate-50/30"
                                :class="selected.includes({{ $product->id }}) ? 'bg-blue-50/40' : ''">
                                <td class="tbl-td text-center">
                                    <input type="checkbox" value="{{ $product->id }}" x-model="selected"
                                        class="w-5 h-5 rounded-lg border-slate-200 text-blue-600 group-hover:scale-110 transition-transform">
                                </td>
                                <td class="tbl-td">
                                    <div class="flex items-center gap-5">
                                        <div
                                            class="w-20 h-20 rounded-[28px] border-2 border-slate-50 overflow-hidden bg-white shadow-sm flex-shrink-0 group-hover:scale-105 transition-transform duration-500">
                                            @if($product->thumbnail_url)
                                                <img src="{{ $product->thumbnail_url }}" class="w-full h-full object-cover">
                                            @else
                                                <div
                                                    class="w-full h-full flex items-center justify-center text-slate-100 bg-slate-50">
                                                    <i class="fa-solid fa-box text-2xl"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <a href="{{ route('admin.products.edit', $product) }}"
                                                class="text-[15px] font-black text-slate-900 group-hover:text-blue-600 transition-colors block leading-tight mb-2 line-clamp-2 uppercase tracking-tighter">{{ $product->name }}</a>

                                            <div class="flex flex-wrap gap-2" x-data="{ 
                                                    isFeatured: {{ $product->is_featured ? 'true' : 'false' }},
                                                    isFavorite: {{ $product->is_favorite ? 'true' : 'false' }},
                                                    isBestSeller: {{ $product->is_best_seller ? 'true' : 'false' }},
                                                    async toggle(key) {
                                                        this[key] = !this[key];
                                                        const fieldMap = { 'isFeatured': 'is_featured', 'isFavorite': 'is_favorite', 'isBestSeller': 'is_best_seller' };
                                                        const fieldName = fieldMap[key];
                                                        try {
                                                            await fetch('{{ route('admin.products.quick-update', $product->id) }}', {
                                                                method: 'PATCH',
                                                                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                                                                body: JSON.stringify({ [fieldName]: this[key] })
                                                            });
                                                        } catch (e) { adminToast('Lỗi', 'Không thể cập nhật!', 'error'); }
                                                    }
                                                }">
                                                <span @click="toggle('isFeatured')"
                                                    :class="isFeatured ? 'bg-amber-50 text-amber-600 border-amber-100' : 'bg-slate-50 text-slate-300 border-slate-100'"
                                                    class="px-2.5 py-0.5 rounded-lg text-[9px] font-black uppercase border cursor-pointer transition-all hover:scale-105 select-none tracking-widest">
                                                    <i class="fa-solid fa-star mr-1"></i> Nổi bật
                                                </span>
                                                <span @click="toggle('isFavorite')"
                                                    :class="isFavorite ? 'bg-rose-50 text-rose-600 border-rose-100' : 'bg-slate-50 text-slate-300 border-slate-100'"
                                                    class="px-2.5 py-0.5 rounded-lg text-[9px] font-black uppercase border cursor-pointer transition-all hover:scale-105 select-none tracking-widest">
                                                    <i class="fa-solid fa-heart mr-1"></i> Yêu thích
                                                </span>
                                                <span @click="toggle('isBestSeller')"
                                                    :class="isBestSeller ? 'bg-blue-50 text-blue-600 border-blue-100' : 'bg-slate-50 text-slate-300 border-slate-100'"
                                                    class="px-2.5 py-0.5 rounded-lg text-[9px] font-black uppercase border cursor-pointer transition-all hover:scale-105 select-none tracking-widest">
                                                    <i class="fa-solid fa-crown mr-1"></i> Bán chạy
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="tbl-td">
                                    @if($product->categories->count() > 0)
                                        <div class="flex flex-wrap gap-1 max-w-[150px]">
                                            @foreach($product->categories->take(5) as $cat)
                                                <span class="px-1.5 py-0.5 bg-slate-100 text-[9px] font-black text-slate-500 uppercase tracking-tighter rounded-md whitespace-nowrap">{{ $cat->name }}</span>
                                            @endforeach
                                            @if($product->categories->count() > 5)
                                                <span class="px-1.5 py-0.5 bg-blue-50 text-[9px] font-black text-blue-500 uppercase tracking-tighter rounded-md">+{{ $product->categories->count() - 5 }}</span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-[10px] font-black text-slate-200 tracking-widest uppercase">Không
                                            có</span>
                                    @endif
                                </td>
                                <td class="tbl-td">
                                    <div class="flex flex-col leading-none"
                                        x-data="{ editing: false, price: {{ (float) $product->price }} }">
                                        <div x-show="!editing" @click="editing = true"
                                            class="cursor-pointer group/price flex items-center">
                                            <span class="text-[16px] font-black text-blue-600 tracking-tighter"
                                                x-text="new Intl.NumberFormat('vi-VN').format(price)"></span>
                                            <small
                                                class="text-[10px] ml-1 font-black uppercase text-blue-300 opacity-0 group-hover/price:opacity-100 transition-opacity"><i
                                                    class="fa-solid fa-pen-to-square"></i></small>
                                        </div>
                                        <input x-show="editing" type="number" x-model="price" @click.away="editing = false"
                                            @keydown.enter="editing = false; quickUpdate({{ $product->id }}, {price: price})"
                                            class="form-input w-24 py-1 px-2 text-[14px] font-black text-blue-600 border-blue-200">
                                        @if($product->compare_price > $product->price)
                                            <span
                                                class="text-[11px] text-slate-300 line-through font-bold mt-1">{{ number_format((float) $product->compare_price, 0, ',', '.') }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="tbl-td">
                                    <div class="flex items-center gap-1.5"
                                        x-data="{ editing: false, stock: {{ $product->stock }} }">
                                        <span
                                            class="w-2.5 h-2.5 rounded-full {{ $product->stock > 5 ? 'bg-green-500' : 'bg-rose-500 shadow-lg shadow-rose-500/30 animate-pulse' }}"></span>
                                        <div x-show="!editing" @click="editing = true"
                                            class="cursor-pointer group/stock flex items-center">
                                            <span class="text-[14px] font-black text-slate-700 tracking-tight"
                                                x-text="stock"></span>
                                            <small
                                                class="text-[10px] ml-1 font-black uppercase text-slate-300 opacity-0 group-hover/stock:opacity-100 transition-opacity"><i
                                                    class="fa-solid fa-pen-to-square"></i></small>
                                        </div>
                                        <input x-show="editing" type="number" x-model="stock" @click.away="editing = false"
                                            @keydown.enter="editing = false; quickUpdate({{ $product->id }}, {stock: stock})"
                                            class="form-input w-16 py-1 px-2 text-[14px] font-black text-slate-700 border-slate-200">
                                    </div>
                                </td>
                                <td class="tbl-td text-center">
                                    <div x-data="{ editing: false, sort: {{ $product->sort_order ?? 0 }} }">
                                        <div x-show="!editing" @click="editing = true" class="cursor-pointer">
                                            <span
                                                class="text-[12px] font-black text-slate-400 bg-slate-100/50 px-3 py-1.5 rounded-xl border border-slate-100 hover:border-slate-300 transition-colors">#<span
                                                    x-text="sort"></span></span>
                                        </div>
                                        <input x-show="editing" type="number" x-model="sort" @click.away="editing = false"
                                            @keydown.enter="editing = false; quickUpdate({{ $product->id }}, {sort_order: sort})"
                                            class="form-input w-14 py-1 px-2 text-[12px] font-bold text-center border-slate-200">
                                    </div>
                                </td>
                                <td class="tbl-td text-center">
                                    @if($product->status === 'active')
                                        <span class="badge badge-green">Hoạt động</span>
                                    @elseif($product->status === 'draft')
                                        <span class="badge badge-slate bg-slate-100 text-slate-500">Bản nháp</span>
                                    @else
                                        <span class="badge badge-rose">Tạm ẩn</span>
                                    @endif
                                </td>
                                <td class="tbl-td text-right pr-6">
                                    <div
                                        class="flex items-center justify-end gap-2 opacity-100 transition-all">
                                        <form
                                            action="{{ route('admin.duplicate.item', ['type' => 'product', 'id' => $product->id]) }}"
                                            method="POST">
                                            @csrf
                                            <button type="submit"
                                                class="action-btn hover:border-blue-200 hover:text-blue-600 font-black text-[10px]"
                                                title="Copy to EN">EN</button>
                                        </form>
                                        <button type="button" 
                                                @click="qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' + encodeURIComponent('{{ url($product->slug) }}'); qrProductName = '{{ $product->name }}'; qrModalOpen = true"
                                                class="action-btn hover:border-emerald-200 hover:text-emerald-600" 
                                                title="Mã QR Scan">
                                            <i class="fa-solid fa-qrcode"></i>
                                        </button>
                                        <a href="{{ route('admin.products.edit', $product) }}" class="action-btn edit"
                                            title="Sửa">
                                            <i class="fa-solid fa-pen-nib"></i>
                                        </a>
                                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button type="submit" onclick="return confirm('Xóa vĩnh viễn?')"
                                                class="action-btn del" title="Xóa">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="tbl-td text-center py-32">
                                    <div class="flex flex-col items-center gap-4 text-slate-200">
                                        <i class="fa-solid fa-box-open text-6xl opacity-20"></i>
                                        <p class="text-[10px] font-black uppercase tracking-[0.3em]">Kho hàng đang trống</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($products->hasPages())
                <div class="p-8 border-t border-slate-50 bg-slate-50/30">
                    {{ $products->links() }}
                </div>
            @endif

            {{-- Floating Bulk Action Bar --}}
            <div x-show="selected.length > 0" style="display:none;"
                :style="{ display: selected.length > 0 ? 'flex' : 'none' }"
                x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-y-full opacity-0"
                x-transition:enter-end="translate-y-0 opacity-100" x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="translate-y-0 opacity-100" x-transition:leave-end="translate-y-full opacity-0"
                class="fixed bottom-10 left-1/2 -translate-x-1/2 bg-slate-900/90 backdrop-blur-md text-white px-8 py-4 rounded-[40px] shadow-2xl z-[1000] flex items-center gap-8 border border-white/10 min-w-[500px]">

                <div class="flex flex-col flex-shrink-0">
                    <span class="text-[10px] font-black uppercase text-slate-400 tracking-[0.2em] mb-0.5">Đã chọn</span>
                    <div class="flex items-center gap-2">
                        <span class="text-3xl font-black text-blue-400" x-text="selected.length"></span>
                        <span class="text-xs font-bold text-slate-300">mục</span>
                    </div>
                </div>

                <div class="h-10 w-px bg-white/10"></div>

                <button @click="bulkModalOpen = true"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-10 h-10 rounded-2xl text-[12px] font-black uppercase tracking-widest transition-all shadow-lg shadow-blue-600/30">
                    <i class="fa-solid fa-sliders-h me-2"></i> THIẾT LẬP HÀNG LOẠT
                </button>

                <div class="h-10 w-px bg-white/10"></div>

                <button @click="bulkDelete()"
                    class="w-12 h-12 rounded-2xl bg-rose-500/20 text-rose-500 hover:bg-rose-500 hover:text-white transition-all flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-trash-can text-lg"></i>
                </button>

                <button @click="selected = []; allSelected = false"
                    class="text-slate-500 hover:text-white transition-colors p-2">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
        </div>

        {{-- BULK EDIT MODAL --}}
        <div x-show="bulkModalOpen" @open-bulk-modal.window="bulkModalOpen = true" style="display:none;"
            class="fixed inset-0 z-[10001] flex items-center justify-center p-6 bg-slate-900/60 backdrop-blur-sm">

            <div @click.away="bulkModalOpen = false"
                class="bg-white rounded-[40px] shadow-2xl w-full max-w-4xl overflow-hidden animate-in zoom-in duration-300 flex flex-col max-h-[90vh]">

                <div class="p-8 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-black text-slate-900 tracking-tight">Sửa hàng loạt</h3>
                        <p class="text-sm text-slate-500 font-bold uppercase tracking-widest mt-1">Cập nhật cho <span
                                class="text-blue-600" x-text="selected.length"></span> mục đã chọn</p>
                    </div>
                    <button @click="bulkModalOpen = false"
                        class="w-12 h-12 rounded-2xl bg-slate-50 text-slate-400 hover:text-slate-600 transition-colors flex items-center justify-center">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>

                <div class="p-8 overflow-y-auto flex-1 custom-scroll bg-slate-50/10">
                    <div class="grid grid-cols-2 gap-8">
                        {{-- Common Fixes --}}
                        <div class="space-y-8">
                            <div class="flex flex-col gap-2">
                                <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest pl-1">Trạng
                                    thái mới</label>
                                <select id="m-bulk-status"
                                    class="form-select rounded-2xl border-slate-100 py-3 font-bold text-slate-700">
                                    <option value="">Giữ nguyên hiện tại</option>
                                    <option value="active">Công khai trực tuyến</option>
                                    <option value="inactive">Tạm ẩn khỏi gian hàng</option>
                                    <option value="draft">Chuyển thành bản nháp</option>
                                </select>
                            </div>

                            {{-- Promotions --}}
                            <div class="p-6 bg-white rounded-3xl border border-slate-100 shadow-sm space-y-6">
                                <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest pl-1">Gắn thẻ khuyến mãi (Hàng loạt)</label>
                                
                                <div class="grid grid-cols-1 gap-4">
                                    <div class="flex items-center justify-between p-3 bg-slate-50/50 rounded-2xl border border-slate-100/50">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-xl bg-amber-500/10 text-amber-500 flex items-center justify-center">
                                                <i class="fa-solid fa-star text-xs"></i>
                                            </div>
                                            <span class="text-xs font-black text-slate-700 uppercase tracking-tight">Sản phẩm Nổi bật</span>
                                        </div>
                                        <select id="m-bulk-featured" class="form-select rounded-xl border-slate-100 text-xs font-bold py-2 w-44">
                                            <option value="">Giữ nguyên</option>
                                            <option value="1">Bật (Hàng loạt)</option>
                                            <option value="0">Tắt (Hàng loạt)</option>
                                        </select>
                                    </div>

                                    <div class="flex items-center justify-between p-3 bg-slate-50/50 rounded-2xl border border-slate-100/50">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-xl bg-rose-500/10 text-rose-500 flex items-center justify-center">
                                                <i class="fa-solid fa-heart text-xs"></i>
                                            </div>
                                            <span class="text-xs font-black text-slate-700 uppercase tracking-tight">Sản phẩm Yêu thích</span>
                                        </div>
                                        <select id="m-bulk-favorite" class="form-select rounded-xl border-slate-100 text-xs font-bold py-2 w-44">
                                            <option value="">Giữ nguyên</option>
                                            <option value="1">Bật (Hàng loạt)</option>
                                            <option value="0">Tắt (Hàng loạt)</option>
                                        </select>
                                    </div>

                                    <div class="flex items-center justify-between p-3 bg-slate-50/50 rounded-2xl border border-slate-100/50">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-xl bg-blue-500/10 text-blue-500 flex items-center justify-center">
                                                <i class="fa-solid fa-crown text-xs"></i>
                                            </div>
                                            <span class="text-xs font-black text-slate-700 uppercase tracking-tight">Sản phẩm Bán chạy</span>
                                        </div>
                                        <select id="m-bulk-bestseller" class="form-select rounded-xl border-slate-100 text-xs font-bold py-2 w-44">
                                            <option value="">Giữ nguyên</option>
                                            <option value="1">Bật (Hàng loạt)</option>
                                            <option value="0">Tắt (Hàng loạt)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-col gap-4 p-6 bg-white rounded-3xl border border-slate-100 shadow-sm">
                                <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Thay đổi Giá
                                    bán</label>
                                <select id="m-bulk-price-rule" class="form-select rounded-xl border-slate-100 font-bold">
                                    <option value="fixed">Gán giá cố định</option>
                                    <option value="inc_amount">+ Theo số tiền (VNĐ)</option>
                                    <option value="dec_amount">- Theo số tiền (VNĐ)</option>
                                    <option value="inc_percent">+ Theo phần trăm (%)</option>
                                    <option value="dec_percent">- Theo phần trăm (%)</option>
                                </select>
                                <input type="number" id="m-bulk-price" placeholder="Nhập giá trị..."
                                    class="form-input rounded-xl border-slate-100 font-black text-blue-600 py-3">
                            </div>

                            <div class="flex flex-col gap-4 p-6 bg-white rounded-3xl border border-slate-100 shadow-sm">
                                <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Thay đổi Kho
                                    hàng</label>
                                <select id="m-bulk-stock-rule" class="form-select rounded-xl border-slate-100 font-bold">
                                    <option value="fixed">Gán số lượng cố định</option>
                                    <option value="inc">+ Thêm vào kho hiện tại</option>
                                    <option value="dec">- Trừ khỏi kho hiện tại</option>
                                </select>
                                <input type="number" id="m-bulk-stock" placeholder="Số lượng..."
                                    class="form-input rounded-xl border-slate-100 font-black py-3">
                            </div>
                        </div>

                        {{-- Category Picker --}}
                        <div class="flex flex-col h-full">
                            <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest pl-1 mb-4">Gán
                                thêm vào danh mục</label>
                            <div
                                class="bg-white border text-slate-700 border-slate-100 rounded-3xl p-6 overflow-y-auto h-[350px] shadow-sm custom-scroll">
                                @foreach($categories as $cat)
                                    <label
                                        class="flex items-center gap-3 p-3 hover:bg-slate-50 rounded-xl cursor-pointer transition-colors border-b last:border-0 border-slate-50">
                                        <input type="checkbox" name="bulk_category[]" value="{{ $cat->id }}"
                                            class="w-5 h-5 rounded-lg border-slate-200 text-blue-600">
                                        <span class="text-sm font-bold text-slate-600">{{ $cat->label_indented }}</span>
                                    </label>
                                @endforeach
                            </div>
                            <p class="text-[10px] text-slate-400 font-bold mt-4 italic">* Lưu ý: Sản phẩm sẽ được gán thêm
                                vào các mục được tick chọn bên trên.</p>
                        </div>
                    </div>
                </div>

                <div class="p-8 border-t border-slate-100 bg-white flex items-center justify-end gap-4">
                    <button @click="bulkModalOpen = false"
                        class="px-8 py-4 font-black text-[12px] uppercase tracking-widest text-slate-400 hover:text-slate-600 transition-colors">
                        Hủy bỏ
                    </button>
                    <button @click="bulkActionFromModal()"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-10 py-4 rounded-2xl text-[12px] font-black uppercase tracking-widest transition-all shadow-xl shadow-blue-600/30">
                        Xác nhận cập nhật h.loạt
                    </button>
                </div>
            </div>
        </div>
        {{-- QR CODE MODAL --}}
        <div x-show="qrModalOpen" style="display:none;"
            class="fixed inset-0 z-[10002] flex items-center justify-center p-6 bg-slate-900/60 backdrop-blur-sm">
            <div @click.away="qrModalOpen = false" 
                class="bg-white rounded-[40px] shadow-2xl w-full max-w-sm overflow-hidden animate-in zoom-in duration-200">
                <div class="p-8 text-center">
                    <div class="flex justify-between items-start mb-6">
                        <h4 class="text-xl font-black text-slate-900 tracking-tight">QR Sản phẩm</h4>
                        <button @click="qrModalOpen = false" class="text-slate-300 hover:text-slate-600 transition-colors">
                            <i class="fa-solid fa-circle-xmark text-2xl"></i>
                        </button>
                    </div>
                    
                    <div class="bg-slate-50 p-6 rounded-[32px] mb-6 inline-block">
                        <img :src="qrUrl" class="w-[200px] h-[200px] mx-auto shadow-sm rounded-xl border-4 border-white" alt="QR Code">
                    </div>
                    
                    <h5 class="text-sm font-black text-slate-700 uppercase tracking-tight mb-2 line-clamp-1" x-text="qrProductName"></h5>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-8">Quét để xem chi tiết sản phẩm</p>
                    
                    <div class="flex flex-col gap-3">
                        <button @click="downloadQr()"
                           class="bg-emerald-500 hover:bg-emerald-600 text-white w-full py-4 rounded-2xl text-[12px] font-black uppercase tracking-widest transition-all shadow-lg shadow-emerald-500/20">
                            Tải mã QR về máy
                        </button>
                        <button @click="qrModalOpen = false" class="text-slate-400 text-[10px] font-black uppercase tracking-widest hover:text-slate-600 transition-colors">
                            Đóng cửa sổ
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div> {{-- End of productTable scope --}}
@endsection

@push('scripts')
    <script>
        function productTable() {
            return {
                selected: [],
                allSelected: false,
                productNames: @json($products->pluck('name', 'id')),

                bulkModalOpen: false,
                qrModalOpen: false,
                qrUrl: '',
                qrProductName: '',

                getProductName(id) {
                    return this.productNames[id] || 'Mặt hàng #' + id;
                },

                removeSelected(id) {
                    this.selected = this.selected.filter(i => i !== id);
                },

                toggleAll(e) {
                    const ids = @json($products->pluck('id'));
                    this.selected = e.target.checked ? ids : [];
                },

                async quickUpdate(id, data) {
                    const url = `{{ route('admin.products.quick-update', ':id') }}`.replace(':id', id);
                    try {
                        const response = await fetch(url, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify(data)
                        });
                        const result = await response.json();
                        if (result.success) {
                            showToast('Cập nhật thành công!');
                        } else {
                            alert(result.message || 'Lỗi cập nhật nhanh');
                        }
                    } catch (error) { console.error(error); }
                },

                async bulkActionFromModal() {
                    const status = document.getElementById('m-bulk-status').value;
                    const price = document.getElementById('m-bulk-price').value;
                    const priceRule = document.getElementById('m-bulk-price-rule').value;
                    const stock = document.getElementById('m-bulk-stock').value;
                    const stockRule = document.getElementById('m-bulk-stock-rule').value;
                    const featured = document.getElementById('m-bulk-featured').value;
                    const favorite = document.getElementById('m-bulk-favorite').value;
                    const bestseller = document.getElementById('m-bulk-bestseller').value;

                    const catInputs = document.querySelectorAll('input[name="bulk_category[]"]:checked');
                    const categoryIds = Array.from(catInputs).map(cb => cb.value);

                    if (!status && !price && !stock && categoryIds.length === 0 && !featured && !favorite && !bestseller) {
                        return alert('Vui lòng chọn ít nhất một thông tin cần thay đổi');
                    }

                    if (!confirm(`Áp dụng thay đổi cho ${this.selected.length} sản phẩm?`)) return;

                    try {
                        const response = await fetch('{{ route('admin.products.bulk-update') }}', {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({
                                ids: this.selected,
                                status: status,
                                price: price,
                                price_rule: priceRule,
                                stock: stock,
                                stock_rule: stockRule,
                                is_featured: featured,
                                is_favorite: favorite,
                                is_best_seller: bestseller,
                                category_ids: categoryIds
                            })
                        });
                        const result = await response.json();
                        if (result.success) {
                            window.location.reload();
                        } else {
                            alert(result.message || 'Lôi cập nhật hàng loạt');
                        }
                    } catch (error) { console.error(error); }
                },

                async downloadQr() {
                    try {
                        const response = await fetch(this.qrUrl);
                        const blob = await response.blob();
                        const url = window.URL.createObjectURL(blob);
                        const a = document.createElement('a');
                        a.href = url;
                        a.download = `qr-${this.qrProductName.toLowerCase().replace(/\s+/g, '-')}.png`;
                        document.body.appendChild(a);
                        a.click();
                        window.URL.revokeObjectURL(url);
                        document.body.removeChild(a);
                    } catch (error) {
                        console.error('Error downloading QR:', error);
                        window.open(this.qrUrl, '_blank');
                    }
                },

                async bulkDelete() {
                    if (!confirm(`Xóa vĩnh viễn ${this.selected.length} sản phẩm đã chọn?`)) return;
                    // Note: This would usually need a custom bulk delete route, 
                    // for now we'll just loop or alert if not available.
                    alert('Tính năng xóa hàng loạt đang được cập nhật.');
                }
            }
        }
    </script>
@endpush