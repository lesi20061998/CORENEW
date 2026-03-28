@extends('admin.layouts.app')
@section('title', 'Sản phẩm')
@section('page-title', 'Danh sách sản phẩm')
@section('page-subtitle', 'Quản lý toàn bộ sản phẩm trong cửa hàng')
@section('page-actions')
    <a href="{{ route('admin.products.trash') }}" class="btn btn-outline-danger me-2">
        <i class="fa-solid fa-trash-can"></i> THÙNG RÁC
    </a>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
        <i class="fa-solid fa-plus-circle"></i> THÊM SẢN PHẨM MỚI
    </a>
@endsection

@section('content')

    {{-- Bộ lọc chuyên sâu --}}
    <form method="GET" action="{{ route('admin.products.index') }}"
        class="card mb-6 border-slate-200 shadow-sm overflow-hidden">
        <div class="card-body p-5 flex flex-wrap gap-5 items-end bg-slate-50/30">
            <div class="flex-1 min-w-[240px]">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">TÌM KIẾM CHI
                    TIẾT</label>
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Nhập tên, mã hiệu SKU, barcode..."
                        class="form-input !py-2.5 !pl-10 text-sm shadow-sm border-slate-200">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-300"></i>
                </div>
            </div>
            <div class="w-56">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">DANH MỤC</label>
                <select name="category_id" class="form-select !py-2.5 text-sm shadow-sm border-slate-200">
                    <option value="">Tất cả danh mục</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->label_indented }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="w-44">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">TRẠNG THÁI</label>
                <select name="status" class="form-select !py-2.5 text-sm font-bold shadow-sm border-slate-200">
                    <option value="">Tất cả trạng thái</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Đang bán công khai</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Đang trong bản nháp</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Đang tạm ẩn đi</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn btn-primary !py-2.5 px-8 shadow-lg shadow-blue-500/20">
                    TÌM KIẾM
                </button>
                @if(request()->hasAny(['search', 'category_id', 'status']))
                    <a href="{{ route('admin.products.index') }}"
                        class="btn bg-white hover:bg-slate-100 text-slate-400 border border-slate-200 !py-2.5 px-4">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </div>
    </form>

    <div class="card shadow-sm border-slate-200 overflow-hidden relative" x-data="productTable()">

        {{-- MODAL SỬA HÀNG LOẠT (BULK EDIT) --}}
        <div x-show="showBulkModal" x-cloak
            class="fixed inset-0 z-[100] overflow-y-auto px-4 py-8 sm:px-0 flex items-center justify-center bg-slate-900/40 backdrop-blur-md"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100">

            <div
                class="bg-white rounded-[40px] shadow-[0_25px_50px_-12px_rgba(0,0,0,0.25)] w-full max-w-[1280px] max-h-[92vh] flex flex-col overflow-hidden border border-white">
                {{-- Header --}}
                <div class="px-10 py-7 border-b border-slate-100 bg-slate-50/30 flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-black text-slate-900 tracking-tighter">QUẢN TRỊ VIÊN <span class="text-slate-300 mx-2">/</span> SỬA ĐỒNG LOẠT
                        </h3>
                        <div class="flex items-center gap-2 mt-2">
                            <span class="px-2.5 py-1 bg-blue-100 text-blue-600 rounded-lg text-[10px] font-black uppercase tracking-widest">Selected <span x-text="selected.length"></span> Items</span>
                            <span class="text-[10px] text-slate-300 font-bold uppercase tracking-widest leading-none">HỆ THỐNG KIỂM SOÁT HÀNG LOẠT</span>
                        </div>
                    </div>
                    <button @click="showBulkModal = false"
                        class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 hover:bg-rose-500 hover:text-white transition-all shadow-inner flex items-center justify-center">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>

                {{-- Body Grid --}}
                <div class="p-10 overflow-y-auto flex-1 grid grid-cols-1 lg:grid-cols-12 gap-10">

                    {{-- Cột 1: Danh sách sản phẩm (LHS) --}}
                    <div
                        class="lg:col-span-3 bg-slate-50/50 border border-slate-100 rounded-[32px] p-6 flex flex-col h-full overflow-hidden shadow-inner">
                        <label
                            class="text-[10px] font-black text-slate-400 uppercase tracking-[.25em] mb-5 flex items-center gap-2">
                            <i class="fa-solid fa-clipboard-list text-blue-500"></i> Đang chỉnh sửa
                        </label>
                        <div class="space-y-3 overflow-y-auto pr-2 custom-scroll flex-1">
                            <template x-for="pid in selected" :key="pid">
                                <div
                                    class="flex items-center gap-3 bg-white p-3 rounded-2xl border border-slate-100 shadow-sm transition-all hover:scale-[1.02] hover:shadow-md group">
                                    <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-[10px] font-black text-slate-400 group-hover:bg-blue-600 group-hover:text-white transition-colors"
                                        x-text="pid"></div>
                                    <span class="text-xs font-black text-slate-600 truncate group-hover:text-blue-900"
                                        x-text="getProductName(pid)"></span>
                                    <button @click="removeSelected(pid)"
                                        class="ml-auto text-slate-200 hover:text-rose-500 transition-colors">
                                        <i class="fa-solid fa-circle-xmark"></i>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>

                    {{-- Cột 2: Danh mục (Center) --}}
                    <div
                        class="lg:col-span-4 bg-white border border-slate-100 rounded-[32px] p-8 shadow-xl shadow-slate-200/20">
                        <label
                            class="text-[10px] font-black text-slate-400 uppercase tracking-[.25em] mb-6 flex items-center gap-2">
                            <i class="fa-solid fa-layer-group text-amber-500"></i> Cập nhật danh mục
                        </label>
                        <div class="max-h-[440px] overflow-y-auto space-y-2 pr-4 custom-scroll">
                            @foreach($categories as $cat)
                                <div class="flex items-center gap-3 py-1.5 group">
                                    <input type="checkbox" x-model="bulkData.category_ids" value="{{ $cat->id }}"
                                        id="bulk_cat_{{ $cat->id }}"
                                        class="w-5 h-5 rounded-lg border-slate-200 text-blue-600 focus:ring-blue-100">
                                    <label for="bulk_cat_{{ $cat->id }}"
                                        class="text-sm font-bold text-slate-500 group-hover:text-blue-600 cursor-pointer select-none transition-colors">
                                        {{ $cat->label_indented ?? $cat->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Cột 3: Rules & Metrics (RHS) --}}
                    <div class="lg:col-span-5 space-y-6">
                        <div class="bg-emerald-50/40 border border-emerald-100 rounded-[40px] p-8 shadow-sm">
                            <label
                                class="text-[10px] font-black text-emerald-600 uppercase tracking-[.25em] mb-6 block">THIẾT
                                LẬP QUY TẮC (RULES)</label>

                            <div class="space-y-6">
                                {{-- Price Rule --}}
                                <div class="space-y-2">
                                    <label
                                        class="text-[10px] font-black text-slate-400 uppercase tracking-widest block pl-1">Quy
                                        tắc điều chỉnh giá</label>
                                    <div class="grid grid-cols-[1fr_130px] gap-3">
                                        <div class="relative">
                                            <select x-model="bulkData.price_rule"
                                                class="form-select !py-2.5 !text-xs font-black bg-white border-slate-100 shadow-sm rounded-2xl w-full">
                                                <option value="fixed">THIẾT LẬP GIÁ MỚI CỐ ĐỊNH</option>
                                                <option value="inc_amount">TĂNG THEO SỐ TIỀN (+ VNĐ)</option>
                                                <option value="dec_amount">GIẢM THEO SỐ TIỀN (- VNĐ)</option>
                                                <option value="inc_percent">TĂNG THEO PHẦN TRĂM ( + % )</option>
                                                <option value="dec_percent">GIẢM THEO PHẦN TRĂM ( - % )</option>
                                            </select>
                                        </div>
                                        <input type="number" x-model="bulkData.price" placeholder="Giá trị..."
                                            class="form-input !py-2.5 !text-sm font-black text-blue-600 bg-white border-slate-100 shadow-sm rounded-2xl text-center">
                                    </div>
                                </div>

                                {{-- Stock Rule --}}
                                <div class="space-y-2">
                                    <label
                                        class="text-[10px] font-black text-slate-400 uppercase tracking-widest block pl-1">Quy
                                        tắc điều chỉnh kho</label>
                                    <div class="grid grid-cols-[1fr_130px] gap-3">
                                        <select x-model="bulkData.stock_rule"
                                            class="form-select !py-2.5 !text-xs font-black bg-white border-slate-100 shadow-sm rounded-2xl w-full">
                                            <option value="fixed">THIẾT LẬP TỒN KHO CỐ ĐỊNH</option>
                                            <option value="inc">CỘNG THÊM VÀO KHO ( + )</option>
                                            <option value="dec">TRỪ BỚT TRONG KHO ( - )</option>
                                        </select>
                                        <input type="number" x-model="bulkData.stock" placeholder="Số lượng..."
                                            class="form-input !py-2.5 !text-sm font-black text-emerald-600 bg-white border-slate-100 shadow-sm rounded-2xl text-center">
                                    </div>
                                </div>

                                {{-- Status & Sorting --}}
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label
                                            class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2 pl-1">Trạng
                                            thái mới</label>
                                        <select x-model="bulkData.status"
                                            class="form-select !py-2.5 !text-xs font-black bg-white border-slate-100 shadow-sm rounded-2xl w-full">
                                            <option value="">(KHÔNG THAY ĐỔI)</option>
                                            <option value="active">CÔNG KHAI (ACTIVE)</option>
                                            <option value="inactive">TẠM ẨN (HIDDEN)</option>
                                            <option value="draft">BẢN NHÁP (DRAFT)</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label
                                            class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2 pl-1">Chỉ
                                            số ưu tiên</label>
                                        <input type="number" x-model="bulkData.sort_order" placeholder="Index..."
                                            class="form-input !py-2.5 !text-sm font-black text-slate-600 bg-white border-slate-100 shadow-sm rounded-2xl text-center">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Info Card --}}
                        <div class="p-6 bg-blue-50/50 rounded-[32px] border border-blue-100/50">
                            <p class="text-[10px] text-blue-400 font-black uppercase tracking-widest leading-relaxed">
                                <i class="fa-solid fa-circle-info mr-1"></i> Lưu ý: Các thay đổi sẽ được áp dụng ngay lập
                                tức cho toàn bộ sản phẩm trong danh sách "Đang chỉnh sửa" bên trái. Hãy kiểm tra kỹ trước
                                khi xác nhận.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="px-10 py-8 bg-slate-900 border-t border-slate-800 flex items-center justify-between">
                    <div class="flex flex-col">
                        <p class="text-white text-sm font-black tracking-tight">XÁC NHẬN TRIỂN KHAI CẬP NHẬT</p>
                        <p class="text-slate-500 text-[10px] font-bold uppercase mt-1">HÀNH ĐỘNG NÀY KHÔNG THỂ HOÀN TÁC</p>
                    </div>
                    <div class="flex gap-3">
                        <button @click="showBulkModal = false"
                            class="px-8 py-3.5 rounded-2xl border border-slate-700 text-slate-400 text-[11px] font-black tracking-widest hover:bg-slate-800 transition-colors uppercase">Hủy
                            bỏ</button>
                        <button @click="runBulkUpdate()" :disabled="selected.length === 0"
                            class="px-12 py-3.5 rounded-2xl bg-blue-600 text-white text-[11px] font-black tracking-[.2em] shadow-2xl shadow-blue-600/40 hover:bg-blue-500 hover:scale-105 active:scale-95 transition-all disabled:opacity-50 disabled:cursor-not-allowed uppercase">
                            Áp dụng các Rules ngay
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Floating Bar báo hiệu đã chọn --}}
        <div x-show="selected.length > 0" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-8"
            class="fixed bottom-8 left-1/2 -translate-x-1/2 z-[90] bg-slate-900 border border-slate-700 shadow-[0_30px_60px_-12px_rgba(0,0,0,0.5)] p-4 pr-5 rounded-[28px] flex items-center gap-8 text-white min-w-[420px]"
            x-cloak>
            <div class="flex items-center gap-4 pl-3 border-r border-slate-700 pr-6">
                <span
                    class="bg-blue-600 text-[11px] font-black w-9 h-9 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-500/30 rotate-3 group-hover:rotate-0 transition-transform"
                    x-text="selected.length"></span>
                <div class="flex flex-col">
                    <span class="text-[10px] font-black uppercase tracking-[.2em] text-blue-400">Sản phẩm</span>
                    <span class="text-xs font-bold text-slate-300">Đã lựa chọn</span>
                </div>
            </div>
            <div class="flex-1 text-sm font-black text-white tracking-tight">Kích hoạt quy tắc sửa hàng loạt?</div>
            <button @click="showBulkModal = true"
                class="bg-white text-slate-900 px-6 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl hover:scale-105 active:scale-95 transition-all flex items-center gap-2">
                <i class="fa-solid fa-bolt-lightning text-amber-500"></i> BẮT ĐẦU SỬA
            </button>
        </div>


        <div class="card-header bg-white border-b border-slate-100 flex items-center justify-between py-5 px-6">
            <div class="flex items-center gap-6">
                 {{-- Tabs Like Image --}}
                <div class="flex items-center gap-2">
                    {{-- Tab All --}}
                    <a href="{{ route('admin.products.index') }}" title="Đang bán" class="relative w-12 h-12 flex items-center justify-center rounded-2xl transition-all duration-300 {{ !request()->routeIs('*.trash') ? 'bg-blue-600 shadow-xl shadow-blue-500/30' : 'bg-white border border-slate-100 shadow-sm' }}">
                        <i class="fa-solid fa-pencil text-sm {{ !request()->routeIs('*.trash') ? 'text-white' : 'text-slate-400' }}"></i>
                        <span class="absolute -top-2 -right-2 bg-slate-100 text-slate-900 text-[10px] font-black px-2 py-0.5 rounded-full border-2 border-white shadow-sm">{{ $counts['all'] }}</span>
                    </a>

                    <div class="w-0.5 h-8 bg-blue-600/30 rounded-full mx-1"></div>

                    {{-- Tab Trash --}}
                    <a href="{{ route('admin.products.trash') }}" title="Thùng rác" class="relative w-12 h-12 flex items-center justify-center rounded-2xl transition-all duration-300 {{ request()->routeIs('*.trash') ? 'bg-blue-600 shadow-xl shadow-blue-500/30' : 'bg-white border border-slate-100 shadow-sm' }}">
                        <i class="fa-solid fa-trash-can text-sm {{ request()->routeIs('*.trash') ? 'text-white' : 'text-slate-400' }}"></i>
                        <span class="absolute -top-2 -right-2 bg-slate-100 text-slate-900 text-[10px] font-black px-2 py-0.5 rounded-full border-2 border-white shadow-sm">{{ $counts['trashed'] }}</span>
                    </a>
                </div>
            </div>
            @if(request()->routeIs('*.trash'))
                <div class="px-4 py-2 bg-rose-50 text-rose-600 rounded-xl border border-rose-100 text-[10px] font-black uppercase tracking-widest flex items-center gap-2">
                   <i class="fa-solid fa-trash-can"></i> Đang xem thùng rác
                </div>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50/50">
                    <tr class="border-b border-slate-100">
                        <th class="px-6 py-4 text-center w-14">
                            <input type="checkbox" @change="toggleAll($event)" :checked="allSelected"
                                class="w-5 h-5 rounded-lg border-slate-200 text-blue-600 focus:ring-blue-100">
                        </th>
                        <th
                            class="px-2 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest w-[420px]">
                            Thông tin mặt hàng</th>
                        <th class="px-4 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Loại
                            hình</th>
                        <th
                            class="px-4 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest w-40">
                            Hạch toán giá</th>
                        <th
                            class="px-4 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest w-28">
                            Lưu kho</th>
                        <th
                            class="px-4 py-4 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest w-32">
                            Thứ tự</th>
                        <th
                            class="px-4 py-4 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest w-40">
                            Trạng thái</th>
                        <th
                            class="px-6 py-4 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest w-24">
                            Quản lý</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($products as $product)
                        <tr class="hover:bg-slate-50/50 transition-all group"
                            :class="selected.includes({{ $product->id }}) ? 'bg-blue-50/40' : ''">
                            <td class="px-6 py-5 text-center">
                                <input type="checkbox" value="{{ $product->id }}" x-model="selected"
                                    class="w-5 h-5 rounded-lg border-slate-200 text-blue-600 focus:ring-blue-100 group-hover:scale-110 transition-transform">
                            </td>
                            <td class="px-2 py-5">
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-16 rounded-[24px] border border-slate-100 overflow-hidden bg-white shadow-sm flex-shrink-0 group-hover:scale-105 transition-transform duration-300">
                                        @if($product->image)
                                            <img src="{{ $product->image }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-slate-200 bg-slate-50">
                                                <i class="fa-solid fa-panorama text-xl"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 overflow-hidden">
                                        <a href="{{ route('admin.products.edit', $product) }}"
                                            class="text-sm font-black text-slate-900 hover:text-blue-600 transition-colors block leading-tight mb-2 truncate">{{ $product->name }}</a>
                                        
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
                                                        headers: {
                                                            'Content-Type': 'application/json',
                                                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                            'Accept': 'application/json',
                                                        },
                                                        body: JSON.stringify({ [fieldName]: this[key] })
                                                    });
                                                } catch (e) { alert('Lỗi cập nhật!'); }
                                            }
                                        }">
                                            {{-- Yêu thích --}}
                                            <span @click="toggle('isFavorite')" :class="isFavorite ? 'bg-rose-50 text-rose-600 border-rose-100 shadow-sm' : 'bg-slate-50/50 text-slate-300 border-slate-100'" 
                                                class="px-2 py-0.5 rounded-lg text-[9px] font-black tracking-widest uppercase border cursor-pointer transition-all hover:scale-105 active:scale-95 flex items-center">
                                                <i class="fa-solid fa-heart text-[8px] mr-1" :class="isFavorite ? 'text-rose-500' : 'text-slate-200'"></i> YÊU THÍCH
                                            </span>

                                            {{-- Bán chạy --}}
                                            <span @click="toggle('isBestSeller')" :class="isBestSeller ? 'bg-blue-50 text-blue-600 border-blue-100 shadow-sm' : 'bg-slate-50/50 text-slate-300 border-slate-100'" 
                                                class="px-2 py-0.5 rounded-lg text-[9px] font-black tracking-widest uppercase border cursor-pointer transition-all hover:scale-105 active:scale-95 flex items-center">
                                                <i class="fa-solid fa-crown text-[8px] mr-1" :class="isBestSeller ? 'text-blue-500' : 'text-slate-200'"></i> BÁN CHẠY
                                            </span>

                                            {{-- Nổi bật --}}
                                            <span @click="toggle('isFeatured')" :class="isFeatured ? 'bg-amber-50 text-amber-600 border-amber-100 shadow-sm' : 'bg-slate-50/50 text-slate-300 border-slate-100'" 
                                                class="px-2 py-0.5 rounded-lg text-[9px] font-black tracking-widest uppercase border cursor-pointer transition-all hover:scale-105 active:scale-95 flex items-center">
                                                <i class="fa-solid fa-fire text-[8px] mr-1" :class="isFeatured ? 'text-amber-500' : 'text-slate-200'"></i> NỔI BẬT
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-5 text-xs font-bold text-slate-500">
                                @if($product->categories->count() > 0)
                                    <div class="flex flex-wrap gap-1.5">
                                        @foreach($product->categories->take(2) as $cat)
                                            <span
                                                class="inline-flex items-center bg-slate-50 px-2 py-1 rounded-lg text-[9px] font-black text-slate-400 border border-slate-100 whitespace-nowrap uppercase tracking-tighter">{{ $cat->name }}</span>
                                        @endforeach
                                        @if($product->categories->count() > 2)
                                            <span
                                                class="text-[10px] text-slate-300 font-black">+{{ $product->categories->count() - 2 }}</span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-[10px] font-black text-slate-200 tracking-[.2em] uppercase">NO CATEGORY</span>
                                @endif
                            </td>
                            <td class="px-4 py-5">
                                <div class="flex flex-col">
                                    <span
                                        class="text-sm font-black text-blue-600">{{ number_format((float) $product->price, 0, ',', '.') }}<small
                                            class="ml-0.5 opacity-60">đ</small></span>
                                    @if($product->compare_price > $product->price)
                                        <span
                                            class="text-[10px] text-slate-300 line-through font-bold">{{ number_format((float) $product->compare_price, 0, ',', '.') }}đ</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-5">
                                <div class="flex flex-col gap-1">
                                    <div class="flex items-center gap-1.5">
                                        <span
                                            class="w-1.5 h-1.5 rounded-full {{ $product->stock > 10 ? 'bg-emerald-500' : 'bg-rose-500 shadow-[0_0_8px_rgba(244,63,94,0.5)]' }}"></span>
                                        <span class="text-xs font-black text-slate-700">{{ $product->stock }} <small
                                                class="font-bold opacity-40 uppercase">PCS</small></span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-5 text-center">
                                <div x-data="{ editing: false, sort: {{ $product->sort_order ?? 0 }} }"
                                    @click.away="editing = false" class="relative group/sort">
                                    <span
                                        class="text-[11px] font-black text-slate-500 bg-slate-100/60 px-3 py-1.5 rounded-2xl cursor-pointer hover:bg-slate-900 hover:text-white transition-all block w-fit mx-auto shadow-sm"
                                        x-show="!editing" @click="editing = true">
                                        <i
                                            class="fa-solid fa-hashtag text-[9px] opacity-40 mr-1"></i>{{ $product->sort_order ?? 0 }}
                                    </span>
                                    <div x-show="editing" x-cloak class="absolute left-1/2 -translate-x-1/2 z-10 -top-2">
                                        <input type="number" x-model="sort"
                                            @keydown.enter="quickUpdate({{ $product->id }}, {sort_order: sort}); editing = false"
                                            class="form-input !py-2 !px-2 text-xs w-20 font-black text-center border-blue-600 shadow-2xl focus:ring-4 focus:ring-blue-100 rounded-2xl transition-all scale-110">
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-5 text-center">
                                @if($product->status === 'active')
                                    <span
                                        class="inline-flex items-center py-1.5 px-3 bg-emerald-50 text-emerald-600 font-black text-[9px] tracking-widest rounded-xl border border-emerald-100">
                                        <i class="fa-solid fa-circle-check text-[10px] mr-2"></i> ĐANG BÁN
                                    </span>
                                @elseif($product->status === 'draft')
                                    <span
                                        class="inline-flex items-center py-1.5 px-3 bg-amber-50 text-amber-600 font-black text-[9px] tracking-widest rounded-xl border border-amber-100">
                                        <i class="fa-solid fa-pen-nib text-[10px] mr-2"></i> BẢN NHÁP
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center py-1.5 px-3 bg-slate-50 text-slate-400 font-black text-[9px] tracking-widest rounded-xl border border-slate-100">
                                        <i class="fa-solid fa-eye-slash text-[10px] mr-2"></i> TẠM ẨN
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-5 text-right">
                                <div
                                    class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-all translate-x-4 group-hover:translate-x-0">
                                    <a href="{{ route('admin.products.edit', $product) }}"
                                        class="w-10 h-10 rounded-2xl bg-white border border-slate-100 text-slate-400 flex items-center justify-center hover:bg-blue-600 hover:text-white hover:border-blue-600 transition-all shadow-sm hover:shadow-lg hover:shadow-blue-500/20 active:scale-90">
                                        <i class="fa-solid fa-pencil text-sm"></i>
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline">
                                        @csrf @method('DELETE')
                                        <button onclick="return confirm('Xác nhận xóa hoàn toàn mặt hàng này?')"
                                            class="w-10 h-10 rounded-2xl bg-white border border-slate-100 text-slate-400 flex items-center justify-center hover:bg-rose-600 hover:text-white hover:border-rose-600 transition-all shadow-sm hover:shadow-lg hover:shadow-rose-500/20 active:scale-90">
                                            <i class="fa-solid fa-trash-can text-sm"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-24 text-center">
                                <div class="flex flex-col items-center gap-4">
                                    <div
                                        class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center text-slate-200">
                                        <i class="fa-solid fa-box-open text-4xl"></i>
                                    </div>
                                    <p class="text-sm font-black text-slate-300 uppercase tracking-widest">Không có mặt hàng nào
                                        khớp với bộ lọc</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
            <div class="px-8 py-6 border-t border-slate-100 bg-slate-50/50">
                {{ $products->links() }}
            </div>
        @endif
    </div>

