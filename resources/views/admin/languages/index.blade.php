@extends('admin.layouts.app')
@section('title', 'Ngôn ngữ')
@section('page-title', 'Quản lý ngôn ngữ')
@section('page-subtitle', 'Thêm ngôn ngữ để bật tính năng đa ngôn ngữ cho toàn bộ nội dung')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    {{-- Danh sách ngôn ngữ --}}
    <div class="lg:col-span-2 space-y-3">
        @forelse($languages as $lang)
        <div class="card p-4 flex items-center gap-4">
            <div class="text-xl w-10 text-center flex-shrink-0 text-gray-400">
                @if($lang->flag)
                    <img src="{{ $lang->flag }}" alt="{{ $lang->name }}" class="w-8 h-8 object-contain mx-auto rounded">
                @else
                    <i class="fa-solid fa-language"></i>
                @endif
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2">
                    <p class="text-sm font-semibold text-gray-800">{{ $lang->name }}</p>
                    <span class="text-xs text-gray-400">({{ $lang->native_name }})</span>
                    <span class="badge-gray text-[10px] font-mono">{{ $lang->code }}</span>
                    @if($lang->is_default)
                        <span class="badge-blue text-[10px]">Mặc định</span>
                    @endif
                    @if(!$lang->is_active)
                        <span class="badge-red text-[10px]">Tắt</span>
                    @endif
                </div>
            </div>
            <div class="flex items-center gap-2 flex-shrink-0">
                @if(!$lang->is_default)
                <form action="{{ route('admin.languages.set-default', $lang) }}" method="POST">
                    @csrf
                    <button type="submit" class="text-xs text-blue-600 hover:underline">Đặt mặc định</button>
                </form>
                @endif

                <button onclick="openEditModal({{ $lang->id }}, '{{ $lang->code }}', '{{ $lang->name }}', '{{ $lang->native_name }}', '{{ $lang->flag }}', {{ $lang->is_active ? 1 : 0 }}, {{ $lang->sort_order }})"
                        class="action-btn hover:bg-blue-50 hover:text-blue-600">
                    <i class="fa-solid fa-pen text-xs"></i>
                </button>

                @if(!$lang->is_default)
                <form action="{{ route('admin.languages.destroy', $lang) }}" method="POST"
                      onsubmit="return confirm('Xóa ngôn ngữ này? Toàn bộ bản dịch sẽ bị mất.')">
                    @csrf @method('DELETE')
                    <button type="submit" class="action-btn hover:bg-red-50 hover:text-red-500">
                        <i class="fa-solid fa-trash text-xs"></i>
                    </button>
                </form>
                @endif
            </div>
        </div>
        @empty
        <div class="card card-body text-center py-12">
            <i class="fa-solid fa-language text-3xl text-gray-200 mb-3"></i>
            <p class="text-sm text-gray-400">Chưa có ngôn ngữ nào. Thêm ngôn ngữ đầu tiên.</p>
        </div>
        @endforelse
    </div>

    {{-- Form thêm ngôn ngữ --}}
    <div>
        <div class="card">
            <div class="card-header">
                <p class="text-sm font-semibold text-gray-800">Thêm ngôn ngữ mới</p>
            </div>
            <form action="{{ route('admin.languages.store') }}" method="POST">
                @csrf
                <div class="card-body space-y-4">
                    <div>
                        <label class="form-label">Mã ngôn ngữ <span class="text-red-500">*</span></label>
                        <input type="text" name="code" value="{{ old('code') }}" placeholder="vi, en, zh, ja..."
                               class="form-input font-mono" maxlength="10" required>
                        <p class="text-xs text-gray-400 mt-1">ISO 639-1 (vd: vi, en, zh)</p>
                    </div>
                    <div>
                        <label class="form-label">Tên ngôn ngữ <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Tiếng Việt"
                               class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Tên bản địa <span class="text-red-500">*</span></label>
                        <input type="text" name="native_name" value="{{ old('native_name') }}" placeholder="Tiếng Việt"
                               class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Biểu tượng (Cờ)</label>
                        <div class="flex items-center gap-3">
                            <div id="add_flag_preview" class="w-12 h-12 border rounded-xl flex items-center justify-center bg-slate-50 flex-shrink-0 overflow-hidden">
                                <i class="fa-solid fa-image text-slate-200 text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <input type="hidden" name="flag" id="add_flag_input">
                                <button type="button" onclick="openMediaPicker('add_flag_input', (url) => { document.getElementById('add_flag_preview').innerHTML = `<img src='${url}' class='w-full h-full object-contain'>` })" 
                                        class="btn-secondary btn-sm w-full justify-center py-2.5">
                                    <i class="fa-solid fa-plus mr-1"></i> Chọn / Tải ảnh
                                </button>
                                <p class="text-[10px] text-gray-400 mt-1.5 leading-tight">PNG/SVG tỷ lệ 1:1 hoặc 4:3</p>
                            </div>
                        </div>
                    </div>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" checked
                               class="w-4 h-4 rounded border-gray-300 text-blue-600">
                        <span class="text-sm text-gray-700">Kích hoạt ngay</span>
                    </label>
                </div>
                <div class="px-6 py-4 border-t border-gray-100">
                    <button type="submit" class="btn-primary w-full justify-center">
                        <i class="fa-solid fa-plus text-xs"></i> Thêm ngôn ngữ
                    </button>
                </div>
            </form>
        </div>

        <div class="card mt-4 p-4 bg-blue-50 border-blue-100">
            <p class="text-xs font-semibold text-blue-700 mb-1"><i class="fa-solid fa-circle-info mr-1"></i> Lưu ý</p>
            <p class="text-xs text-blue-600 leading-relaxed">Khi có nhiều hơn 1 ngôn ngữ, các trang chỉnh sửa sản phẩm, bài viết, trang tĩnh sẽ hiển thị tab đa ngôn ngữ để nhập nội dung.</p>
        </div>
    </div>
