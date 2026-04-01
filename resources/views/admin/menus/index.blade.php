@extends('admin.layouts.app')
@section('title', 'Quản lý Menu')
@section('page-title', 'Menu Hệ thống')
@section('page-subtitle', 'Tổ chức các liên kết điều hướng cho Header, Footer và các khu vực khác.')

@section('page-actions')
    <button onclick="document.getElementById('addMenuModal').showModal()" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i> Tạo Menu mới
    </button>
@endsection

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($menus as $menu)
    <div class="card overflow-hidden">
        <div class="p-5">
            <div class="flex items-start justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                        <i class="fa-solid fa-bars-staggered"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 leading-tight">{{ $menu->name }}</h3>
                        <p class="text-xs text-slate-400 mt-1">Area: <span class="font-mono">{{ $menu->area }}</span></p>
                    </div>
                </div>
                <div class="flex items-center gap-1">
                    <a href="{{ route('admin.menus.edit', $menu->id) }}" class="act-btn edit" title="Chỉnh sửa cấu trúc">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </a>
                    <form action="{{ route('admin.widgets.destroy', $menu->id) }}" method="POST" onsubmit="return confirm('Xóa menu này?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="act-btn del"><i class="fa-solid fa-trash-can"></i></button>
                    </form>
                </div>
            </div>
            
            @php 
                $config = json_decode($menu->config ?? '[]', true);
                $count = count($config['items'] ?? []);
            @endphp
            <div class="mt-4 flex items-center justify-between text-sm">
                <span class="text-slate-500">{{ $count }} liên kết</span>
                <span class="badge {{ $menu->is_active ? 'badge-green' : 'badge-gray' }}">
                    {{ $menu->is_active ? 'Đang bật' : 'Đang tắt' }}
                </span>
            </div>
        </div>
        <div class="bg-slate-50 px-5 py-3 border-top flex justify-end">
            <a href="{{ route('admin.menus.edit', $menu->id) }}" class="text-xs font-bold text-blue-600 hover:underline">Thiết lập Menu &rarr;</a>
        </div>
    </div>
    @empty
    <div class="col-span-full border-2 border-dashed border-slate-200 rounded-2xl p-12 text-center">
        <div class="w-16 h-16 bg-slate-100 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fa-solid fa-list-ul text-2xl"></i>
        </div>
        <h3 class="text-slate-600 font-bold">Chưa có Menu nào</h3>
        <p class="text-slate-400 text-sm mt-1">Hãy tạo Menu đầu tiên để quản lý điều hướng trang web.</p>
        <button onclick="document.getElementById('addMenuModal').showModal()" class="btn btn-primary mt-6">Tạo ngay</button>
    </div>
    @endforelse
</div>

{{-- Modal Add Menu --}}
<dialog id="addMenuModal" class="p-0 rounded-2xl border-none shadow-2xl bg-white w-full max-w-md">
    <div class="p-6">
        <h3 class="text-lg font-bold mb-4">Tạo Menu mới</h3>
        <form action="{{ route('admin.menus.store') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="form-label">Tên định danh</label>
                    <input type="text" name="name" class="form-input" placeholder="Ví dụ: Menu Chính Header" required>
                </div>
                <div>
                    <label class="form-label">Vị trí (Area Key)</label>
                    <input type="text" name="area" class="form-input" placeholder="ví dụ: header, footer_1" required>
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="this.closest('dialog').close()" class="btn btn-secondary">Hủy</button>
                <button type="submit" class="btn btn-primary px-8">Lưu lại</button>
            </div>
        </form>
    </div>
</dialog>

@endsection

@push('styles')
<style>
    dialog::backdrop { background: rgba(15, 23, 42, 0.4); backdrop-filter: blur(4px); }
</style>
@endpush
