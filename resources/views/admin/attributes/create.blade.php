@extends('admin.layouts.app')
@section('title', 'Thêm thuộc tính')
@section('page-title', 'Thêm thuộc tính mới')

@section('content')
<form action="{{ route('admin.attributes.store') }}" method="POST">
    @csrf
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

        <div class="xl:col-span-2 space-y-5">

            <div class="card">
                <div class="card-header"><h3 class="text-sm font-bold text-gray-700">Thông tin thuộc tính</h3></div>
                <div class="card-body space-y-4">
                    <div>
                        <label class="form-label">Tên thuộc tính <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            placeholder="Ví dụ: Màu sắc, Kích thước..."
                            class="form-input @error('name') ring-2 ring-red-300 border-red-400 @enderror">
                        @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Loại hiển thị</label>
                            <select name="type" class="form-select">
                                <option value="select"   {{ old('type') === 'select'   ? 'selected' : '' }}>Danh sách chọn</option>
                                <option value="checkbox" {{ old('type') === 'checkbox' ? 'selected' : '' }}>Checkbox (nhiều lựa chọn)</option>
                                <option value="radio"    {{ old('type') === 'radio'    ? 'selected' : '' }}>Radio (một lựa chọn)</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Thứ tự hiển thị</label>
                            <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0" class="form-input">
                        </div>
                    </div>
                    <label class="flex items-center gap-3 cursor-pointer p-3 rounded-xl border border-gray-100 hover:bg-gray-50 transition">
                        <input type="checkbox" name="is_filterable" value="1" id="is_filterable"
                            {{ old('is_filterable', true) ? 'checked' : '' }}
                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 w-4 h-4">
                        <div>
                            <p class="text-sm font-medium text-gray-700">Hiển thị trong bộ lọc</p>
                            <p class="text-xs text-gray-400">Khách hàng có thể lọc sản phẩm theo thuộc tính này</p>
                        </div>
                    </label>
                </div>
            </div>

            {{-- Giá trị --}}
            <div class="card">
                <div class="card-header flex items-center justify-between">
                    <h3 class="text-sm font-bold text-gray-700">Danh sách giá trị</h3>
                    <button type="button" id="addValue"
                        class="inline-flex items-center gap-1.5 text-xs font-semibold text-blue-600 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition">
                        <i class="fa-solid fa-plus"></i> Thêm giá trị
                    </button>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-3 gap-2 text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2 px-1">
                        <span class="col-span-2">Tên giá trị</span>
                        <span>Màu sắc (tùy chọn)</span>
                    </div>
                    <div id="valuesContainer" class="space-y-2">
                        <div class="value-row flex items-center gap-2">
                            <input type="text" name="values[0][value]" placeholder="Ví dụ: Đỏ, XL, Cotton..." required
                                class="form-input flex-1">
                            <input type="color" name="values[0][color_code]" title="Chọn màu"
                                class="w-10 h-9 rounded-lg border border-gray-200 cursor-pointer p-0.5 flex-shrink-0">
                            <button type="button" class="remove-value action-btn hover:text-red-500 hover:bg-red-50 flex-shrink-0">
                                <i class="fa-solid fa-xmark text-xs"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-3">
            <button type="submit" class="w-full btn-primary justify-center py-2.5">
                <i class="fa-solid fa-plus"></i> Tạo thuộc tính
            </button>
            <a href="{{ route('admin.attributes.index') }}" class="w-full btn-secondary justify-center py-2.5">
                Hủy bỏ
            </a>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
let idx = 1;
document.getElementById('addValue').addEventListener('click', () => {
    const row = document.createElement('div');
    row.className = 'value-row flex items-center gap-2';
    row.innerHTML = `
        <input type="text" name="values[${idx}][value]" placeholder="Ví dụ: Đỏ, XL, Cotton..." required
            class="form-input flex-1" style="border:1px solid #e5e7eb;border-radius:0.5rem;padding:0.5rem 0.75rem;font-size:0.875rem;width:100%;outline:none;">
        <input type="color" name="values[${idx}][color_code]" title="Chọn màu"
            class="w-10 h-9 rounded-lg border border-gray-200 cursor-pointer p-0.5 flex-shrink-0">
        <button type="button" class="remove-value p-1.5 rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50 transition flex-shrink-0">
            <i class="fa-solid fa-xmark text-xs"></i>
        </button>`;
    document.getElementById('valuesContainer').appendChild(row);
    idx++;
});
document.addEventListener('click', e => {
    if (e.target.closest('.remove-value')) {
        const rows = document.querySelectorAll('.value-row');
        if (rows.length > 1) e.target.closest('.value-row').remove();
    }
});
</script>
@endpush
