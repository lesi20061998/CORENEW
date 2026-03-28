@extends('admin.layouts.app')
@section('title', 'Sửa thuộc tính')
@section('page-title', 'Sửa: ' . $attribute->name)

@section('content')
<form action="{{ route('admin.attributes.update', $attribute) }}" method="POST">
    @csrf @method('PUT')
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">
        <div class="xl:col-span-2">
            <div class="card">
                <div class="card-header"><h3 class="text-sm font-bold text-gray-700">Thông tin thuộc tính</h3></div>
                <div class="card-body space-y-4">
                    <div>
                        <label class="form-label">Tên thuộc tính <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $attribute->name) }}" required
                            class="form-input @error('name') ring-2 ring-red-300 border-red-400 @enderror">
                        @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Loại hiển thị</label>
                            <select name="type" class="form-select">
                                <option value="select"   {{ old('type', $attribute->type) === 'select'   ? 'selected' : '' }}>Danh sách chọn</option>
                                <option value="checkbox" {{ old('type', $attribute->type) === 'checkbox' ? 'selected' : '' }}>Checkbox</option>
                                <option value="radio"    {{ old('type', $attribute->type) === 'radio'    ? 'selected' : '' }}>Radio</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Thứ tự hiển thị</label>
                            <input type="number" name="sort_order" value="{{ old('sort_order', $attribute->sort_order) }}" min="0" class="form-input">
                        </div>
                    </div>
                    <label class="flex items-center gap-3 cursor-pointer p-3 rounded-xl border border-gray-100 hover:bg-gray-50 transition">
                        <input type="checkbox" name="is_filterable" value="1"
                            {{ old('is_filterable', $attribute->is_filterable) ? 'checked' : '' }}
                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 w-4 h-4">
                        <div>
                            <p class="text-sm font-medium text-gray-700">Hiển thị trong bộ lọc</p>
                            <p class="text-xs text-gray-400">Khách hàng có thể lọc theo thuộc tính này</p>
                        </div>
                    </label>
                </div>
            </div>
        </div>
        <div class="space-y-3">
            <button type="submit" class="w-full btn-primary justify-center py-2.5">
                <i class="fa-solid fa-floppy-disk"></i> Lưu thay đổi
            </button>
            <a href="{{ route('admin.attributes.show', $attribute) }}" class="w-full btn-secondary justify-center py-2.5">
                Hủy bỏ
            </a>
        </div>
    </div>
</form>
@endsection