@endsection

@push('scripts')
    <script>
        function productTable() {
            return {
                selected: [],
                allSelected: false,
                showBulkModal: false,
                bulkData: {
                    price: '',
                    price_rule: 'fixed',
                    stock: '',
                    stock_rule: 'fixed',
                    status: '',
                    sort_order: '',
                    category_ids: []
                },
                productNames: @json($products->pluck('name', 'id')),

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
                            window.location.reload();
                        } else {
                            alert(result.message || 'Lỗi!');
                        }
                    } catch (error) { alert('Lỗi kết nối server.'); }
                },

                async runBulkUpdate() {
                    if (!confirm(`Xác nhận thực thi Rules cho ${this.selected.length} sản phẩm?`)) return;

                    const url = `{{ route('admin.products.bulk-update') }}`;
                    try {
                        const response = await fetch(url, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({
                                ids: this.selected,
                                ...this.bulkData
                            })
                        });
                        const result = await response.json();
                        if (result.success) {
                            window.location.reload();
                        } else {
                            alert(result.message || 'Lỗi!');
                        }
                    } catch (error) { alert('Lỗi kết nối server.'); }
                }
            }
        }
    </script>
    <style>
        .custom-scroll::-webkit-scrollbar {
            width: 5px;
        }

        .custom-scroll::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        .custom-scroll::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
@endpush