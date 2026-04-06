@extends('admin.layouts.app')
@section('title', 'Cấu hình TOC (Mục lục)')
@section('page-title', 'Table of Contents')
@section('page-subtitle', 'Quản lý hiển thị mục lục tự động cho bài viết')

@section('page-actions')
<a href="{{ route('admin.settings.index') }}" class="btn btn-secondary">
    <i class="fa-solid fa-arrow-left text-xs"></i> Quay lại
</a>
@endsection

@section('content')
<div class="max-w-4xl">
    <form action="{{ route('admin.settings.group.update', 'toc') }}" method="POST">
        @csrf @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            {{-- Left column: General Settings --}}
            <div class="md:col-span-2 space-y-6">
                <div class="card overflow-hidden">
                    <div class="card-header bg-gray-50 border-b flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-blue-600 flex items-center justify-center text-white shadow-lg shadow-blue-200">
                            <i class="fa-solid fa-list-ul"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-black text-gray-900 tracking-tight">Cài đặt chung</h3>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-0.5">TOC Behaviour & Context</p>
                        </div>
                    </div>
                    <div class="card-body p-8 space-y-8">
                        {{-- Enable Toggle --}}
                        <div class="flex items-center justify-between p-5 bg-blue-50/30 rounded-2xl border border-blue-100/50">
                            <div class="flex-1">
                                <h4 class="text-sm font-black text-blue-900 mb-1 leading-none">Bật mục lục tự động</h4>
                                <p class="text-xs text-blue-600/70 font-medium tracking-tight">Tự động phát hiện và chèn mục lục khi đủ điều kiện</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="hidden" name="settings[toc_enabled]" value="0">
                                <input type="checkbox" name="settings[toc_enabled]" value="1" 
                                    {{ ($settingsMap['toc_enabled'] ?? '1') == '1' ? 'checked' : '' }}
                                    class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>

                        {{-- Title --}}
                        <div>
                            <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest mb-3 block">Tiêu đề mục lục</label>
                            <input type="text" name="settings[toc_title]" value="{{ $settingsMap['toc_title'] ?? 'Mục lục' }}" 
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-5 py-4 text-sm font-bold text-gray-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all"
                                placeholder="VD: Nội dung chính bài viết">
                        </div>

                        {{-- Min Headings --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                            <div>
                                <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest mb-3 block">Số lượng Heading tối thiểu</label>
                                <div class="relative">
                                    <input type="number" name="settings[toc_min_headings]" value="{{ $settingsMap['toc_min_headings'] ?? '3' }}" 
                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-5 py-4 text-sm font-black text-gray-900 outline-none focus:bg-white transition-all">
                                    <span class="absolute right-5 top-1/2 -translate-y-1/2 text-[10px] font-bold text-gray-400">Headings</span>
                                </div>
                                <p class="text-[10px] text-gray-400 mt-2 italic font-medium">Bố cục sẽ không hiện nếu số heading ít hơn số này.</p>
                            </div>
                        </div>

                        {{-- Heading Selection (Visual) --}}
                        <div>
                            <label class="text-[11px] font-black text-gray-400 uppercase tracking-widest mb-4 block">Chọn các cấp độ Heading (H1 - H6)</label>
                            <div class="flex flex-wrap gap-3">
                                @php
                                    $selectedTags = explode(',', $settingsMap['toc_heading_tags'] ?? 'h2,h3');
                                @endphp
                                @foreach(['h1', 'h2', 'h3', 'h4', 'h5' , 'h6'] as $tag)
                                    <label class="flex-1 min-w-[80px] cursor-pointer group">
                                        <input type="checkbox" class="sr-only peer toc-tag-checkbox" value="{{ $tag }}" 
                                            {{ in_array($tag, $selectedTags) ? 'checked' : '' }}>
                                        <div class="h-16 rounded-xl border-2 border-slate-100 bg-white flex flex-col items-center justify-center transition-all peer-checked:border-blue-600 peer-checked:bg-blue-50 group-hover:bg-slate-50">
                                            <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest group-hover:text-slate-400 transition-colors peer-checked:text-blue-600">Level</span>
                                            <span class="text-base font-black text-slate-900 peer-checked:text-blue-700">{{ strtoupper($tag) }}</span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            <input type="hidden" name="settings[toc_heading_tags]" id="toc-heading-tags-input" value="{{ $settingsMap['toc_heading_tags'] ?? 'h2,h3' }}">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right column: Help/Tips --}}
            <div class="space-y-6">
                <div class="bg-gradient-to-br from-slate-900 to-slate-800 rounded-3xl p-8 text-white shadow-xl shadow-slate-200">
                    <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center mb-6 text-xl text-blue-400 border border-white/10">
                        <i class="fa-solid fa-lightbulb"></i>
                    </div>
                    <h4 class="text-lg font-black tracking-tight mb-4 leading-tight">Mẹo tối ưu TOC</h4>
                    <ul class="space-y-4 text-xs font-medium text-slate-400 leading-relaxed">
                        <li class="flex gap-3">
                            <i class="fa-solid fa-circle-check text-emerald-400 flex-shrink-0 mt-0.5"></i>
                            <span>Nên sử dụng <b>H2 và H3</b> để mục lục gọn gàng và dễ đọc nhất trên mobile.</span>
                        </li>
                        <li class="flex gap-3">
                            <i class="fa-solid fa-circle-check text-emerald-400 flex-shrink-0 mt-0.5"></i>
                            <span>Bạn có thể bật/tắt mục lục cho từng bài viết cụ thể trong trang soạn thảo.</span>
                        </li>
                    </ul>
                </div>

                <div class="card p-6 bg-slate-50 border border-dashed border-slate-200 rounded-3xl">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Thông tin hệ thống</p>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-8 h-8 rounded-lg bg-white flex items-center justify-center text-slate-400 border">
                            <i class="fa-solid fa-code text-xs"></i>
                        </div>
                        <div class="text-xs font-bold text-slate-600">Version: 1.0.0 Stable</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer Actions --}}
        <div class="mt-12 flex items-center gap-4 bg-white p-6 rounded-3xl border shadow-sm sticky bottom-8">
            <button type="submit" class="px-10 py-5 bg-blue-600 text-white rounded-2xl font-black text-sm uppercase tracking-widest shadow-xl shadow-blue-500/20 hover:bg-blue-700 hover:-translate-y-1 transition-all flex items-center gap-3">
                <i class="fa-solid fa-circle-check"></i> Lưu cài đặt TOC
            </button>
            <a href="{{ route('admin.settings.index') }}" class="px-8 py-5 text-slate-400 font-black text-sm uppercase tracking-widest hover:text-slate-600 transition-colors">
                Hủy bỏ
            </a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const checkboxes = document.querySelectorAll('.toc-tag-checkbox');
    const hiddenInput = document.getElementById('toc-heading-tags-input');

    function updateTags() {
        const selected = Array.from(checkboxes)
            .filter(c => c.checked)
            .map(c => c.value);
        hiddenInput.value = selected.join(',');
    }

    checkboxes.forEach(c => {
        c.addEventListener('change', updateTags);
    });
});
</script>
@endpush
