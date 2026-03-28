@extends('admin.layouts.app')
@section('title', isset($campaign) ? 'Sửa Flash Sale' : 'Tạo Flash Sale')
@section('page-title', isset($campaign) ? 'Sửa chiến dịch: ' . $campaign->name : 'Tạo chiến dịch Flash Sale')
@section('page-subtitle', 'Cấu hình thời gian, sản phẩm và mức giảm giá')
@section('page-actions')
    <a href="{{ route('admin.flash-sales.index') }}" class="btn btn-secondary">
        <i class="fa-solid fa-arrow-left"></i> Quay lại
    </a>
@endsection

@section('content')
<form method="POST"
      action="{{ isset($campaign) ? route('admin.flash-sales.update', $campaign) : route('admin.flash-sales.store') }}">
    @csrf
    @if(isset($campaign)) @method('PUT') @endif

    <div style="display:grid;grid-template-columns:1fr 340px;gap:20px;align-items:start;">

        {{-- LEFT: Main info + Items --}}
        <div style="display:flex;flex-direction:column;gap:16px;">

            {{-- Thông tin chiến dịch --}}
            <div class="card">
                <div class="card-header"><p class="card-title">Thông tin chiến dịch</p></div>
                <div class="card-body" style="display:flex;flex-direction:column;gap:14px;">

                    <div>
                        <label class="form-label">Tên chiến dịch <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $campaign->name ?? '') }}"
                               class="form-input" placeholder="VD: Flash Sale Cuối Tuần" required>
                        @error('name')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="form-label">Mô tả</label>
                        <textarea name="description" rows="2" class="form-input"
                                  placeholder="Mô tả ngắn về chiến dịch...">{{ old('description', $campaign->description ?? '') }}</textarea>
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                        <div>
                            <label class="form-label">Bắt đầu <span class="text-red-500">*</span></label>
                            <input type="datetime-local" name="starts_at"
                                   value="{{ old('starts_at', isset($campaign) ? $campaign->starts_at->format('Y-m-d\TH:i') : '') }}"
                                   class="form-input" required>
                            @error('starts_at')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="form-label">Kết thúc <span class="text-red-500">*</span></label>
                            <input type="datetime-local" name="ends_at"
                                   value="{{ old('ends_at', isset($campaign) ? $campaign->ends_at->format('Y-m-d\TH:i') : '') }}"
                                   class="form-input" required>
                            @error('ends_at')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                    </div>

                </div>
            </div>

            {{-- Danh sách sản phẩm / danh mục --}}
            <div class="card" id="items-card">
                <div class="card-header" style="display:flex;justify-content:space-between;align-items:center;">
                    <p class="card-title">Sản phẩm & Danh mục Flash Sale</p>
                    <div style="display:flex;gap:8px;">
                        <button type="button" onclick="addItem('product')" class="btn btn-secondary" style="font-size:12px;padding:5px 12px;">
                            <i class="fa-solid fa-plus"></i> Thêm sản phẩm
                        </button>
                        <button type="button" onclick="addItem('category')" class="btn btn-secondary" style="font-size:12px;padding:5px 12px;">
                            <i class="fa-solid fa-folder-plus"></i> Thêm danh mục
                        </button>
                    </div>
                </div>
                <div class="card-body" style="padding:0;">
                    <table class="w-full" id="items-table">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="tbl-th">Loại</th>
                                <th class="tbl-th">Sản phẩm / Danh mục</th>
                                <th class="tbl-th">Kiểu giảm</th>
                                <th class="tbl-th">Giá trị giảm</th>
                                <th class="tbl-th">Giới hạn SL</th>
                                <th class="tbl-th w-8"></th>
                            </tr>
                        </thead>
                        <tbody id="items-body">
                            @php $existingItems = isset($campaign) ? $campaign->items : collect(); @endphp
                            @forelse($existingItems as $item)
                            <tr class="item-row">
                                <td class="tbl-td">
                                    <input type="hidden" name="items[{{ $loop->index }}][product_id]"  value="{{ $item->product_id }}">
                                    <input type="hidden" name="items[{{ $loop->index }}][category_id]" value="{{ $item->category_id }}">
                                    <span class="text-xs font-medium {{ $item->product_id ? 'text-blue-600' : 'text-purple-600' }}">
                                        {{ $item->product_id ? 'Sản phẩm' : 'Danh mục' }}
                                    </span>
                                </td>
                                <td class="tbl-td">
                                    <span class="text-sm text-gray-700">
                                        {{ $item->product_id ? ($item->product->name ?? '—') : ($item->category->name ?? '—') }}
                                    </span>
                                </td>
                                <td class="tbl-td">
                                    <select name="items[{{ $loop->index }}][discount_type]" class="form-select text-xs" style="padding:4px 8px;">
                                        <option value="percent" {{ $item->discount_type === 'percent' ? 'selected' : '' }}>%</option>
                                        <option value="fixed"   {{ $item->discount_type === 'fixed'   ? 'selected' : '' }}>VNĐ</option>
                                    </select>
                                </td>
                                <td class="tbl-td">
                                    <input type="number" name="items[{{ $loop->index }}][discount_value]"
                                           value="{{ $item->discount_value }}" min="0" step="1"
                                           class="form-input text-xs" style="width:90px;padding:4px 8px;" required>
                                </td>
                                <td class="tbl-td">
                                     <input type="number" name="items[{{ $loop->index }}][sale_limit]"
                                            value="{{ $item->sale_limit }}" min="0" placeholder="K.giới hạn"
                                            class="form-input text-xs" style="width:80px;padding:4px 8px;">
                                </td>
                                <td class="tbl-td">
                                    <button type="button" onclick="this.closest('tr').remove()"
                                            class="action-btn hover:bg-red-50 hover:text-red-600">
                                        <i class="fa-solid fa-xmark text-xs"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr id="empty-row">
                                <td colspan="6" class="tbl-td text-center text-gray-400 py-8 text-sm">
                                    Chưa có item nào. Nhấn "Thêm sản phẩm" hoặc "Thêm danh mục" để bắt đầu.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        {{-- RIGHT: Status + Options --}}
        <div style="display:flex;flex-direction:column;gap:16px;">

            <div class="card">
                <div class="card-header"><p class="card-title">Trạng thái</p></div>
                <div class="card-body">
                    <select name="status" class="form-select">
                        <option value="draft"  {{ old('status', $campaign->status ?? 'draft') === 'draft'  ? 'selected' : '' }}>Nháp</option>
                        <option value="active" {{ old('status', $campaign->status ?? 'draft') === 'active' ? 'selected' : '' }}>Kích hoạt</option>
                        <option value="ended"  {{ old('status', $campaign->status ?? 'draft') === 'ended'  ? 'selected' : '' }}>Kết thúc</option>
                    </select>
                    <p class="text-xs text-gray-400 mt-2">
                        Chiến dịch chỉ áp dụng khi trạng thái là "Kích hoạt" và trong khoảng thời gian đã cài.
                    </p>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><p class="card-title">Lưu ý</p></div>
                <div class="card-body" style="font-size:12px;color:#64748b;line-height:1.7;">
                    <ul style="list-style:disc;padding-left:16px;">
                        <li>Giảm theo <strong>%</strong>: nhập số từ 1–100</li>
                        <li>Giảm theo <strong>VNĐ</strong>: nhập số tiền cố định</li>
                        <li>Nếu sản phẩm thuộc danh mục được cài, giá sản phẩm cụ thể sẽ được ưu tiên hơn</li>
                        <li>Giới hạn SL: để trống = không giới hạn</li>
                    </ul>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-full">
                <i class="fa-solid fa-floppy-disk"></i>
                {{ isset($campaign) ? 'Cập nhật chiến dịch' : 'Tạo chiến dịch' }}
            </button>

        </div>
    </div>
