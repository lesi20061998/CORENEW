@extends('admin.layouts.app')

@section('title', 'Widget Management')
@section('page-title', 'Widget Builder')
@section('page-subtitle', 'Kéo & thả để xây dựng bố cục trang web hiện đại')

@push('styles')
    <!-- Sortable JS and Custom Style Inclusion -->
    <link rel="stylesheet" href="{{ asset('assets/admin/widget.css') }}?v={{ time() }}">
    <style>
        /* Small inline tweaks to fix layout within the existing admin app wrapper */
        .area-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
            gap: 1.5rem;
        }

        .wb-wrap {
            display: flex;
            gap: 2rem;
            min-height: calc(100vh - 180px);
            align-items: flex-start;
        }

        .wb-left {
            width: 320px;
            flex-shrink: 0;
            position: sticky;
            top: 2rem;
            background: white;
            border-radius: 14px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            max-height: calc(100vh - 120px);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .wb-right {
            flex: 1;
            min-width: 0;
        }

        /* Collapsible Transition */
        .area-body {
            max-height: 2000px;
            overflow: visible;
            transition: all 0.3s ease-in-out;
        }

        .area-body.collapsed {
            max-height: 0;
            padding-top: 0;
            padding-bottom: 0;
            overflow: hidden;
            border: none;
        }

        /* ── Modern Slide-over Drawer (wm-canvas) ── */
        .wm-overlay {
            position: fixed;
            inset: 0;
            z-index: 9999;
            visibility: hidden;
            transition: all 0.3s;
        }
        .wm-overlay.open {
            visibility: visible;
        }
        .wm-backdrop {
            position: absolute;
            inset: 0;
            background: rgba(15, 23, 42, 0.4);
            backdrop-filter: blur(8px);
            opacity: 0;
            transition: opacity 0.4s ease-out;
        }
        .wm-overlay.open .wm-backdrop {
            opacity: 1;
        }
        .wm-box {
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            width: 100%;
            max-width: 100%;
            background: white;
            box-shadow: -20px 0 50px -10px rgba(0,0,0,0.1);
            transform: translateX(100%);
            transition: transform 0.5s cubic-bezier(0.16, 1, 0.3, 1);
            display: flex;
            flex-direction: column;
        }
        .wm-overlay.open .wm-box {
            transform: translateX(0);
        }

        .wm-head {
            padding: 2rem 2.5rem;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            gap: 1.5rem;
            background: linear-gradient(to right, #ffffff, #fcfcfc);
        }

        .wm-body {
            flex: 1;
            overflow-y: auto;
            padding: 0;
            background: #f8fafc/50;
        }

        .wm-foot {
            padding: 1.5rem 2.5rem;
            background: white;
            border-top: 1px solid #f1f5f9;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 -10px 30px -15px rgba(0,0,0,0.05);
        }

        /* ── Form Styling ── */
        .form-label-premium {
            font-size: 11px;
            font-weight: 900;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 0.75rem;
            display: block;
            padding-left: 0.25rem;
        }
    </style>
@endpush

@section('content')
    <div class="wb-wrap">

        {{-- 1. LEFT PANEL: Available Widgets --}}
        <aside class="wb-left">
            <div class="p-4 bg-white border-bottom">
                <div class="flex items-center gap-2 mb-3">
                    <i class="fa-solid fa-shapes text-blue-600 text-lg"></i>
                    <h5 class="text-sm font-black text-slate-800 uppercase tracking-wider">Tiện ích sẵn có</h5>
                </div>
                <div class="relative">
                    <i
                        class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs text-xs"></i>
                    <input type="text" id="widget-search"
                        class="w-full bg-slate-50 border border-slate-200 rounded-lg pl-8 pr-3 py-2 text-xs font-medium outline-none focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 transition-all"
                        placeholder="Tìm kiếm widget...">
                </div>
            </div>

            <div class="flex-1 overflow-y-auto px-4 py-4 custom-scroll" id="available-widgets-list">
                @foreach($types as $typeKey => $type)
                    <div class="available-widget-item" data-type="{{ $typeKey }}" data-label="{{ $type['label'] }}">
                        <div class="widget-icon-box">
                            <i class="{{ $type['icon'] ?? 'fa-solid fa-layer-group' }} text-sm"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-[12px] font-black text-slate-800 truncate">{{ $type['label'] }}</div>
                            <div class="text-[10px] text-slate-400 font-medium truncate">{{ $type['description'] }}</div>
                        </div>
                        <i class="fa-solid fa-grip-vertical text-slate-300 text-[10px] handle cursor-grab"></i>
                    </div>
                @endforeach
            </div>

            <div class="p-4 bg-slate-50 border-t text-center">
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-relaxed">
                    Kéo & thả widget vào<br>khu vực bạn muốn hiển thị
                </p>
            </div>
        </aside>

        {{-- 2. RIGHT PANEL: Widget Areas --}}
        <main class="wb-right">
            <div class="area-grid">
                @foreach($widgetsByArea as $areaKey => $areaData)
                    <div class="area-card widget-card shadow-sm" data-area="{{ $areaKey }}">
                        {{-- Area Header (Toggle) --}}
                        <div class="area-header">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-slate-600">
                                    <i class="fa-solid {{ $areaData['area']['icon'] ?? 'fa-table-cells-large' }} text-xs"></i>
                                </div>
                                <div>
                                    <div class="text-sm font-black text-slate-800 tracking-tight">
                                        {{ $areaData['area']['label'] }}</div>
                                    <div class="text-[10px] text-slate-400 font-medium tracking-tight">
                                        {{ $areaData['area']['description'] ?? 'Quản lý widget trong khu vực này' }}</div>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <span
                                    class="text-[9px] font-black text-slate-300 bg-slate-50 px-2 py-0.5 rounded-full uppercase tracking-tighter">
                                    {{ count($areaData['widgets']) }} items
                                </span>
                                <i
                                    class="fa-solid fa-chevron-down text-slate-300 text-xs transition-transform collapse-icon"></i>
                            </div>
                        </div>

                        {{-- Area Drop Zone --}}
                        <div class="area-body widget-drop-zone transition-all pb-6" data-area="{{ $areaKey }}">
                            {{-- Empty State --}}
                            <div class="empty-area-placeholder @if(count($areaData['widgets']) > 0) hidden @endif">
                                <i class="fa-solid fa-plus-circle me-2 opacity-50"></i> Thả Widget vào đây
                            </div>

                            @foreach($areaData['widgets'] as $widget)
                                <div class="placed-widget-item widget-card group" data-id="{{ $widget->id }}"
                                    data-type="{{ $widget->type }}">

                                    <i class="fa-solid fa-grip-vertical widget-handle"></i>

                                    <div
                                        class="w-7 h-7 rounded bg-blue-50 text-blue-500 flex items-center justify-center text-xs shrink-0">
                                        <i class="{{ $types[$widget->type]['icon'] ?? 'fa-solid fa-layer-group' }}"></i>
                                    </div>

                                    <div class="flex-1 min-w-0">
                                        <div
                                            class="text-[11px] text-blue-600 font-black uppercase tracking-widest mb-0.5 opacity-60">
                                            {{ $types[$widget->type]['label'] ?? $widget->type }}</div>
                                        <div class="text-[12px] font-bold text-slate-800 truncate leading-tight">{{ $widget->name }}
                                        </div>
                                    </div>

                                    <div class="widget-actions opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button class="action-btn @if($widget->is_active) text-emerald-500 @endif" title="Bật/Tắt"
                                            onclick="toggleWidget({{ $widget->id }}, this)">
                                            <i class="fa-solid fa-power-off"></i>
                                        </button>
                                        <button class="action-btn" title="Cấu hình" onclick="openEditModal({{ $widget->id }})">
                                            <i class="fa-solid fa-sliders"></i>
                                        </button>
                                        <button class="action-btn" title="Nhân bản" onclick="cloneWidget({{ $widget->id }})">
                                            <i class="fa-regular fa-clone"></i>
                                        </button>
                                        <form action="{{ route('admin.widgets.destroy', $widget) }}" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="action-btn btn-delete"
                                                onclick="return confirm('Xóa Widget này?')" title="Xóa">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </main>
    </div>

    {{-- ── Premium Config Drawer (Slide-over) ── --}}
    <div class="wm-overlay" id="wm-overlay">
        <div class="wm-backdrop" onclick="closeModal()"></div>
        <div class="wm-box">
            {{-- Drawer Header --}}
            <div class="wm-head">
                <div id="wm-modal-icon" class="w-14 h-14 rounded-2xl bg-blue-600 flex items-center justify-center text-white text-2xl shadow-xl shadow-blue-500/20 border border-blue-400">
                    <i class="fa-solid fa-layer-group"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-2xl font-black text-slate-900 tracking-tight leading-none mb-1.5" id="wm-modal-title">Widget Settings</h3>
                    <p id="wm-modal-subtitle" class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Hiệu chỉnh tham số và hiển thị</p>
                </div>
                <button class="w-12 h-12 rounded-xl bg-slate-50 text-slate-400 hover:bg-rose-50 hover:text-rose-600 transition-all flex items-center justify-center text-xl group" onclick="closeModal()">
                    <i class="fa-solid fa-xmark transition-transform group-hover:rotate-90"></i>
                </button>
            </div>

            {{-- Drawer Body --}}
            <div class="wm-body custom-scroll" id="wm-body"></div>

            {{-- Drawer Footer --}}
            <div class="wm-foot">
                <div id="wm-delete-btn-wrap" style="display:none;">
                    <button class="px-6 py-4 rounded-xl border border-rose-100 text-rose-500 font-black text-[11px] uppercase tracking-widest hover:bg-rose-600 hover:text-white hover:border-rose-600 transition-all" onclick="deleteFromModal()">
                        <i class="fa-solid fa-trash-can me-2"></i> Xóa Widget
                    </button>
                </div>
                <div class="flex items-center gap-4 ml-auto">
                    <button class="px-8 py-4 rounded-xl bg-slate-100 text-slate-600 font-black text-[11px] uppercase tracking-widest transition-all hover:bg-slate-200" onclick="closeModal()">
                        Hủy bỏ
                    </button>
                    <button class="px-10 py-4 rounded-xl bg-blue-600 text-white font-black text-[11px] uppercase tracking-widest shadow-xl shadow-blue-600/30 hover:bg-blue-700 hover:-translate-y-1 transition-all active:translate-y-0" id="wm-save" onclick="submitModal()">
                        <i class="fa-solid fa-circle-check me-2"></i> Lưu thay đổi
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @php
        $areasJson = [];
        foreach($widgetsByArea as $k => $v) $areasJson[$k] = $v['area']['label'];
        $categoriesJson = isset($categories) ? $categories->map(fn($c) => ['id' => $c->id, 'name' => $c->name])->values() : [];
    @endphp

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>

    <script>
        // Global Config for External JS
        window.REORDER_URL = '{{ route('admin.widgets.reorder') }}';
        window.STORE_URL = '{{ route('admin.widgets.store') }}';
        window.TOGGLE_URL = '{{ url("/admin/widgets/{id}/toggle") }}';
        window.DATA_URL = '{{ url("/admin/widgets/{id}/data") }}';
        window.CSRF_TOKEN = '{{ csrf_token() }}';
        const TYPES = @json($types);
        const AREAS = @json($areasJson);
        const CATEGORIES = @json($categoriesJson);

        // Modal Global Vars
        let modalMode = null;
        let modalWidgetId = null;
        let modalWidgetArea = 'homepage';

        function escHtml(str) {
            if (!str) return '';
            const div = document.createElement('div');
            div.textContent = str;
            return div.innerHTML;
        }
    </script>

    <script src="{{ asset('assets/admin/widget.js') }}?v={{ time() }}"></script>

    <script>
        // Integration logic for Modal (kept from existing version)
        function openModal() {
            document.getElementById('wm-overlay').classList.add('open');
            document.body.style.overflow = 'hidden';
        }
        function closeModal() {
            document.getElementById('wm-overlay').classList.remove('open');
            document.body.style.overflow = '';
        }

        function openEditModal(id) {
            modalMode = 'edit'; modalWidgetId = id;
            document.getElementById('wm-delete-btn-wrap').style.display = 'block';
            document.getElementById('wm-body').innerHTML = '<div class="text-center py-20 flex flex-col items-center gap-4"><div class="w-12 h-12 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"></div><div class="text-xs font-black text-slate-400 uppercase tracking-widest">Đang tải cấu hình...</div></div>';
            openModal();
            fetch(DATA_URL.replace('{id}', id)).then(r => r.json()).then(data => {
                const t = TYPES[data.type];
                modalWidgetArea = data.area;
                document.getElementById('wm-modal-title').textContent = data.name;
                document.getElementById('wm-modal-icon').innerHTML = `<i class="${t?.icon ?? 'fa-solid fa-puzzle-piece'}"></i>`;
                renderModalForm(data);
            });
        }

        function toggleWidget(id, btn) {
            fetch(TOGGLE_URL.replace('{id}', id), {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': window.CSRF_TOKEN }
            })
                .then(r => r.json())
                .then(d => {
                    btn.classList.toggle('text-emerald-500', d.is_active);
                    if (window.adminToast) window.adminToast('Visibility', 'Widget state updated', 'success');
                });
        }

        function cloneWidget(id) {
            if (!confirm('Nhân bản widget này?')) return;
            fetch('{{ url("/admin/widgets") }}/' + id + '/clone', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': window.CSRF_TOKEN }
            }).then(() => location.reload());
        }

        function renderModalForm(data) {
            const areaOpts = Object.entries(AREAS).map(([k, v]) => `<option value="${k}" ${data.area === k ? 'selected' : ''}>${v}</option>`).join('');

            document.getElementById('wm-body').innerHTML = `
                <div class="flex h-full min-h-0">
                    <div class="w-1/2 p-10 bg-white overflow-y-auto border-r border-slate-100" id="wm-main-fields">
                        <div class="space-y-8 max-w-4xl mx-auto">
                            <div class="bg-slate-50/50 p-8 rounded-3xl border border-slate-100">
                                 <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-4">Tên hiển thị nội bộ</label>
                                 <input type="text" id="wm-name" class="w-full bg-white border border-slate-200 rounded-2xl px-5 py-4 text-sm font-bold text-slate-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all shadow-sm" value="${escHtml(data.name || '')}" placeholder="Ví dụ: Banner Trang chủ">
                            </div>
                            <div id="wm-config-main" class="space-y-6"></div>
                        </div>
                    </div>
                    <aside class="w-1/2 p-10 bg-slate-50/30 overflow-y-auto" id="wm-config-side">
                             <div id="wm-config-common" class="space-y-6"></div>
                             <div class="bg-white p-6 rounded-3xl border border-slate-150">
                                 <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-3">Vị trí hiển thị (Area)</label>
                                 <select id="wm-area" class="w-full bg-slate-50 border-none outline-none font-bold text-xs p-3 rounded-lg">
                                     ${areaOpts}
                                 </select>
                             </div>
                        </div>
                    </aside>
                </div>
            `;
            renderModalConfig(data.type, data.config ?? {});
        }

        function renderModalConfig(type, config) {
            const mainWrap = document.getElementById('wm-config-main');
            const sideWrap = document.getElementById('wm-config-common');
            if (!mainWrap || !sideWrap) return;

            const t = TYPES[type];
            if (!t?.fields?.length) return;

            let target = mainWrap;
            t.fields.forEach(f => {
                if (f.type === 'tab_start' && f.key.includes('common')) {
                    target = sideWrap;
                    return;
                }
                if (f.type === 'tab_end') {
                    target = mainWrap;
                    return;
                }
                // Add field rendering logic here or use buildField from previous version
                // (I'll keep it abbreviated for brevity, assuming standard field types)
                target.appendChild(buildField(f, config[f.key] ?? f.default ?? null, 'config'));
            });
        }

        function buildField(field, value, prefix) {
            const wrap = document.createElement('div');
            wrap.className = 'bg-white p-6 rounded-2xl border border-slate-150 shadow-sm transition-all hover:shadow-md';
            const name = `${prefix}[${field.key}]`;

            let input = '';
            if (field.type === 'text' || field.type === 'image') {
                if (field.type === 'image') {
                    input = `
                        <div class="flex gap-4">
                            <div class="w-16 h-16 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center flex-shrink-0 overflow-hidden shadow-inner">
                                ${value ? `<img src="${value}" class="w-full h-full object-cover">` : `<i class="fa-solid fa-image text-slate-200 text-xl"></i>`}
                            </div>
                            <div class="flex-1 space-y-2">
                                 <input type="text" name="${name}" class="w-full bg-slate-50 border border-slate-150 rounded-lg px-3 py-2.5 text-xs font-bold text-slate-700 outline-none focus:bg-white transition-all" value="${escHtml(value ?? '')}" placeholder="URL ảnh hoặc chọn...">
                                 <button type="button" class="text-[10px] font-black text-blue-600 uppercase tracking-widest hover:text-blue-800" onclick="openMediaPicker(this)">
                                     <i class="fa-solid fa-photo-film me-1"></i> Mở thư viện Media
                                 </button>
                            </div>
                        </div>
                    `;
                } else {
                    input = `<input type="text" name="${name}" class="w-full bg-slate-50 border border-slate-150 rounded-lg px-4 py-3 text-sm font-bold text-slate-900 border-none outline-none focus:bg-white transition-all" value="${escHtml(value ?? '')}" placeholder="${field.placeholder ?? ''}">`;
                }
            } else if (field.type === 'number') {
                input = `<input type="number" name="${name}" class="w-full bg-slate-50 border border-slate-150 rounded-lg px-4 py-2 text-sm font-black text-slate-900 outline-none focus:bg-white transition-all" value="${value ?? ''}">`;
            } else if (field.type === 'color') {
                input = `<div class="flex items-center gap-3">
                    <input type="color" name="${name}" class="w-10 h-10 rounded-lg border-none p-0 cursor-pointer" value="${value ?? '#ffffff'}">
                    <input type="text" class="flex-1 bg-slate-50 rounded-lg px-3 py-2 text-xs font-black text-slate-700 uppercase" value="${value ?? '#ffffff'}" oninput="this.previousElementSibling.value=this.value">
                </div>`;
            } else if (field.type === 'select') {
                const os = Object.entries(field.options ?? {}).map(([k, v]) => `<option value="${k}" ${value == k ? 'selected' : ''}>${v}</option>`).join('');
                input = `<select name="${name}" class="w-full bg-slate-50 border-none outline-none font-bold text-xs p-3 rounded-lg">${os}</select>`;
            } else if (field.type === 'category_select') {
                const categories = CATEGORIES || [];
                if (field.multiple === false || field.single === true) {
                    const os = categories.map(c => `<option value="${c.id}" ${value == c.id ? 'selected' : ''}>${escHtml(c.name)}</option>`).join('');
                    input = `<select name="${name}" class="w-full bg-slate-50 border-none outline-none font-bold text-xs p-3 rounded-lg"><option value="">Tất cả danh mục</option>${os}</select>`;
                } else {
                    const selectedIds = Array.isArray(value) ? value.map(String) : (value ? [String(value)] : []);
                    input = `<div class="p-4 bg-slate-50 rounded-xl space-y-2 max-h-48 overflow-y-auto custom-scroll">
                        ${categories.map(c => `
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="checkbox" name="${name}[]" value="${c.id}" ${selectedIds.includes(String(c.id)) ? 'checked' : ''} class="w-4 h-4 rounded text-blue-600 border-slate-200">
                                <span class="text-xs font-bold text-slate-700 group-hover:text-blue-600 transition-colors uppercase tracking-tight">${escHtml(c.name)}</span>
                            </label>
                        `).join('')}
                    </div>`;
                }
            } else if (field.type === 'box_model') {
                const m = value ?? {};
                input = `<div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest pl-1">Margin TB/LR</label>
                        <div class="flex gap-2">
                            <input type="number" name="${name}[margin_top]" class="w-full bg-slate-50 rounded-lg p-2 text-xs font-black" value="${m.margin_top ?? 0}" title="Top">
                            <input type="number" name="${name}[margin_bottom]" class="w-full bg-slate-50 rounded-lg p-2 text-xs font-black" value="${m.margin_bottom ?? 0}" title="Bottom">
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest pl-1">Padding TB/LR</label>
                        <div class="flex gap-2">
                            <input type="number" name="${name}[padding_top]" class="w-full bg-slate-50 rounded-lg p-2 text-xs font-black" value="${m.padding_top ?? 0}" title="Top">
                            <input type="number" name="${name}[padding_bottom]" class="w-full bg-slate-50 rounded-lg p-2 text-xs font-black" value="${m.padding_bottom ?? 0}" title="Bottom">
                        </div>
                    </div>
                </div>`;
            }

            wrap.innerHTML = `<label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-3 pl-1">${field.label}</label>${input}`;
            return wrap;
        }

        function submitModal() {
            const fd = new FormData();
            fd.append('_token', window.CSRF_TOKEN);

            fd.append('name', document.getElementById('wm-name')?.value || 'Unnamed');
            fd.append('is_active', document.getElementById('wm-active')?.checked ? 1 : 0);
            fd.append('area', document.getElementById('wm-area')?.value || modalWidgetArea);

            if (modalMode === 'create') {
                fd.append('type', document.getElementById('wm-type')?.value);
            }

            document.querySelectorAll('[name^="config"]').forEach(el => {
                if (el.type === 'checkbox') {
                    fd.append(el.name, el.checked ? 1 : 0);
                } else if (el.type === 'radio') {
                    if (el.checked) fd.append(el.name, el.value);
                } else {
                    fd.append(el.name, el.value);
                }
            });

            const url = modalMode === 'create' ? window.STORE_URL : '{{ url("/admin/widgets") }}/' + modalWidgetId;
            if (modalMode === 'edit') fd.append('_method', 'PUT');

            const btn = document.getElementById('wm-save');
            btn.disabled = true;
            const originalHtml = btn.innerHTML;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> Đang lưu...';

            fetch(url, {
                method: 'POST',
                body: fd,
                headers: { 'Accept': 'application/json' }
            })
                .then(r => r.json())
                .then(data => {
                    if (data.success) location.reload();
                    else alert('Lỗi: ' + (data.message || 'Không thể lưu.'));
                })
                .catch(err => {
                    alert('Lỗi kết nối: ' + err.message);
                })
                .finally(() => {
                    btn.disabled = false;
                    btn.innerHTML = originalHtml;
                });
        }

        function deleteFromModal() {
            if (!confirm('Xóa Widget này vĩnh viễn?')) return;
            fetch('{{ url("/admin/widgets") }}/' + modalWidgetId, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': window.CSRF_TOKEN },
                body: (() => { const fd = new FormData(); fd.append('_method', 'DELETE'); return fd; })()
            })
                .then(() => location.reload());
        }
    </script>
@endpush