@extends('admin.layouts.app')
@section('title', 'Quản lý Widget')
@section('page-title', 'Widget')
@section('page-subtitle', 'Kéo thả widget vào các khu vực hiển thị')

@push('styles')
<style>
/* ── Overall layout ── */
.wb-wrap{display:flex;min-height:calc(100vh - 120px);background:#fff;border-radius:14px;border:1.5px solid #e2e8f0;overflow:visible}

/* ── Left panel ── */
.wb-left{width:260px;flex-shrink:0;border-right:1.5px solid #e2e8f0;display:flex;flex-direction:column;position:sticky;top:0;max-height:calc(100vh - 120px);overflow:hidden}
.wb-panel-head{padding:10px 14px;background:#f8fafc;border-bottom:1px solid #e2e8f0;font-size:12px;font-weight:700;color:#374151}
.wb-left-body{overflow-y:auto;flex:1}
.wb-type-grid{display:flex;flex-direction:column}
.wb-type-item{padding:10px 12px;border-bottom:1px solid #f1f5f9;cursor:grab;user-select:none;transition:background .1s}
.wb-type-item:hover{background:#f0f7ff}
.wb-type-item:active{cursor:grabbing}
.wb-type-name{font-size:11.5px;font-weight:700;color:#1e293b;line-height:1.3}
.wb-type-sub{font-size:10px;color:#94a3b8;margin-top:1px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}

/* ── Right panel ── */
.wb-right{flex:1;display:flex;flex-direction:column;min-width:0}
.wb-right-body{flex:1;padding:12px;display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px;align-content:start;scrollbar-width:thin;scrollbar-color:#cbd5e1 #f1f5f9}
@media(max-width:1100px){.wb-right-body{grid-template-columns:1fr 1fr}}
@media(max-width:700px){.wb-right-body{grid-template-columns:1fr}}

/* ── Area column ── */
.wb-area{background:#fff;border-radius:10px;border:1.5px solid #e2e8f0;overflow:hidden;display:flex;flex-direction:column}
.wb-area-head{padding:8px 12px;background:#f8fafc;border-bottom:1px solid #e2e8f0;font-size:11px;font-weight:800;color:#374151;text-transform:uppercase;letter-spacing:.06em;white-space:nowrap}

/* ── Drop zone ── */
.wb-drop-zone{min-height:80px;padding:6px;display:flex;flex-direction:column;gap:5px;transition:background .15s}
.wb-drop-zone.drag-over{background:#eff6ff}
.wb-empty{display:flex;align-items:center;justify-content:center;min-height:60px;border:2px dashed #e2e8f0;border-radius:8px;color:#cbd5e1;font-size:11px;gap:5px}

/* ── Widget item (dark card) ── */
.wb-item{border:1.5px dashed #4b5563;border-radius:8px;background:#1f2937;cursor:grab;transition:opacity .15s,box-shadow .15s;position:relative}
.wb-item:hover{box-shadow:0 2px 10px rgba(0,0,0,.3)}
.wb-item:active{cursor:grabbing}
.wb-item.dragging{opacity:.3}
.wb-item-inner{padding:8px 10px}
.wb-item-type-label{font-size:10px;color:#6b7280;margin-bottom:3px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.wb-item-name{font-size:13px;font-weight:700;color:#e5e7eb;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;padding-right:70px}
.wb-item-actions{position:absolute;top:50%;right:8px;transform:translateY(-50%);display:flex;align-items:center;gap:2px}
.wb-act{width:22px;height:22px;border-radius:5px;border:none;background:transparent;display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:11px;transition:all .12s;color:#9ca3af;padding:0}
.wb-act.edit:hover{color:#10b981;background:rgba(16,185,129,.15)}
.wb-act.copy:hover{color:#60a5fa;background:rgba(96,165,250,.15)}
.wb-act.del:hover{color:#f87171;background:rgba(248,113,113,.15)}
.wb-toggle{width:28px;height:15px;border-radius:8px;border:none;cursor:pointer;position:relative;flex-shrink:0;transition:background .2s}
.wb-toggle.on{background:#22c55e}.wb-toggle.off{background:#4b5563}
.wb-toggle::after{content:'';position:absolute;top:2px;width:11px;height:11px;border-radius:50%;background:#fff;transition:left .2s}
.wb-toggle.on::after{left:15px}.wb-toggle.off::after{left:2px}

/* ── Category multi-select ── */
.cat-opt{padding:5px 8px;border-radius:5px;font-size:12px;color:#374151;cursor:pointer;transition:background .1s}
.cat-opt:hover{background:#f0f7ff}
.cat-opt.selected{background:#dbeafe;color:#1d4ed8;font-weight:600}
/* ── Modal ── */
.wm-overlay{position:fixed;inset:0;background:rgba(0,0,0,.55);z-index:9000;display:none;align-items:center;justify-content:center}
.wm-overlay.open{display:flex}
.wm-box{background:#fff;border-radius:14px;width:680px;max-width:95vw;max-height:90vh;display:flex;flex-direction:column;box-shadow:0 20px 60px rgba(0,0,0,.3)}
.wm-head{padding:14px 18px;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;gap:10px}
.wm-title{font-size:14px;font-weight:700;color:#1e293b;flex:1}
.wm-close{width:28px;height:28px;border-radius:7px;border:none;background:#f1f5f9;color:#64748b;cursor:pointer;font-size:13px;display:flex;align-items:center;justify-content:center}
.wm-close:hover{background:#e2e8f0}
.wm-body{overflow-y:auto;flex:1;padding:18px}
.wm-foot{padding:12px 18px;border-top:1px solid #e2e8f0;display:flex;align-items:center;justify-content:flex-end;gap:8px}
</style>
@endpush

@section('content')
<div class="wb-wrap">

    {{-- LEFT: Widget Types --}}
    <div class="wb-left">
        <div class="wb-panel-head"><i class="fa-solid fa-puzzle-piece" style="color:#7c3aed;margin-right:6px;"></i>Widget</div>
        <div class="wb-left-body">
            <div class="wb-type-grid">
                @foreach($types as $typeKey => $type)
                <div class="wb-type-item" draggable="true"
                     data-type="{{ $typeKey }}" data-label="{{ $type['label'] }}"
                     ondragstart="onTypeDragStart(event)">
                    <div class="wb-type-name">{{ $type['label'] }}</div>
                    <div class="wb-type-sub">{{ $type['description'] }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- RIGHT: Widget Box --}}
    <div class="wb-right">
        <div class="wb-panel-head">Widget Box</div>
        <div class="wb-right-body">
            @foreach($widgetsByArea as $areaKey => $areaData)
            <div class="wb-area">
                <div class="wb-area-head">{{ $areaData['area']['label'] }}</div>
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
                        <div class="wb-item-inner">
                            <div class="wb-item-type-label">{{ $types[$widget->type]['label'] ?? $widget->type }}</div>
                            <div class="wb-item-name">{{ $widget->name }}</div>
                        </div>
                        <div class="wb-item-actions">
                            <button class="wb-toggle {{ $widget->is_active ? 'on' : 'off' }}"
                                    onclick="toggleWidget({{ $widget->id }}, this)"></button>
                            <button class="wb-act edit" title="Chỉnh sửa" onclick="openEditModal({{ $widget->id }})">
                                <i class="fa-solid fa-wrench"></i>
                            </button>
                            <button class="wb-act copy" title="Nhân bản" onclick="cloneWidget({{ $widget->id }})">
                                <i class="fa-regular fa-copy"></i>
                            </button>
                            <form action="{{ route('admin.widgets.destroy', $widget) }}" method="POST" style="display:contents;">
                                @csrf @method('DELETE')
                                <button class="wb-act del" title="Xóa" onclick="return confirm('Xóa widget này?')">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <div class="wb-empty"><i class="fa-solid fa-arrow-down"></i> Kéo widget vào đây</div>
                    @endforelse
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- MODAL --}}
<div class="wm-overlay" id="wm-overlay" onclick="closeModalOnBackdrop(event)">
    <div class="wm-box">
        <div class="wm-head">
            <span class="wm-title" id="wm-title">Widget</span>
            <button class="wm-close" onclick="closeModal()"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="wm-body" id="wm-body"></div>
        <div class="wm-foot">
            <button class="btn btn-primary" id="wm-save" onclick="submitModal()">
                <i class="fa-solid fa-floppy-disk"></i> Lưu
            </button>
            <button class="btn btn-secondary" onclick="closeModal()">Đóng</button>
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
const CSRF        = '{{ csrf_token() }}';

let draggedWidgetEl = null;
let draggedType     = null;
let modalMode       = null;
let modalWidgetId   = null;
// Counter per zone to handle dragenter/dragleave on child elements
const dragCounters  = new WeakMap();

function onTypeDragStart(e) {
    draggedType     = { type: e.currentTarget.dataset.type, label: e.currentTarget.dataset.label };
    draggedWidgetEl = null;
    e.dataTransfer.setData('text/plain', draggedType.type); // required for Firefox
    e.dataTransfer.effectAllowed = 'copy';
}
function onWidgetDragStart(e) {
    draggedWidgetEl = e.currentTarget;
    draggedType     = null;
    e.dataTransfer.setData('text/plain', e.currentTarget.dataset.id);
    e.dataTransfer.effectAllowed = 'move';
    setTimeout(() => draggedWidgetEl?.classList.add('dragging'), 0);
}
function onWidgetDragEnd(e) {
    e.currentTarget.classList.remove('dragging');
    removePlaceholder();
    // Clean up any leftover drag-over states
    document.querySelectorAll('.wb-drop-zone.drag-over').forEach(z => z.classList.remove('drag-over'));
}

function onDragEnter(e) {
    e.preventDefault();
    const zone = e.currentTarget;
    dragCounters.set(zone, (dragCounters.get(zone) || 0) + 1);
    zone.classList.add('drag-over');
}
function onDragOver(e) {
    e.preventDefault();
    e.dataTransfer.dropEffect = draggedWidgetEl ? 'move' : 'copy';
    if (!draggedWidgetEl) return;
    const zone = e.currentTarget;
    const afterEl = getDragAfterElement(zone, e.clientY);
    removePlaceholder();
    const ph = document.createElement('div');
    ph.id = 'drag-placeholder';
    ph.style.cssText = 'height:3px;background:#3b82f6;border-radius:3px;margin:2px 0;pointer-events:none;';
    afterEl ? zone.insertBefore(ph, afterEl) : zone.appendChild(ph);
}
function onDragLeave(e) {
    const zone = e.currentTarget;
    const count = (dragCounters.get(zone) || 1) - 1;
    dragCounters.set(zone, count);
    if (count <= 0) {
        dragCounters.set(zone, 0);
        zone.classList.remove('drag-over');
        removePlaceholder();
    }
}
function onDrop(e) {
    e.preventDefault();
    const zone = e.currentTarget;
    dragCounters.set(zone, 0);
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
        const rect   = child.getBoundingClientRect();
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
        .then(d => { btn.classList.toggle('on', d.is_active); btn.classList.toggle('off', !d.is_active); });
}
function cloneWidget(id) {
    fetch(CLONE_URL.replace('{id}', id), { method:'POST', headers:{'X-CSRF-TOKEN':CSRF} }).then(() => location.reload());
}

// ── Modal ──
function openModal()  { document.getElementById('wm-overlay').classList.add('open'); }
function closeModal() { document.getElementById('wm-overlay').classList.remove('open'); }
function closeModalOnBackdrop(e) { if (e.target.id === 'wm-overlay') closeModal(); }

function openCreateModal(type, area) {
    modalMode = 'create'; modalWidgetId = null;
    document.getElementById('wm-title').textContent = 'Thêm Widget — ' + (TYPES[type]?.label ?? type);
    renderModalForm({ type, area, name:'', sort_order:0, is_active:true, config:{} });
    openModal();
}
function openEditModal(id) {
    modalMode = 'edit'; modalWidgetId = id;
    document.getElementById('wm-title').textContent = 'Chỉnh sửa Widget';
    document.getElementById('wm-body').innerHTML = '<p style="text-align:center;padding:30px;color:#94a3b8;">Đang tải...</p>';
    openModal();
    fetch(DATA_URL.replace('{id}', id)).then(r => r.json()).then(data => {
        document.getElementById('wm-title').textContent = 'Chỉnh sửa: ' + data.name;
        renderModalForm(data);
    });
}
function renderModalForm(data) {
    const areaOpts = Object.entries(AREAS).map(([k,v]) => `<option value="${k}" ${data.area===k?'selected':''}>${v}</option>`).join('');
    const typeOpts = Object.entries(TYPES).map(([k,t]) => `<option value="${k}" ${data.type===k?'selected':''}>${t.label}</option>`).join('');
    document.getElementById('wm-body').innerHTML = `
        <div style="display:flex;flex-direction:column;gap:14px;">
            ${modalMode==='create'
                ? `<div><label class="form-label">Loại widget</label>
                   <select id="wm-type" class="form-select" onchange="onModalTypeChange(this.value)">${typeOpts}</select></div>`
                : `<input type="hidden" id="wm-type" value="${escHtml(data.type)}">`}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                <div><label class="form-label">Khu vực</label><select id="wm-area" class="form-select">${areaOpts}</select></div>
                <div><label class="form-label">Thứ tự</label><input type="number" id="wm-sort" class="form-input" value="${data.sort_order??0}" min="0"></div>
            </div>
            <div style="display:flex;align-items:center;gap:8px;">
                <input type="checkbox" id="wm-active" value="1" ${data.is_active?'checked':''} style="width:16px;height:16px;cursor:pointer;">
                <label for="wm-active" class="form-label" style="margin:0;cursor:pointer;">Kích hoạt</label>
            </div>
            <div id="wm-config-fields"></div>
        </div>`;
    renderModalConfig(data.type, data.config ?? {});
}
function onModalTypeChange(type) { renderModalConfig(type, {}); }
function renderModalConfig(type, config) {
    const c = document.getElementById('wm-config-fields');
    if (!c) return;
    c.innerHTML = '';
    const t = TYPES[type];
    if (!t?.fields?.length) return;
    const h = document.createElement('p');
    h.style.cssText = 'font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.05em;margin:4px 0 10px;border-top:1px solid #e2e8f0;padding-top:12px;';
    h.textContent = 'Cấu hình — ' + t.label;
    c.appendChild(h);
    t.fields.forEach(f => c.appendChild(buildField(f, config[f.key] ?? f.default ?? null, 'config')));
}
function submitModal() {
    const fd = new FormData();
    fd.append('_token', CSRF);
    fd.append('type', document.getElementById('wm-type')?.value);
    fd.append('area', document.getElementById('wm-area')?.value);
    fd.append('sort_order', document.getElementById('wm-sort')?.value ?? 0);
    fd.append('is_active', document.getElementById('wm-active')?.checked ? 1 : 0);
    document.querySelectorAll('#wm-config-fields [name^="config"]').forEach(el => {
        if (el.type !== 'checkbox' || el.checked) fd.append(el.name, el.value);
    });
    const url = modalMode === 'create' ? STORE_URL : UPDATE_URL.replace('{id}', modalWidgetId);
    if (modalMode === 'edit') fd.append('_method', 'PUT');
    const btn = document.getElementById('wm-save');
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Đang lưu...';
    fetch(url, { method:'POST', body:fd })
        .then(r => { if (r.redirected || r.ok) location.reload(); else r.text().then(t => { throw t; }); })
        .catch(() => { btn.disabled=false; btn.innerHTML='<i class="fa-solid fa-floppy-disk"></i> Lưu'; alert('Có lỗi xảy ra.'); });
}

// ── Field builder ──
function buildField(field, value, prefix) {
    const wrap = document.createElement('div');
    wrap.style.marginBottom = '14px';
    const name = `${prefix}[${field.key}]`;
    if (field.type === 'repeater') { wrap.innerHTML = buildRepeater(field, value??[], prefix); initRepeater(wrap, field, prefix); return wrap; }
    if (field.type === 'toggle') {
        wrap.innerHTML = `<label style="display:flex;align-items:center;gap:8px;cursor:pointer;"><input type="hidden" name="${name}" value="0"><input type="checkbox" name="${name}" value="1" ${value?'checked':''} style="width:16px;height:16px;"><span style="font-size:13px;color:#64748b;">${field.label}</span></label>`;
        return wrap;
    }
    let input = '';
    if (field.type==='text'||field.type==='image') {
        input = `<input type="text" name="${name}" class="form-input" value="${escHtml(value??'')}" placeholder="${field.placeholder??''}">`;
        if (field.type==='image') input += `<p class="form-hint">URL ảnh hoặc chọn từ <a href="/admin/media" target="_blank" style="color:#2563eb;">Thư viện</a></p>`;
    } else if (field.type==='textarea') {
        input = `<textarea name="${name}" class="form-textarea" rows="3">${escHtml(value??'')}</textarea>`;
    } else if (field.type==='html') {
        input = `<textarea name="${name}" class="form-textarea" rows="6" style="font-family:monospace;font-size:13px;">${escHtml(value??'')}</textarea>`;
    } else if (field.type==='number') {
        input = `<input type="number" name="${name}" class="form-input" value="${value??''}">`;
    } else if (field.type==='category_select') {
        // Multi-select danh mục với tag UI
        const selectedIds = Array.isArray(value) ? value.map(String) : (value ? [String(value)] : []);
        const catItems = CATEGORIES.map(c => {
            const sel = selectedIds.includes(String(c.id));
            return `<div class="cat-opt ${sel?'selected':''}" data-id="${c.id}" onclick="toggleCatOpt(this,'${name}')">${escHtml(c.name)}</div>`;
        }).join('');
        // Hidden inputs cho các id đã chọn
        const hiddenInputs = selectedIds.map(id => `<input type="hidden" name="${name}[]" value="${id}" class="cat-hidden-input">`).join('');
        input = `<div class="cat-select-wrap" style="border:1.5px solid #e2e8f0;border-radius:8px;overflow:hidden;">
            <div class="cat-search-box" style="padding:6px 8px;border-bottom:1px solid #f1f5f9;">
                <input type="text" placeholder="Tìm danh mục..." oninput="filterCatOpts(this)"
                    style="width:100%;border:none;outline:none;font-size:12px;background:transparent;color:#374151;">
            </div>
            <div class="cat-opts-list" style="max-height:160px;overflow-y:auto;padding:4px;">
                ${catItems}
            </div>
            <div class="cat-tags" style="padding:6px 8px;border-top:1px solid #f1f5f9;display:flex;flex-wrap:wrap;gap:4px;min-height:32px;">
                ${selectedIds.map(id => {
                    const cat = CATEGORIES.find(c => String(c.id) === id);
                    return cat ? `<span class="cat-tag" data-id="${id}" style="background:#dbeafe;color:#1d4ed8;border-radius:4px;padding:2px 8px;font-size:11px;display:flex;align-items:center;gap:4px;">${escHtml(cat.name)}<i class="fa-solid fa-xmark" style="cursor:pointer;font-size:9px;" onclick="removeCatTag(this,'${name}')"></i></span>` : '';
                }).join('')}
            </div>
            <div style="display:none;">${hiddenInputs}</div>
        </div>`;
    } else if (field.type==='select') {
        const opts = Object.entries(field.options??{}).map(([k,v])=>`<option value="${k}" ${value==k?'selected':''}>${v}</option>`).join('');
        input = `<select name="${name}" class="form-select">${opts}</select>`;
    }
    wrap.innerHTML = `<label class="form-label">${field.label}</label>${input}`;
    return wrap;
}
function buildRepeater(field, rows, prefix) {
    const name = `${prefix}[${field.key}]`;
    return `<div class="repeater-wrap" data-field='${JSON.stringify(field)}' data-prefix="${prefix}">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
            <label class="form-label" style="margin:0;">${field.label}</label>
            <button type="button" class="btn btn-ghost btn-sm repeater-add" style="font-size:12px;"><i class="fa-solid fa-plus"></i> Thêm</button>
        </div>
        <div class="repeater-rows" style="display:flex;flex-direction:column;gap:8px;">
            ${(rows??[]).map((row,i)=>buildRepeaterRow(field,row,name,i)).join('')}
        </div></div>`;
}
function buildRepeaterRow(field, data, name, idx) {
    const subs = (field.fields??[]).map(sf => {
        const val = escHtml(data[sf.key]??sf.default??'');
        const inp = sf.type==='textarea'
            ? `<textarea name="${name}[${idx}][${sf.key}]" class="form-textarea" rows="2">${val}</textarea>`
            : `<input type="text" name="${name}[${idx}][${sf.key}]" class="form-input" value="${val}" placeholder="${sf.label}">`;
        return `<div style="margin-bottom:6px;"><label class="form-label" style="font-size:11px;">${sf.label}</label>${inp}</div>`;
    }).join('');
    return `<div class="repeater-row" style="background:#f8fafc;border:1.5px solid #e8ecf0;border-radius:8px;padding:12px;position:relative;">
        <button type="button" class="repeater-remove" style="position:absolute;top:7px;right:7px;width:20px;height:20px;border-radius:5px;border:none;background:#fee2e2;color:#dc2626;cursor:pointer;font-size:10px;display:flex;align-items:center;justify-content:center;"><i class="fa-solid fa-xmark"></i></button>
        ${subs}</div>`;
}
function initRepeater(wrap, field, prefix) {
    const name = `${prefix}[${field.key}]`;
    wrap.querySelector('.repeater-add')?.addEventListener('click', () => {
        const rows = wrap.querySelector('.repeater-rows');
        const div = document.createElement('div');
        div.innerHTML = buildRepeaterRow(field, {}, name, rows.querySelectorAll('.repeater-row').length);
        rows.appendChild(div.firstElementChild);
        bindRemove(rows);
    });
    bindRemove(wrap.querySelector('.repeater-rows'));
}
function bindRemove(rows) {
    rows.querySelectorAll('.repeater-remove').forEach(btn => { btn.onclick = () => btn.closest('.repeater-row').remove(); });
}
function toggleCatOpt(el, name) {
    const id = el.dataset.id;
    const wrap = el.closest('.cat-select-wrap');
    const hiddenBox = wrap.querySelector('div[style*="display:none"]');
    const tagsBox   = wrap.querySelector('.cat-tags');
    if (el.classList.contains('selected')) {
        // Deselect
        el.classList.remove('selected');
        hiddenBox.querySelector(`input[value="${id}"]`)?.remove();
        tagsBox.querySelector(`.cat-tag[data-id="${id}"]`)?.remove();
    } else {
        // Select
        el.classList.add('selected');
        const inp = document.createElement('input');
        inp.type = 'hidden'; inp.name = name + '[]'; inp.value = id; inp.className = 'cat-hidden-input';
        hiddenBox.appendChild(inp);
        const cat = CATEGORIES.find(c => String(c.id) === id);
        if (cat) {
            const tag = document.createElement('span');
            tag.className = 'cat-tag'; tag.dataset.id = id;
            tag.style.cssText = 'background:#dbeafe;color:#1d4ed8;border-radius:4px;padding:2px 8px;font-size:11px;display:flex;align-items:center;gap:4px;';
            tag.innerHTML = `${escHtml(cat.name)}<i class="fa-solid fa-xmark" style="cursor:pointer;font-size:9px;" onclick="removeCatTag(this,'${name}')"></i>`;
            tagsBox.appendChild(tag);
        }
    }
}
function removeCatTag(xBtn, name) {
    const tag  = xBtn.closest('.cat-tag');
    const id   = tag.dataset.id;
    const wrap = tag.closest('.cat-select-wrap');
    tag.remove();
    wrap.querySelector(`input[value="${id}"]`)?.remove();
    const opt = wrap.querySelector(`.cat-opt[data-id="${id}"]`);
    if (opt) opt.classList.remove('selected');
}
function filterCatOpts(input) {
    const q = input.value.toLowerCase();
    input.closest('.cat-select-wrap').querySelectorAll('.cat-opt').forEach(opt => {
        opt.style.display = opt.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
}
function escHtml(str) {
    return String(str??'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}
</script>
@endpush