</form>

{{-- Modal chọn sản phẩm --}}
<div id="modal-product" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:9999;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:12px;width:600px;max-height:80vh;display:flex;flex-direction:column;overflow:hidden;">
        <div style="padding:16px 20px;border-bottom:1px solid #e2e8f0;display:flex;justify-content:space-between;align-items:center;">
            <p style="font-weight:700;font-size:15px;">Chọn sản phẩm</p>
            <button type="button" onclick="closeModal('product')" style="background:none;border:none;cursor:pointer;font-size:16px;color:#94a3b8;"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div style="padding:12px 20px;border-bottom:1px solid #f1f5f9;">
            <input type="text" id="product-search" placeholder="Tìm sản phẩm..."
                   oninput="filterProducts(this.value)"
                   style="width:100%;padding:7px 10px;border:1.5px solid #e2e8f0;border-radius:8px;font-size:13px;outline:none;">
        </div>
        <div id="product-list" style="overflow-y:auto;flex:1;padding:8px 0;">
            @foreach($products as $product)
            <div class="product-option" data-name="{{ strtolower($product->name) }}"
                 onclick="selectProduct({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }})"
                 style="display:flex;align-items:center;gap:10px;padding:8px 20px;cursor:pointer;transition:background .15s;"
                 onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background=''">
                @if($product->image)
                <img src="{{ $product->image }}" style="width:36px;height:36px;object-fit:cover;border-radius:6px;">
                @else
                <div style="width:36px;height:36px;background:#f1f5f9;border-radius:6px;display:flex;align-items:center;justify-content:center;">
                    <i class="fa-solid fa-box text-gray-300 text-xs"></i>
                </div>
                @endif
                <div style="flex:1;">
                    <p style="font-size:13px;font-weight:600;color:#1e293b;">{{ $product->name }}</p>
                    <p style="font-size:11px;color:#64748b;">{{ number_format($product->price, 0, ',', '.') }}₫</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Modal chọn danh mục --}}
