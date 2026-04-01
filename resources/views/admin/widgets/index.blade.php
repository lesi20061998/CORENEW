@extends('admin.layouts.app')
@section('title', 'Homepage Section Builder')
@section('page-title', 'Homepage Builder')
@section('page-subtitle', 'Thiết kế trang chủ chuyên nghiệp bằng cách kéo thả các Section')

@push('styles')
<style>
    /* ── Overall layout (Premium) ── */
    .wb-wrap { display: flex; gap: 24px; min-height: calc(100vh - 180px); background: #f8fafc; overflow: visible; }

    /* ── Section Picker (Left) ── */
    .wb-left { width: 300px; flex-shrink: 0; background: white; border-radius: 24px; border: 1px solid #e2e8f0; display: flex; flex-direction: column; position: sticky; top: 0; max-height: calc(100vh - 200px); box-shadow: 0 10px 30px rgba(0,0,0,0.03); }
    .wb-panel-head { padding: 20px; background: white; border-bottom: 2px solid #f1f5f9; font-size: 13px; font-weight: 800; color: #1e293b; text-transform: uppercase; letter-spacing: 0.1em; display: flex; align-items: center; border-radius: 24px 24px 0 0; }
    .wb-left-body { overflow-y: auto; flex: 1; padding: 12px; scrollbar-width: none; }
    .wb-type-item { padding: 16px; margin-bottom: 10px; background: #fff; border: 1.5px solid #f1f5f9; border-radius: 16px; cursor: grab; transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); display: flex; align-items: center; gap: 14px; }
    .wb-type-item:hover { transform: translateX(5px); border-color: #3b82f6; background: #eff6ff; box-shadow: 0 10px 20px rgba(59,130,246,0.1); }
    .wb-type-icon { width: 40px; height: 40px; border-radius: 12px; background: #f8fafc; display: flex; align-items: center; justify-content: center; font-size: 18px; color: #3b82f6; }
    .wb-type-info { flex: 1; }
    .wb-type-name { font-size: 13px; font-weight: 800; color: #0f172a; margin-bottom: 2px; }
    .wb-type-sub { font-size: 11px; color: #64748b; font-weight: 500; display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden; }

    /* ── Main Canvas (Center) ── */
    .wb-right { flex: 1; min-width: 0; }
    .wb-area-grid { display: grid; grid-template-columns: 1fr; gap: 30px; }
    .wb-area { background: white; border-radius: 30px; border: 1px solid #e2e8f0; box-shadow: 0 20px 50px rgba(15,23,42,0.05); overflow: hidden; }
    .wb-area-head { padding: 20px 30px; background: #0f172a; color: white; display: flex; align-items: center; gap: 12px; }
    .wb-area-head i { font-size: 20px; color: #3b82f6; }
    .wb-area-title { font-size: 16px; font-weight: 900; letter-spacing: -0.02em; }
    .wb-area-desc { font-size: 12px; color: #94a3b8; margin-left: auto; font-weight: 500; }

    /* ── Canvas Sections ── */
    .wb-drop-zone { min-height: 200px; padding: 20px; background: #f8fafc; display: flex; flex-direction: column; gap: 15px; border-top: 1px solid #e2e8f0; }
    .wb-drop-zone.drag-over { background: #eff6ff; box-shadow: inset 0 0 40px rgba(59,130,246,0.05); }
    
    .wb-empty { display: flex; flex-direction: column; align-items: center; justify-content: center; height: 160px; border: 2px dashed #cbd5e1; border-radius: 20px; color: #94a3b8; font-size: 14px; gap: 15px; font-weight: 600; background: white; }
    .wb-empty i { font-size: 40px; color: #e2e8f0; }

    .wb-item { background: white; border: 1.5px solid #e2e8f0; border-radius: 20px; padding: 20px; cursor: grab; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); position: relative; display: flex; align-items: center; gap: 20px; }
    .wb-item:hover { border-color: #3b82f6; box-shadow: 0 15px 35px rgba(15,23,42,0.08); transform: scale(1.005); }
    .wb-item.dragging { opacity: 0.5; background: #3b82f610; }
    
    .wb-item-preview { width: 60px; height: 60px; border-radius: 12px; background: #f1f5f9; display: flex; align-items: center; justify-content: center; font-size: 24px; color: #3b82f6; flex-shrink: 0; }
    .wb-item-meta { flex: 1; }
    .wb-item-type { font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em; color: #3b82f6; margin-bottom: 4px; }
    .wb-item-name { font-size: 15px; font-weight: 800; color: #1e293b; }
    
    .wb-item-actions { display: flex; align-items: center; gap: 8px; }
    .wb-btn { width: 36px; height: 36px; border-radius: 10px; border: none; background: #f8fafc; color: #64748b; font-size: 14px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s; }
    .wb-btn:hover { background: #3b82f6; color: white; transform: translateY(-2px); }
    .wb-btn.del:hover { background: #ef4444; }
    
    .wb-switch { width: 50px; height: 26px; border-radius: 50px; background: #cbd5e1; position: relative; cursor: pointer; transition: all 0.3s; border: none; flex-shrink: 0; }
    .wb-switch.on { background: #22c55e; }
    .wb-switch::after { content: ''; position: absolute; top: 3px; left: 3px; width: 20px; height: 20px; background: white; border-radius: 50%; transition: all 0.3s; transform: scale(0.9); }
    .wb-switch.on::after { left: 27px; }

    /* ── Modal (Slide-over Drawer) ── */
    .wm-overlay { position: fixed; inset: 0; background: rgba(15,23,42,0.6); backdrop-filter: blur(4px); z-index: 9999; display: none; justify-content: flex-end; }
    .wm-overlay.open { display: flex; }
    .wm-box { background: white; width: 100%; max-width: 1350px; height: 100vh; display: flex; flex-direction: column; box-shadow: -20px 0 80px rgba(0,0,0,0.15); border-left: 1px solid rgba(0,0,0,0.05); transform: translateX(100%); transition: transform 0.45s cubic-bezier(0.16, 1, 0.3, 1); }
    .wm-overlay.open .wm-box { transform: translateX(0); }
    
    .wm-head { padding: 30px 50px; background: white; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; gap: 20px; flex-shrink: 0; }
    .wm-title { font-size: 22px; font-weight: 900; color: #0f172a; flex: 1; letter-spacing: -0.03em; }
    .wm-close { width: 50px; height: 50px; border-radius: 12px; border: none; background: #f1f5f9; color: #64748b; font-size: 24px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.3s; }
    .wm-close:hover { background: #ef4444; color: white; transform: rotate(90deg); }
    
    .wm-body { flex: 1; overflow-y: auto; padding: 0; scrollbar-width: thin; display: flex; flex-direction: column; background: #fafafa; }
    .wm-foot { padding: 30px 50px; background: white; border-top: 1px solid #f1f5f9; display: flex; justify-content: flex-end; gap: 15px; flex-shrink: 0; }
    
    /* Split Layout inside Modal */
    .wm-split { display: flex; height: 100%; min-height: 0; }
    .wm-main { flex: 1; padding: 50px; background: white; overflow-y: auto; border-right: 1px solid #f1f5f9; }
    .wm-side { width: 450px; padding: 50px; background: #fafafa; overflow-y: auto; }

    /* Form refinement */
    .field-card { background: white; padding: 25px; border-radius: 16px; border: 1px solid #f1f5f9; margin-bottom: 25px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02), 0 2px 4px -1px rgba(0,0,0,0.01); transition: all 0.3s; }
    .field-card:hover { box-shadow: 0 20px 40px rgba(15,23,42,0.04); transform: translateY(-2px); border-color: #e2e8f0; }
    
    .form-group { margin-bottom: 24px; }
    .form-label { display: block; font-size: 11px; font-weight: 900; color: #1e293b; margin-bottom: 12px; text-transform: uppercase; letter-spacing: 0.1em; opacity: 0.8; }
    .form-control { width: 100%; border: 1.5px solid #f1f5f9; border-radius: 10px; padding: 12px 16px; font-size: 14px; font-weight: 600; transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1); color: #334155; background: #fff; }
    .form-control:hover { border-color: #cbd5e1; background: #fafafa; }
    .form-control:focus { border-color: #3b82f6; box-shadow: 0 0 0 4px rgba(59,130,246,0.1); outline: none; background: #fff; }
    
    /* Background Tabs (matching screenshot) */
    .bg-tabs { display: flex; background: #f1f5f9; border-radius: 12px; padding: 5px; gap: 5px; margin-bottom: 20px; }
    .bg-tab-btn { flex: 1; border: none; background: transparent; padding: 10px; border-radius: 8px; font-size: 18px; color: #64748b; cursor: pointer; transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); display: flex; align-items: center; justify-content: center; }
    .bg-tab-btn:hover { background: rgba(255,255,255,0.5); color: #334155; }
    .bg-tab-btn.active { background: white; color: #3b82f6; box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
    
    /* Alignment Layout */
    .alignment-btn { transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); border: 1.5px solid transparent; }
    .alignment-btn:hover:not(.bg-white) { background: rgba(255,255,255,0.8); }
    
    /* ── Box Model Editor (Elementor-style) ── */
    .box-model-editor { 
        background: #fdfdfd; 
        padding: 30px; 
        border-radius: 20px; 
        border: 1px solid #eef2f6; 
        margin-top: 10px;
        box-shadow: inset 0 2px 10px rgba(0,0,0,0.02);
    }
    .box-container { position: relative; display: flex; align-items: center; justify-content: center; width: 100%; transition: all 0.3s; }
    
    .margin-box { 
        background: #ffe6cc; 
        padding: 60px; 
        border-radius: 6px; 
        border: 2px dashed #ffb366; 
        width: 100%;
        position: relative;
    }
    .padding-box { 
        background: #d4edda; 
        padding: 60px; 
        border-radius: 4px; 
        border: 2px dashed #82c91e; 
        width: 100%;
        position: relative;
    }
    .content-box { 
        background: #d0ebff; 
        padding: 30px 20px; 
        border: 2px solid #339af0; 
        font-size: 11px; 
        font-weight: 900; 
        color: #1864ab; 
        text-transform: uppercase; 
        border-radius: 3px;
        width: 100%;
        text-align: center;
        letter-spacing: 0.1em;
    }
    
    .bm-input { 
        position: absolute; 
        width: 45px; 
        height: 28px; 
        border: 1px solid rgba(0,0,0,0.1); 
        border-radius: 6px; 
        font-size: 11px; 
        text-align: center; 
        font-weight: 800; 
        color: #000; 
        background: rgba(255,255,255,0.9); 
        transition: all 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275); 
        z-index: 10;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }
    .bm-input:hover { transform: scale(1.1); border-color: #3b82f6; box-shadow: 0 5px 15px rgba(59,130,246,0.2); }
    .bm-input:focus { outline: none; border-color: #3b82f6; box-shadow: 0 0 0 4px rgba(59,130,246,0.2); width: 55px; }
    
    .bm-input.top { top: 12px; left: 50%; transform: translateX(-50%); }
    .bm-input.bottom { bottom: 12px; left: 50%; transform: translateX(-50%); }
    .bm-input.left { left: 12px; top: 50%; transform: translateY(-50%); }
    .bm-input.right { right: 12px; top: 50%; transform: translateY(-50%); }
    
    .bm-label { 
        position: absolute; 
        font-size: 10px; 
        font-weight: 900; 
        text-transform: uppercase; 
        color: rgba(0,0,0,0.4); 
        letter-spacing: 0.05em; 
        pointer-events: none; 
        z-index: 5;
    }
    .margin-label { top: 12px; left: 15px; }
    .padding-label { top: 12px; left: 15px; }

    /* Fix sections inside not working (Premium UI tweaks) */
    .wb-type-item.active { border-color: #3b82f6; background: #eff6ff; }
    .premium-form .field-card label { color: #1e293b; font-weight: 800; margin-bottom: 12px; font-size: 12px; }
</style>
@endpush

@section('content')
<div class="wb-wrap">
    
    {{-- Left Menu: Section Picker --}}
    <div class="wb-left">
        <div class="wb-panel-head">
            <i class="fa-solid fa-cube me-2"></i> Section Picker
        </div>
        <div class="wb-left-body">
            @foreach($types as $typeKey => $type)
            <div class="wb-type-item" draggable="true"
                 data-type="{{ $typeKey }}" data-label="{{ $type['label'] }}"
                 ondragstart="onTypeDragStart(event)">
                <div class="wb-type-icon">
                    <i class="{{ $type['icon'] ?? 'fa-solid fa-layer-group' }}"></i>
                </div>
                <div class="wb-type-info">
                    <div class="wb-type-name">{{ $type['label'] }}</div>
                    <div class="wb-type-sub">{{ $type['description'] }}</div>
                </div>
            </div>
            @endforeach
        </div>
        <div style="padding: 15px; border-top: 1px solid #f1f5f9;">
            <p style="font-size: 10px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.1em; text-align: center;">
                Kéo & thả để thêm Section
            </p>
        </div>
    </div>

    {{-- Main Canvas: Structure --}}
    <div class="wb-right">
        <div class="wb-area-grid">
            @foreach($widgetsByArea as $areaKey => $areaData)
            <div class="wb-area">
                <div class="wb-area-head">
                    <i class="fa-solid {{ $areaData['area']['icon'] ?? 'fa-table-cells-large' }}"></i>
                    <div class="wb-area-title">{{ $areaData['area']['label'] }}</div>
                    <div class="wb-area-desc">{{ $areaData['area']['description'] }}</div>
                </div>
                <div class="wb-drop-zone"
                     data-area="{{ $areaKey }}"
                     ondragover="onDragOver(event)"
                     ondragleave="onDragLeave(event)"
                     ondrop="onDrop(event)">
                    
                    @forelse($areaData['widgets'] as $widget)
                    <div class="wb-item" draggable="true"
                         data-id="{{ $widget->id }}" data-area="{{ $areaKey }}"
                         ondragstart="onWidgetDragStart(event)"
                         ondragend="onWidgetDragEnd(event)">
                        
                        <div class="wb-item-preview">
                            <i class="{{ $types[$widget->type]['icon'] ?? 'fa-solid fa-layer-group' }}"></i>
                        </div>
                        
                        <div class="wb-item-meta">
                            <div class="wb-item-type">{{ $types[$widget->type]['label'] ?? $widget->type }}</div>
                            <div class="wb-item-name">{{ $widget->name }}</div>
                        </div>

                        <div class="wb-item-actions">
                            <button class="wb-switch {{ $widget->is_active ? 'on' : '' }}" 
                                    onclick="toggleWidget({{ $widget->id }}, this)"></button>
                            
                            <button class="wb-btn" title="Chỉnh sửa" onclick="openEditModal({{ $widget->id }})">
                                <i class="fa-solid fa-sliders"></i>
                            </button>
                            
                            <button class="wb-btn" title="Nhân bản" onclick="cloneWidget({{ $widget->id }})">
                                <i class="fa-regular fa-clone"></i>
                            </button>
                            
                            <form action="{{ route('admin.widgets.destroy', $widget) }}" method="POST" style="display:contents;">
                                @csrf @method('DELETE')
                                <button class="wb-btn del" title="Xóa" onclick="return confirm('Xóa Section này?')">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <div class="wb-empty">
                        <i class="fa-solid fa-cloud-arrow-down"></i>
                        Trống. Hãy kéo một Section vào đây.
                    </div>
                    @endforelse
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- CONFIG MODAL (DRAWER) --}}
<div class="wm-overlay" id="wm-overlay">
    <div class="wm-box shadow-lg">
        <div class="wm-head">
            <div id="wm-modal-icon" style="width:44px;height:44px;background:#f8fafc;border-radius:12px;display:flex;align-items:center;justify-content:center;color:#3b82f6;font-size:18px;">
                <i class="fa-solid fa-puzzle-piece"></i>
            </div>
            <div class="wm-title-wrap" style="flex:1;">
                <div class="wm-title" id="wm-modal-title" style="margin-bottom:2px;">Section Theme</div>
                <div id="wm-modal-subtitle" style="font-size:11px;color:#94a3b8;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;">Cấu hình các tham số và giao diện cho Section</div>
            </div>
            <button class="wm-close shadow-sm" onclick="closeModal()"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="wm-body" id="wm-body">
            {{-- Content injected via JS --}}
        </div>
        <div class="wm-foot border-top shadow-lg d-flex justify-content-between align-items-center px-4 py-3 bg-white">
            <div id="wm-delete-btn-wrap" style="display:none;">
                <button class="btn btn-outline-danger px-4 py-2 fw-900 fs-11 uppercase rounded-3" onclick="deleteFromModal()">
                    <i class="fa-solid fa-trash-can me-2"></i> Xóa Section
                </button>
            </div>
            <div class="ms-auto d-flex gap-3">
                <button class="btn btn-light px-5 py-3 fw-900 fs-13 uppercase rounded-4 border" onclick="closeModal()">
                    <i class="fa-solid fa-xmark me-2"></i> Đóng
                </button>
                <button class="btn btn-success px-5 py-3 fw-900 fs-13 uppercase rounded-4 shadow-sm" id="wm-save" onclick="submitModal()">
                    <i class="fa-solid fa-circle-check me-2"></i> Lưu nội dung
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
@php
    $areasJson      = array_map(fn($a) => $a['area']['label'], $widgetsByArea);
    $categoriesJson = $categories->map(fn($c) => ['id' => $c->id, 'name' => $c->name])->values();
@endphp

const TYPES       = @json($types);
const AREAS       = @json($areasJson);
const CATEGORIES  = @json($categoriesJson);
const REORDER_URL = '{{ route('admin.widgets.reorder') }}';
const TOGGLE_URL  = '/admin/widgets/{id}/toggle';
const CLONE_URL   = '/admin/widgets/{id}/clone';
const DATA_URL    = '/admin/widgets/{id}/data';
const STORE_URL   = '{{ route('admin.widgets.store') }}';
const UPDATE_URL  = '/admin/widgets/{id}';
const DELETE_URL  = '/admin/widgets/{id}';
const CSRF        = '{{ csrf_token() }}';

let draggedWidgetEl = null;
let draggedType     = null;
let modalMode       = null;
let modalWidgetId   = null;

// Re-implementing core logic with premium UI
function onTypeDragStart(e) {
    draggedType = { type: e.currentTarget.dataset.type, label: e.currentTarget.dataset.label };
    draggedWidgetEl = null;
    e.dataTransfer.setData('text/plain', draggedType.type);
}

function onWidgetDragStart(e) {
    draggedWidgetEl = e.currentTarget;
    draggedType = null;
    e.dataTransfer.setData('text/plain', e.currentTarget.dataset.id);
    setTimeout(() => draggedWidgetEl?.classList.add('dragging'), 0);
}

function onWidgetDragEnd(e) {
    e.currentTarget.classList.remove('dragging');
    removePlaceholder();
}

function onDragOver(e) {
    e.preventDefault();
    const zone = e.currentTarget;
    zone.classList.add('drag-over');
    
    if (!draggedWidgetEl && !draggedType) return;
    
    const afterEl = getDragAfterElement(zone, e.clientY);
    removePlaceholder();
    const ph = document.createElement('div');
    ph.id = 'drag-placeholder';
    ph.style.cssText = 'height:4px; background:#3b82f6; border-radius:10px; margin:10px 0;';
    afterEl ? zone.insertBefore(ph, afterEl) : zone.appendChild(ph);
}

function onDragLeave(e) {
    e.currentTarget.classList.remove('drag-over');
    removePlaceholder();
}

function onDrop(e) {
    e.preventDefault();
    const zone = e.currentTarget;
    zone.classList.remove('drag-over');
    removePlaceholder();
    
    const area = zone.dataset.area;

    if (draggedType) {
        const t = draggedType;
        draggedType = null;
        openCreateModal(t.type, area);
        return;
    }

    if (draggedWidgetEl) {
        const el = draggedWidgetEl;
        el.classList.remove('dragging');
        draggedWidgetEl = null;
        const afterEl = getDragAfterElement(zone, e.clientY);
        zone.querySelectorAll('.wb-empty').forEach(emp => emp.remove());
        afterEl ? zone.insertBefore(el, afterEl) : zone.appendChild(el);
        el.dataset.area = area;
        saveOrder();
    }
}

function getDragAfterElement(container, y) {
    const items = [...container.querySelectorAll('.wb-item:not(.dragging)')];
    return items.reduce((closest, child) => {
        const rect = child.getBoundingClientRect();
        const offset = y - rect.top - rect.height / 2;
        return (offset < 0 && offset > closest.offset) ? { offset, element: child } : closest;
    }, { offset: Number.NEGATIVE_INFINITY }).element;
}

function removePlaceholder() { document.getElementById('drag-placeholder')?.remove(); }

function saveOrder() {
    const items = [];
    document.querySelectorAll('.wb-drop-zone').forEach(zone => {
        zone.querySelectorAll('.wb-item[data-id]').forEach((el, idx) => {
            items.push({ id: +el.dataset.id, area: zone.dataset.area, sort_order: idx });
        });
    });
    fetch(REORDER_URL, { method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF}, body:JSON.stringify({items}) });
}

function toggleWidget(id, btn) {
    fetch(TOGGLE_URL.replace('{id}', id), { method:'POST', headers:{'X-CSRF-TOKEN':CSRF} })
        .then(r => r.json())
        .then(d => btn.classList.toggle('on', d.is_active));
}

function cloneWidget(id) {
    if(!confirm('Nhân bản Section này?')) return;
    fetch(CLONE_URL.replace('{id}', id), { method:'POST', headers:{'X-CSRF-TOKEN':CSRF} }).then(() => location.reload());
}

// ── Modal Logic ──
function openModal() {
    document.getElementById('wm-overlay').classList.add('open');
    document.body.style.overflow = 'hidden';
}
function closeModal() {
    document.getElementById('wm-overlay').classList.remove('open');
    document.body.style.overflow = '';
}

function openCreateModal(type, area) {
    modalMode = 'create'; modalWidgetId = null;
    document.getElementById('wm-delete-btn-wrap').style.display = 'none';
    const t = TYPES[type];
    document.getElementById('wm-modal-title').textContent = 'Thêm Section: ' + (t?.label ?? type);
    document.getElementById('wm-modal-icon').innerHTML = `<i class="${t?.icon ?? 'fa-solid fa-puzzle-piece'}"></i>`;
    renderModalForm({ type, area, name:'', sort_order:0, is_active:true, config:{} });
    openModal();
}

function openEditModal(id) {
    modalMode = 'edit'; modalWidgetId = id;
    document.getElementById('wm-delete-btn-wrap').style.display = 'block';
    document.getElementById('wm-body').innerHTML = '<div class="text-center py-5"><i class="fa-solid fa-spinner fa-spin fa-2x text-muted"></i></div>';
    openModal();
    fetch(DATA_URL.replace('{id}', id)).then(r => r.json()).then(data => {
        const t = TYPES[data.type];
        document.getElementById('wm-modal-title').textContent = 'Config: ' + data.name;
        document.getElementById('wm-modal-icon').innerHTML = `<i class="${t?.icon ?? 'fa-solid fa-puzzle-piece'}"></i>`;
        renderModalForm(data);
    });
}

function renderModalForm(data) {
    const areaOpts = Object.entries(AREAS).map(([k,v]) => `<option value="${k}" ${data.area===k?'selected':''}>${v}</option>`).join('');
    const typeOpts = Object.entries(TYPES).map(([k,t]) => `<option value="${k}" ${data.type===k?'selected':''}>${t.label}</option>`).join('');
    
    document.getElementById('wm-body').innerHTML = `
        <div class="wm-split">
            <div class="wm-main" id="wm-main-fields">
                <div class="premium-form">
                    <div class="row g-4 mb-5">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label">Tên hiển thị (Admin only)</label>
                                <input type="text" id="wm-name" class="form-control" value="${escHtml(data.name || '')}" placeholder="Ví dụ: Banner Trang chủ">
                            </div>
                        </div>
                        ${modalMode==='create' 
                            ? `<div class="col-md-6"><div class="form-group"><label class="form-label">Loại Section</label><select id="wm-type" class="form-control" onchange="onModalTypeChange(this.value)">${typeOpts}</select></div></div>`
                            : `<input type="hidden" id="wm-type" value="${escHtml(data.type)}">`}
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">Khu vực</label>
                                <select id="wm-area" class="form-control">${areaOpts}</select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">Thứ tự</label>
                                <input type="number" id="wm-sort" class="form-control" value="${data.sort_order ?? 0}">
                            </div>
                        </div>
                    </div>
                    <div id="wm-config-main"></div>
                </div>
            </div>
            <div class="wm-side" id="wm-config-side">
                <div class="d-flex align-items-center bg-white p-4 rounded-4 shadow-sm mb-5 border border-dashed border-primary">
                    <input type="checkbox" id="wm-active" value="1" ${data.is_active?'checked':''} style="width:24px;height:24px;accent-color:#3b82f6;cursor:pointer;">
                    <label for="wm-active" class="ms-3 mb-0 fw-900 text-dark cursor-pointer fs-13 uppercase">Kích hoạt Section</label>
                </div>
                <div id="wm-config-common"></div>
            </div>
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
    
    mainWrap.innerHTML = `<h6 class="fw-black text-primary mb-4 uppercase tracking-wider fs-11 border-bottom pb-2" style="letter-spacing:0.05em;"><i class="fa-solid fa-pen-to-square me-2"></i> Nội dung ${t.label}</h6>`;
    sideWrap.innerHTML = ``;

    let target = mainWrap;
    let bgFields = [];
    
    t.fields.forEach(f => {
        if (f.type === 'tab_start' && f.key.includes('common')) {
            target = sideWrap;
            if (f.label) target.innerHTML += `<h6 class="fw-black text-secondary mb-4 uppercase tracking-wider fs-11 border-bottom pb-2" style="letter-spacing:0.05em;"><i class="fa-solid fa-sliders me-2"></i> ${f.label}</h6>`;
            return;
        }
        if (f.type === 'tab_end') {
            target = mainWrap;
            return;
        }

        // Special handling for Background Tabs in sidebar
        if (target === sideWrap && ['bg_type', 'bg_color', 'bg_image'].includes(f.key)) {
            bgFields.push(f);
            if (bgFields.length === 3) {
                renderBackgroundTabs(sideWrap, bgFields, config);
            }
            return;
        }

        target.appendChild(buildField(f, config[f.key] ?? f.default ?? null, 'config'));
    });
}

function renderBackgroundTabs(container, fields, config) {
    const bgType = config.bg_type ?? 'none';
    const card = document.createElement('div');
    card.className = 'field-card';
    card.innerHTML = `
        <label class="form-label">Nền Section</label>
        <div class="bg-tabs mb-4">
            <button type="button" class="bg-tab-btn ${bgType==='none'?'active':''}" onclick="setBgTab(this, 'none')"><i class="fa-solid fa-ban"></i></button>
            <button type="button" class="bg-tab-btn ${bgType==='color'?'active':''}" onclick="setBgTab(this, 'color')"><i class="fa-solid fa-palette"></i></button>
            <button type="button" class="bg-tab-btn ${bgType==='image'?'active':''}" onclick="setBgTab(this, 'image')"><i class="fa-solid fa-image"></i></button>
            <button type="button" class="bg-tab-btn ${bgType==='video'?'active':''}" onclick="setBgTab(this, 'video')"><i class="fa-solid fa-video"></i></button>
        </div>
        <input type="hidden" name="config[bg_type]" value="${bgType}">
        <div class="bg-content">
            <div class="bg-pane" data-bg="color" style="display:${bgType==='color'?'block':'none'}">
                ${buildField(fields.find(f=>f.key==='bg_color'), config.bg_color, 'config').innerHTML}
            </div>
            <div class="bg-pane" data-bg="image" style="display:${bgType==='image'?'block':'none'}">
                ${buildField(fields.find(f=>f.key==='bg_image'), config.bg_image, 'config').innerHTML}
            </div>
        </div>
    `;
    container.appendChild(card);
}

function setBgTab(btn, type) {
    const wrap = btn.closest('.field-card');
    wrap.querySelectorAll('.bg-tab-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    wrap.querySelector('input[name="config[bg_type]"]').value = type;
    wrap.querySelectorAll('.bg-pane').forEach(p => p.style.display = p.dataset.bg === type ? 'block' : 'none');
}

function submitModal() {
    const fd = new FormData();
    fd.append('_token', CSRF);
    
    const nameVal = document.getElementById('wm-name')?.value || 'New Section';
    fd.append('name', nameVal);
    fd.append('type', document.getElementById('wm-type')?.value);
    fd.append('area', document.getElementById('wm-area')?.value);
    fd.append('sort_order', document.getElementById('wm-sort')?.value ?? 0);
    fd.append('is_active', document.getElementById('wm-active')?.checked ? 1 : 0);
    
    document.querySelectorAll('[name^="config"]').forEach(el => {
        if (el.type === 'checkbox') {
            if (el.dataset.sync) return;
            fd.append(el.name, el.checked ? 1 : 0);
        } else if (el.type === 'radio') {
            if (el.checked) fd.append(el.name, el.value);
        } else {
            fd.append(el.name, el.value);
        }
    });

    const url = modalMode === 'create' ? STORE_URL : UPDATE_URL.replace('{id}', modalWidgetId);
    if (modalMode === 'edit') fd.append('_method', 'PUT');
    
    const btn = document.getElementById('wm-save');
    btn.disabled = true; 
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> Đang lưu...';
    
    fetch(url, { 
        method: 'POST', 
        body: fd, 
        headers: { 'Accept': 'application/json' } 
    })
        .then(r => {
            if (!r.ok) throw new Error(r.statusText);
            return r.json();
        })
        .then(data => {
            if (modalMode === 'edit') {
                const item = document.querySelector(`.wb-item[data-id="${modalWidgetId}"]`);
                if (item) {
                     item.querySelector('.wb-item-name').textContent = nameVal;
                     item.querySelector('.wb-switch').classList.toggle('on', document.getElementById('wm-active')?.checked);
                }
                closeModal();
                showToast('Cập nhật nội dung thành công!');
            } else {
                location.reload(); // Still reload for create to get full HTML
            }
        })
        .catch(err => alert('Lỗi: ' + err.message))
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = originalText;
        });
}

function showToast(msg) {
    const toast = document.createElement('div');
    toast.style.cssText = 'position:fixed; bottom:30px; left:50%; transform:translateX(-50%); background:#0f172a; color:white; padding:15px 30px; border-radius:12px; z-index:10000; font-weight:800; box-shadow:0 10px 30px rgba(0,0,0,0.2); display:flex; align-items:center; gap:12px; border:1px solid rgba(255,255,255,0.1);';
    toast.innerHTML = `<i class="fa-solid fa-circle-check text-success fs-18"></i> ${msg}`;
    document.body.appendChild(toast);
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transition = 'opacity 0.5s';
        setTimeout(() => toast.remove(), 500);
    }, 2500);
}

function deleteFromModal() {
    if (!confirm('Bạn có chắc chắn muốn XÓA Section này không? Hành động này không thể hoàn tác.')) return;
    
    const url = DELETE_URL.replace('{id}', modalWidgetId);
    fetch(url, { 
        method: 'POST', 
        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        body: (() => { const fd = new FormData(); fd.append('_method', 'DELETE'); return fd; })()
    })
    .then(r => r.json())
    .then(() => {
        const item = document.querySelector(`.wb-item[data-id="${modalWidgetId}"]`);
        if (item) item.remove();
        closeModal();
        showToast('Đã xóa Section thành công!');
    });
}

// ── Field Logic (Simplified) ──
function buildField(field, value, prefix) {
    const wrap = document.createElement('div');
    wrap.className = 'field-card';
    const name = `${prefix}[${field.key}]`;
    
    // Tab Start/End logic handled in renderModalConfig
    if (field.type === 'tab_start' || field.type === 'tab_end') return document.createTextNode('');

    let input = '';
    if (field.type === 'text' || field.type === 'image') {
        if (field.type === 'image') {
            const inputId = `img-input-${Math.random().toString(36).substr(2, 9)}`;
            input = `
                <div class="d-flex gap-3 align-items-center">
                    <div id="${inputId}_preview" style="width:50px;height:50px;border-radius:8px;background:#f8fafc;border:1px solid #e2e8f0;display:flex;align-items:center;justify-content:center;overflow:hidden;">
                        ${value ? `<img src="${value}" style="width:100%;height:100%;object-fit:cover;">` : `<i class="fa-solid fa-image text-muted"></i>`}
                    </div>
                    <div class="flex-1 d-flex gap-2">
                        <input type="text" id="${inputId}" name="${name}" class="form-control" value="${escHtml(value ?? '')}" placeholder="URL ảnh..." oninput="updateImgPreview('${inputId}_preview', this.value)">
                        <button type="button" class="btn btn-dark px-3 py-2 rounded-3" onclick="openMediaPicker('${inputId}')">
                            <i class="fa-solid fa-photo-film"></i>
                        </button>
                    </div>
                </div>
            `;
        } else {
            input = `<input type="text" name="${name}" class="form-control" value="${escHtml(value ?? '')}" placeholder="${field.placeholder ?? ''}">`;
        }
    } else if (field.type === 'number') {
        input = `<input type="number" name="${name}" class="form-control" value="${value ?? ''}">`;
    } else if (field.type === 'toggle') {
        input = `<div class="d-flex align-items-center justify-content-between">
            <label class="mb-0 fs-13 fw-800 text-dark uppercase" style="letter-spacing:0.05em;">${field.label}</label>
            <div class="form-check form-switch mb-0"><input type="checkbox" name="${name}" value="1" ${value?'checked':''} class="form-check-input" style="width:40px;height:20px;cursor:pointer;"></div>
        </div>`;
        wrap.innerHTML = input; return wrap;
    } else if (field.type === 'select') {
        const os = Object.entries(field.options??{}).map(([k,v]) => `<option value="${k}" ${value==k?'selected':''}>${v}</option>`).join('');
        input = `<select name="${name}" class="form-control">${os}</select>`;
    } else if (field.type === 'color') {
        input = `
            <div class="d-flex align-items-center gap-2">
                <input type="color" name="${name}" class="form-control-color p-1 border-0" value="${value ?? '#ffffff'}" style="width:40px;height:40px;border-radius:6px;background:none;cursor:pointer;">
                <input type="text" class="form-control flex-1" value="${value ?? '#ffffff'}" oninput="this.previousElementSibling.value=this.value">
            </div>
        `;
    } else if (field.type === 'alignment') {
        input = `
            <div class="d-flex bg-light p-1 rounded-3" style="width: 100%; border: 1px solid #e2e8f0;">
                ${['left', 'center', 'right'].map(a => {
                    const isActive = value === a;
                    const iconMap = { left: 'fa-align-left', center: 'fa-align-center', right: 'fa-align-right' };
                    return `
                    <label class="flex-1 text-center py-2 rounded-2 cursor-pointer transition-all ${isActive ? 'bg-white shadow-sm text-primary border border-white' : 'text-muted'}" style="display:flex; align-items:center; justify-content:center; font-size: 15px;">
                        <input type="radio" name="${name}" value="${a}" class="d-none" ${isActive ? 'checked' : ''} 
                            onchange="const p = this.closest('.d-flex'); p.querySelectorAll('label').forEach(l=>l.className='flex-1 text-center py-2 rounded-2 cursor-pointer transition-all text-muted'); this.parentElement.className='flex-1 text-center py-2 rounded-2 cursor-pointer transition-all bg-white shadow-sm text-primary border border-white';">
                        <i class="fa-solid ${iconMap[a]}"></i>
                    </label>
                    `;
                }).join('')}
            </div>
        `;
    } else if (field.type === 'box_model') {
        const m = value ?? {};
        input = `
            <div class="box-model-editor">
                <div class="box-container margin-box">
                    <div class="bm-label margin-label">Margin</div>
                    <input type="number" name="${name}[margin_top]" class="bm-input top" value="${m.margin_top ?? 0}" title="Margin Top">
                    <input type="number" name="${name}[margin_right]" class="bm-input right" value="${m.margin_right ?? 0}" title="Margin Right">
                    <input type="number" name="${name}[margin_bottom]" class="bm-input bottom" value="${m.margin_bottom ?? 0}" title="Margin Bottom">
                    <input type="number" name="${name}[margin_left]" class="bm-input left" value="${m.margin_left ?? 0}" title="Margin Left">
                    
                    <div class="box-container padding-box">
                        <div class="bm-label padding-label">Padding</div>
                        <input type="number" name="${name}[padding_top]" class="bm-input top" value="${m.padding_top ?? 0}" title="Padding Top">
                        <input type="number" name="${name}[padding_right]" class="bm-input right" value="${m.padding_right ?? 0}" title="Padding Right">
                        <input type="number" name="${name}[padding_bottom]" class="bm-input bottom" value="${m.padding_bottom ?? 0}" title="Padding Bottom">
                        <input type="number" name="${name}[padding_left]" class="bm-input left" value="${m.padding_left ?? 0}" title="Padding Left">
                        
                        <div class="content-box">Section Content</div>
                    </div>
                </div>
            </div>
        `;
    } else if (field.type === 'category_select') {
        const categories = CATEGORIES || [];
        // Support for single selection (often used in repeaters) vs multi-checkbox
        if (field.multiple === false || field.single === true) {
            const os = categories.map(c => `<option value="${c.id}" ${value==c.id?'selected':''}>${escHtml(c.name)}</option>`).join('');
            input = `<select name="${name}" class="form-control"><option value="">Tất cả danh mục</option>${os}</select>`;
        } else {
            const selectedIds = Array.isArray(value) ? value.map(String) : (value ? [String(value)] : []);
            input = `<div class="p-3 border rounded-3 bg-light" style="max-height:180px;overflow-y:auto;">
                ${categories.map(c => `
                    <div class="d-flex align-items-center mb-1">
                        <input type="checkbox" name="${name}[]" value="${c.id}" ${selectedIds.includes(String(c.id))?'checked':''} id="cat-${prefix}-${field.key}-${c.id}" class="me-2">
                        <label for="cat-${prefix}-${field.key}-${c.id}" class="mb-0 fs-12 fw-700 text-secondary">${escHtml(c.name)}</label>
                    </div>
                `).join('')}
            </div>`;
        }
    } else if (field.type === 'repeater') { 
        wrap.innerHTML = buildRepeater(field, value??[], prefix); 
        initRepeater(wrap, field, prefix); 
        return wrap; 
    }

    wrap.innerHTML = `<label class="form-label">${field.label}</label>${input}`;
    return wrap;
}

function buildRepeater(field, rows, prefix) {
    const name = `${prefix}[${field.key}]`;
    return `
        <div class="repeater-wrap border-start border-4 border-primary ps-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <label class="form-label mb-0" style="color:#0f172a; font-weight:900;">${field.label}</label>
                <button type="button" class="btn btn-sm btn-dark px-3 rounded-pill repeater-add">+ Thêm Item</button>
            </div>
            <div class="repeater-rows">
                ${(rows??[]).map((row,i) => buildRepeaterRow(field, row, name, i)).join('')}
            </div>
        </div>`;
}

function buildRepeaterRow(field, data, name, idx) {
    const rowPrefix = `${name}[${idx}]`;
    const innerFields = (field.fields??[]).map(sf => {
        const value = data[sf.key] ?? sf.default ?? null;
        // Use buildField recursively
        const fieldHtml = buildField(sf, value, rowPrefix);
        return fieldHtml.outerHTML;
    }).join('');

    return `
    <div class="repeater-row mb-4 p-4 bg-light rounded-4 position-relative border border-dashed border-primary">
        <button type="button" class="btn btn-danger btn-sm rounded-circle position-absolute top-0 end-0 m-2 repeater-remove" style="width:24px;height:24px;padding:0;font-size:12px;z-index:10;">×</button>
        <div class="row g-3">
            ${(field.fields??[]).map(sf => {
                const value = data[sf.key] ?? sf.default ?? null;
                const fieldHtml = buildField(sf, value, rowPrefix);
                return `<div class="${sf.col ?? 'col-12'}">${fieldHtml.innerHTML}</div>`;
            }).join('')}
        </div>
    </div>`;
}

function initRepeater(wrap, field, prefix) {
    const name = `${prefix}[${field.key}]`;
    const addBtn = wrap.querySelector('.repeater-add');
    const rowsContainer = wrap.querySelector('.repeater-rows');

    addBtn.onclick = () => {
        const idx = rowsContainer.querySelectorAll('.repeater-row').length;
        const rowHtml = buildRepeaterRow(field, {}, name, idx);
        const div = document.createElement('div');
        div.innerHTML = rowHtml;
        const newRow = div.firstElementChild;
        rowsContainer.appendChild(newRow);
        
        // Bind remove to the new row
        newRow.querySelector('.repeater-remove').onclick = () => newRow.remove();
    };

    // Bind remove to existing rows
    rowsContainer.querySelectorAll('.repeater-remove').forEach(btn => {
        btn.onclick = () => btn.closest('.repeater-row').remove();
    });
}

function escHtml(str) {
    return String(str??'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}
</script>
@endpush
