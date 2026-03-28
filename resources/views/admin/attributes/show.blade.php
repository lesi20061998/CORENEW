@extends('admin.layouts.app')
@section('title', $attribute->name)
@section('page-title', $attribute->name)
@section('page-actions')
    <a href="{{ route('admin.attributes.edit', $attribute) }}" class="btn-primary">
        <i class="fa-solid fa-pencil"></i> Chỉnh sửa
    </a>
@endsection

@section('content')
<div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

    <div class="card">
        <div class="card-header"><h3 class="text-sm font-bold text-gray-700">Thông tin</h3></div>
        <div class="card-body">
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between items-center py-2 border-b border-gray-50">
                    <dt class="text-gray-500">Tên</dt>
                    <dd class="font-semibold text-gray-800">{{ $attribute->name }}</dd>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-50">
                    <dt class="text-gray-500">Slug</dt>
                    <dd><code class="text-xs bg-gray-100 px-2 py-0.5 rounded-lg">{{ $attribute->slug }}</code></dd>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-50">
                    <dt class="text-gray-500">Loại</dt>
                    <dd><span class="badge-blue">{{ ucfirst($attribute->type) }}</span></dd>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-50">
                    <dt class="text-gray-500">Có thể lọc</dt>
                    <dd><span class="{{ $attribute->is_filterable ? 'badge-green' : 'badge-gray' }}">{{ $attribute->is_filterable ? 'Có' : 'Không' }}</span></dd>
                </div>
                <div class="flex justify-between items-center py-2">
                    <dt class="text-gray-500">Thứ tự</dt>
                    <dd class="text-gray-800 font-medium">{{ $attribute->sort_order }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <div class="xl:col-span-2 card overflow-hidden">
        <div class="card-header flex items-center justify-between">
            <h3 class="text-sm font-bold text-gray-700">Giá trị ({{ $attribute->values->count() }})</h3>
            <button onclick="document.getElementById('addValueModal').classList.remove('hidden')"
                class="inline-flex items-center gap-1.5 text-xs font-semibold text-blue-600 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition">
                <i class="fa-solid fa-plus"></i> Thêm giá trị
            </button>
        </div>
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50/80 border-b border-gray-100">
                    <th class="tbl-th">Giá trị</th>
                    <th class="tbl-th">Slug</th>
                    <th class="tbl-th">Màu sắc</th>
                    <th class="tbl-th w-16"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($attribute->values as $value)
                <tr class="hover:bg-gray-50/60 transition-colors">
                    <td class="tbl-td font-semibold text-gray-800">{{ $value->value }}</td>
                    <td class="tbl-td"><code class="text-xs bg-gray-100 px-2 py-0.5 rounded-lg">{{ $value->slug }}</code></td>
                    <td class="tbl-td">
                        @if($value->color_code)
                            <div class="flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full border-2 border-white shadow ring-1 ring-gray-200" style="background:{{ $value->color_code }}"></span>
                                <span class="text-xs text-gray-500 font-mono">{{ $value->color_code }}</span>
                            </div>
                        @else
                            <span class="text-gray-400">—</span>
                        @endif
                    </td>
                    <td class="tbl-td text-right">
                        <form action="{{ route('admin.attributes.values.destroy', [$attribute, $value]) }}" method="POST">
                            @csrf @method('DELETE')
                            <button onclick="return confirm('Xóa giá trị này?')"
                                class="action-btn hover:text-red-600 hover:bg-red-50">
                                <i class="fa-solid fa-trash text-xs"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-5 py-10 text-center text-gray-400 text-sm">Chưa có giá trị nào.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Modal thêm giá trị --}}
<div id="addValueModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4">
        <form action="{{ route('admin.attributes.values.store', $attribute) }}" method="POST">
            @csrf
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <h3 class="font-bold text-gray-800">Thêm giá trị mới</h3>
                <button type="button" onclick="document.getElementById('addValueModal').classList.add('hidden')"
                    class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="px-6 py-5 space-y-4">
                <div>
                    <label class="form-label">Tên giá trị <span class="text-red-500">*</span></label>
                    <input type="text" name="value" required placeholder="Ví dụ: Đỏ, XL, Cotton..."
                        class="form-input">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Màu sắc (tùy chọn)</label>
                        <input type="color" name="color_code"
                            class="w-full h-10 rounded-xl border border-gray-200 cursor-pointer p-1">
                    </div>
                    <div>
                        <label class="form-label">Thứ tự</label>
                        <input type="number" name="sort_order" value="{{ $attribute->values->count() }}" min="0"
                            class="form-input">
                    </div>
                </div>
            </div>
            <div class="flex gap-3 px-6 py-4 border-t border-gray-100">
                <button type="button" onclick="document.getElementById('addValueModal').classList.add('hidden')"
                    class="flex-1 btn-secondary justify-center py-2.5">Hủy</button>
                <button type="submit" class="flex-1 btn-primary justify-center py-2.5">
                    <i class="fa-solid fa-plus"></i> Thêm
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