<div id="modal-category" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:9999;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:12px;width:480px;max-height:70vh;display:flex;flex-direction:column;overflow:hidden;">
        <div style="padding:16px 20px;border-bottom:1px solid #e2e8f0;display:flex;justify-content:space-between;align-items:center;">
            <p style="font-weight:700;font-size:15px;">Chọn danh mục</p>
            <button type="button" onclick="closeModal('category')" style="background:none;border:none;cursor:pointer;font-size:16px;color:#94a3b8;"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div id="category-list" style="overflow-y:auto;flex:1;padding:8px 0;">
            @foreach($categories as $cat)
            <div onclick="selectCategory({{ $cat->id }}, '{{ addslashes($cat->name) }}')"
                 style="padding:10px 20px;cursor:pointer;font-size:13px;font-weight:500;color:#1e293b;transition:background .15s;"
                 onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background=''">
                <i class="fa-solid fa-folder text-purple-400 mr-2"></i>{{ $cat->name }}
            </div>
            @endforeach
        </div>
    </div>
</div>

@push('scripts')
<script>
let itemIndex = {{ isset($campaign) ? $campaign->items->count() : 0 }};
let pendingType = null;

function addItem(type) {
    pendingType = type;
    document.getElementById('modal-' + type).style.display = 'flex';
}

function closeModal(type) {
    document.getElementById('modal-' + type).style.display = 'none';
}

function selectProduct(id, name, price) {
    closeModal('product');
    removeEmptyRow();
    const tbody = document.getElementById('items-body');
    const tr = document.createElement('tr');
    tr.className = 'item-row';
    tr.innerHTML = `
        <td class="tbl-td">
            <input type="hidden" name="items[${itemIndex}][product_id]" value="${id}">
            <input type="hidden" name="items[${itemIndex}][category_id]" value="">
            <span class="text-xs font-medium text-blue-600">Sản phẩm</span>
        </td>
        <td class="tbl-td">
            <span class="text-sm text-gray-700">${name}</span>
            <p class="text-xs text-gray-400">${Number(price).toLocaleString('vi-VN')}₫</p>
        </td>
        <td class="tbl-td">
            <select name="items[${itemIndex}][discount_type]" class="form-select text-xs" style="padding:4px 8px;">
                <option value="percent">%</option>
                <option value="fixed">VNĐ</option>
            </select>
        </td>
        <td class="tbl-td">
            <input type="number" name="items[${itemIndex}][discount_value]" min="0" step="1" placeholder="10"
                   class="form-input text-xs" style="width:90px;padding:4px 8px;" required>
        </td>
        <td class="tbl-td">
            <input type="number" name="items[${itemIndex}][sale_limit]" min="0" placeholder="K.giới hạn"
                   class="form-input text-xs" style="width:80px;padding:4px 8px;">
        </td>
        <td class="tbl-td">
            <button type="button" onclick="this.closest('tr').remove()"
                    class="action-btn hover:bg-red-50 hover:text-red-600">
                <i class="fa-solid fa-xmark text-xs"></i>
            </button>
        </td>`;
    tbody.appendChild(tr);
    itemIndex++;
}

function selectCategory(id, name) {
    closeModal('category');
    removeEmptyRow();
    const tbody = document.getElementById('items-body');
    const tr = document.createElement('tr');
    tr.className = 'item-row';
    tr.innerHTML = `
        <td class="tbl-td">
            <input type="hidden" name="items[${itemIndex}][product_id]" value="">
            <input type="hidden" name="items[${itemIndex}][category_id]" value="${id}">
            <span class="text-xs font-medium text-purple-600">Danh mục</span>
        </td>
        <td class="tbl-td">
            <span class="text-sm text-gray-700"><i class="fa-solid fa-folder text-purple-400 mr-1"></i>${name}</span>
        </td>
        <td class="tbl-td">
            <select name="items[${itemIndex}][discount_type]" class="form-select text-xs" style="padding:4px 8px;">
                <option value="percent">%</option>
                <option value="fixed">VNĐ</option>
            </select>
        </td>
        <td class="tbl-td">
            <input type="number" name="items[${itemIndex}][discount_value]" min="0" step="1" placeholder="20"
                   class="form-input text-xs" style="width:90px;padding:4px 8px;" required>
        </td>
        <td class="tbl-td">
            <input type="number" name="items[${itemIndex}][sale_limit]" min="0" placeholder="K.giới hạn"
                   class="form-input text-xs" style="width:80px;padding:4px 8px;">
        </td>
        <td class="tbl-td">
            <button type="button" onclick="this.closest('tr').remove()"
                    class="action-btn hover:bg-red-50 hover:text-red-600">
                <i class="fa-solid fa-xmark text-xs"></i>
            </button>
        </td>`;
    tbody.appendChild(tr);
    itemIndex++;
}

function removeEmptyRow() {
    const empty = document.getElementById('empty-row');
    if (empty) empty.remove();
}

function filterProducts(q) {
    document.querySelectorAll('.product-option').forEach(el => {
        el.style.display = el.dataset.name.includes(q.toLowerCase()) ? '' : 'none';
    });
}

// Close modal on backdrop click
['modal-product','modal-category'].forEach(id => {
    document.getElementById(id).addEventListener('click', function(e) {
        if (e.target === this) closeModal(id.replace('modal-',''));
    });
});
</script>
@endpush
@endsection