</div>

{{-- Edit Modal --}}
<div id="editModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center" x-data>
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <p class="text-sm font-semibold text-gray-800">Chỉnh sửa ngôn ngữ</p>
            <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form id="editForm" method="POST">
            @csrf @method('PUT')
            <div class="p-6 space-y-4">
                <div>
                    <label class="form-label">Mã ngôn ngữ</label>
                    <input type="text" id="edit_code" class="form-input bg-gray-50 font-mono" readonly>
                </div>
                <div>
                    <label class="form-label">Tên ngôn ngữ</label>
                    <input type="text" name="name" id="edit_name" class="form-input" required>
                </div>
                <div>
                    <label class="form-label">Tên bản địa</label>
                    <input type="text" name="native_name" id="edit_native_name" class="form-input" required>
                </div>
                <div>
                    <label class="form-label">Biểu tượng hiện tại</label>
                    <div class="flex items-center gap-3">
                        <div id="edit_flag_preview" class="w-12 h-12 border rounded-xl flex items-center justify-center bg-slate-50 flex-shrink-0 overflow-hidden">
                            <!-- Preview image will be injected here -->
                        </div>
                        <div class="flex-1">
                            <input type="hidden" name="flag" id="edit_flag_input">
                            <button type="button" onclick="openMediaPicker('edit_flag_input', (url) => { document.getElementById('edit_flag_preview').innerHTML = `<img src='${url}' class='w-full h-full object-contain'>` })" 
                                    class="btn-secondary btn-sm w-full justify-center py-2.5">
                                <i class="fa-solid fa-arrows-rotate mr-1"></i> Thay đổi ảnh
                            </button>
                        </div>
                    </div>
                </div>
                <div>
                    <label class="form-label">Thứ tự</label>
                    <input type="number" name="sort_order" id="edit_sort_order" class="form-input" min="0">
                </div>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" id="edit_is_active" value="1"
                           class="w-4 h-4 rounded border-gray-300 text-blue-600">
                    <span class="text-sm text-gray-700">Kích hoạt</span>
                </label>
            </div>
            <div class="px-6 py-4 border-t border-gray-100 flex gap-3">
                <button type="submit" class="btn-primary flex-1 justify-center">Lưu</button>
                <button type="button" onclick="closeEditModal()" class="btn-secondary flex-1 justify-center">Hủy</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
const routes = @json($languages->pluck('id'));

function openEditModal(id, code, name, nativeName, flag, isActive, sortOrder) {
    document.getElementById('editForm').action = `/admin/settings/languages/${id}`;
    document.getElementById('edit_code').value = code;
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_native_name').value = nativeName;
    const previewDiv = document.getElementById('edit_flag_preview');
    const inputEl = document.getElementById('edit_flag_input');
    inputEl.value = flag && flag !== 'null' ? flag : '';
    if (flag && flag !== 'null') {
        previewDiv.innerHTML = `<img src="${flag}" class="w-full h-full object-contain">`;
    } else {
        previewDiv.innerHTML = `<i class="fa-solid fa-image text-slate-200 text-xl"></i>`;
    }
    document.getElementById('edit_sort_order').value = sortOrder;
    document.getElementById('edit_is_active').checked = isActive == 1;
    document.getElementById('editModal').classList.remove('hidden');
    document.getElementById('editModal').classList.add('flex');
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
    document.getElementById('editModal').classList.remove('flex');
}
</script>
@endpush
