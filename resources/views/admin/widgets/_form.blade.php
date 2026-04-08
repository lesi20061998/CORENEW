@php
$isEdit     = !is_null($widget);
$curType    = $isEdit ? $widget->type : (request('type') ?: array_key_first($types));
$curArea    = $isEdit ? $widget->area : (request('area') ?: 'homepage');
$curConfig  = $isEdit ? ($widget->config ?? []) : [];
$typesJson  = json_encode($types);
@endphp

<form action="{{ $action }}" method="POST" id="widget-form">
    @csrf
    @if($method !== 'POST') @method($method) @endif

    <div style="display:grid;grid-template-columns:1fr 320px;gap:20px;align-items:start;">

        {{-- ═══ CỘT CHÍNH ═══ --}}
        <div style="display:flex;flex-direction:column;gap:16px;">

            {{-- Thông tin cơ bản --}}
            <div class="card">
                <div class="card-header"><span class="card-title">Thông tin widget</span></div>
                <div class="card-body" style="display:flex;flex-direction:column;gap:14px;">
                    <div>
                        <label class="form-label">Tên widget <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="name" class="form-input"
                               value="{{ old('name', $widget?->name) }}"
                               placeholder="vd: Slider trang chủ" required>
                    </div>
                    <div>
                        <label class="form-label">Loại widget <span style="color:#ef4444;">*</span></label>
                        <select name="type" id="widget-type" class="form-select" {{ $isEdit ? 'disabled' : '' }}
                                onchange="onTypeChange(this.value)">
                            @foreach($types as $key => $type)
                            <option value="{{ $key }}" {{ $curType === $key ? 'selected' : '' }}>
                                {{ $type['label'] }} — {{ $type['description'] }}
                            </option>
                            @endforeach
                        </select>
                        @if($isEdit)
                            <input type="hidden" name="type" value="{{ $widget->type }}">
                        @endif
                    </div>
                </div>
            </div>

            {{-- Dynamic Config Fields --}}
            <div class="card" id="config-card">
                <div class="card-header">
                    <span class="card-title" id="config-title">Cấu hình widget</span>
                    <span style="font-size:11.5px;color:#94a3b8;" id="config-type-badge"></span>
                </div>
                <div class="card-body" id="config-fields">
                    {{-- Render bởi JS --}}
                </div>
            </div>

        </div>

        {{-- ═══ CỘT PHẢI ═══ --}}
        <div style="display:flex;flex-direction:column;gap:16px;position:sticky;top:20px;">

            {{-- Publish --}}
            <div class="card">
                <div class="card-header"><span class="card-title">Xuất bản</span></div>
                <div class="card-body" style="display:flex;flex-direction:column;gap:14px;">
                    <div style="display:flex;align-items:center;justify-content:space-between;">
                        <label class="form-label" style="margin:0;">Kích hoạt</label>
                        <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1"
                                   {{ old('is_active', $widget?->is_active ?? true) ? 'checked' : '' }}
                                   style="width:16px;height:16px;cursor:pointer;">
                            <span style="font-size:13px;color:#64748b;">Hiển thị trên trang</span>
                        </label>
                    </div>
                    <div>
                        <label class="form-label">Khu vực hiển thị</label>
                        <select name="area" class="form-select">
                            @foreach($areas as $key => $area)
                            <option value="{{ $key }}" {{ $curArea === $key ? 'selected' : '' }}>
                                {{ $area['label'] }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Thứ tự</label>
                        <input type="number" name="sort_order" class="form-input"
                               value="{{ old('sort_order', $widget?->sort_order ?? 0) }}" min="0">
                    </div>
                    <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;">
                        <i class="fa-solid fa-floppy-disk"></i>
                        {{ $isEdit ? 'Cập nhật widget' : 'Tạo widget' }}
                    </button>
                </div>
            </div>

            {{-- Hướng dẫn --}}
            <div class="card" style="border-color:#fef3c7;">
                <div class="card-body" style="padding:14px;">
                    <p style="font-size:12.5px;font-weight:700;color:#92400e;margin-bottom:8px;">
                        <i class="fa-solid fa-lightbulb" style="color:#f59e0b;"></i> Hướng dẫn
                    </p>
                    <ul style="font-size:12px;color:#78350f;line-height:1.7;padding-left:16px;">
                        <li>Dùng <code>@widgetArea('homepage')</code> trong Blade để render</li>
                        <li>Tạo widget type mới: <code>php artisan widget:make TenWidget</code></li>
                        <li>Kéo thả để sắp xếp thứ tự trong trang danh sách</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
const TYPES      = @json($types);
const CUR_CONFIG = @json($curConfig);
const CUR_TYPE   = '{{ $curType }}';

document.addEventListener('DOMContentLoaded', () => renderFields(CUR_TYPE, CUR_CONFIG));

function onTypeChange(type) {
    renderFields(type, {});
}

function renderFields(type, config) {
    const typeData = TYPES[type];
    if (!typeData) return;

    document.getElementById('config-type-badge').textContent = typeData.label;
    const container = document.getElementById('config-fields');
    container.innerHTML = '';

    if (!typeData.fields || typeData.fields.length === 0) {
        container.innerHTML = '<p style="color:#94a3b8;font-size:13px;text-align:center;padding:20px 0;">Widget này không có cấu hình thêm.</p>';
        return;
    }

    typeData.fields.forEach(field => {
        container.appendChild(buildField(field, config[field.key] ?? field.default ?? null, 'config'));
    });
}

function buildField(field, value, prefix) {
    const wrap = document.createElement('div');
    wrap.style.cssText = 'margin-bottom:16px;';
    const name = `${prefix}[${field.key}]`;

    if (field.type === 'repeater') {
        wrap.innerHTML = buildRepeater(field, value ?? [], prefix);
        initRepeater(wrap, field, prefix);
        return wrap;
    }

    let input = '';
    if (field.type === 'text' || field.type === 'image') {
        const val = escHtml(value ?? '');
        const fieldId = `field_${name.replace(/[\[\]]/g, '_')}`;
        if (field.type === 'image') {
            input = `
                <div style="display:flex;gap:8px;align-items:center;">
                    <input type="text" id="${fieldId}" name="${name}" class="form-input" value="${val}" placeholder="${field.placeholder ?? 'URL ảnh hoặc chọn từ thư viện'}">
                    <button type="button" onclick="openMediaPicker('${fieldId}')"
                        style="flex-shrink:0;padding:0 14px;height:42px;background:#2563eb;color:#fff;border:none;border-radius:8px;font-size:11px;font-weight:700;cursor:pointer;white-space:nowrap;">
                        <i class="fa-solid fa-image"></i> Chọn
                    </button>
                </div>
                <div id="${fieldId}_preview" style="margin-top:6px;">
                    ${val ? `<img src="${val}" style="height:60px;border-radius:6px;object-fit:cover;border:1px solid #e2e8f0;" onerror="this.style.display='none'">` : ''}
                </div>`;
        } else {
            input = `<input type="text" id="${fieldId}" name="${name}" class="form-input" value="${val}" placeholder="${field.placeholder ?? ''}">`;
        }
    } else if (field.type === 'textarea') {
        input = `<textarea name="${name}" class="form-textarea" rows="3" placeholder="${field.placeholder ?? ''}">${escHtml(value ?? '')}</textarea>`;
    } else if (field.type === 'html') {
        input = `<textarea name="${name}" class="form-textarea" rows="8" style="font-family:monospace;font-size:13px;" placeholder="<div>HTML của bạn...</div>">${escHtml(value ?? '')}</textarea>`;
    } else if (field.type === 'number') {
        input = `<input type="number" name="${name}" class="form-input" value="${value ?? ''}" placeholder="${field.placeholder ?? ''}">`;
    } else if (field.type === 'select') {
        const opts = Object.entries(field.options ?? {}).map(([k, v]) =>
            `<option value="${k}" ${value == k ? 'selected' : ''}>${v}</option>`
        ).join('');
        input = `<select name="${name}" class="form-select">${opts}</select>`;
    } else if (field.type === 'toggle') {
        const checked = value ? 'checked' : '';
        input = `<label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
            <input type="hidden" name="${name}" value="0">
            <input type="checkbox" name="${name}" value="1" ${checked} style="width:16px;height:16px;">
            <span style="font-size:13px;color:#64748b;">${field.label}</span>
        </label>`;
        wrap.innerHTML = input;
        return wrap;
    }

    wrap.innerHTML = `
        <label class="form-label">${field.label}</label>
        ${input}
    `;
    return wrap;
}

function buildRepeater(field, rows, prefix) {
    const name = `${prefix}[${field.key}]`;
    return `
    <div class="repeater-wrap" data-field='${JSON.stringify(field)}' data-prefix="${prefix}">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;">
            <label class="form-label" style="margin:0;">${field.label}</label>
            <button type="button" class="btn btn-ghost btn-sm repeater-add" style="font-size:12px;">
                <i class="fa-solid fa-plus"></i> Thêm
            </button>
        </div>
        <div class="repeater-rows" style="display:flex;flex-direction:column;gap:10px;">
            ${(rows ?? []).map((row, i) => buildRepeaterRow(field, row, name, i)).join('')}
        </div>
    </div>`;
}

function buildRepeaterRow(field, data, name, idx) {
    const subFields = (field.fields ?? []).map(sf => {
        const val = escHtml(data[sf.key] ?? sf.default ?? '');
        const sfId = `field_${name}_${idx}_${sf.key}`.replace(/[\[\]]/g, '_');
        let inp = '';
        if (sf.type === 'textarea') {
            inp = `<textarea name="${name}[${idx}][${sf.key}]" class="form-textarea" rows="2">${val}</textarea>`;
        } else if (sf.type === 'select') {
            const opts = Object.entries(sf.options ?? {}).map(([k, v]) =>
                `<option value="${k}" ${data[sf.key] == k ? 'selected' : ''}>${v}</option>`
            ).join('');
            inp = `<select name="${name}[${idx}][${sf.key}]" class="form-select">${opts}</select>`;
        } else if (sf.type === 'image') {
            inp = `
                <div style="display:flex;gap:8px;align-items:center;">
                    <input type="text" id="${sfId}" name="${name}[${idx}][${sf.key}]" class="form-input" value="${val}" placeholder="${sf.label}">
                    <button type="button" onclick="openMediaPicker('${sfId}')"
                        style="flex-shrink:0;padding:0 12px;height:42px;background:#2563eb;color:#fff;border:none;border-radius:8px;font-size:11px;font-weight:700;cursor:pointer;white-space:nowrap;">
                        <i class="fa-solid fa-image"></i> Chọn
                    </button>
                </div>
                <div id="${sfId}_preview" style="margin-top:4px;">
                    ${val ? `<img src="${val}" style="height:50px;border-radius:6px;object-fit:cover;border:1px solid #e2e8f0;" onerror="this.style.display='none'">` : ''}
                </div>`;
        } else {
            inp = `<input type="text" id="${sfId}" name="${name}[${idx}][${sf.key}]" class="form-input" value="${val}" placeholder="${sf.label}">`;
        }
        return `<div style="margin-bottom:8px;"><label class="form-label" style="font-size:11.5px;">${sf.label}</label>${inp}</div>`;
    }).join('');

    return `<div class="repeater-row" style="background:#f8fafc;border:1.5px solid #e8ecf0;border-radius:10px;padding:14px;position:relative;">
        <button type="button" class="repeater-remove" style="position:absolute;top:8px;right:8px;width:22px;height:22px;border-radius:6px;border:none;background:#fee2e2;color:#dc2626;cursor:pointer;font-size:11px;display:flex;align-items:center;justify-content:center;">
            <i class="fa-solid fa-xmark"></i>
        </button>
        ${subFields}
    </div>`;
}

function initRepeater(wrap, field, prefix) {
    const name = `${prefix}[${field.key}]`;

    wrap.querySelector('.repeater-add')?.addEventListener('click', () => {
        const rows = wrap.querySelector('.repeater-rows');
        const idx  = rows.querySelectorAll('.repeater-row').length;
        const div  = document.createElement('div');
        div.innerHTML = buildRepeaterRow(field, {}, name, idx);
        rows.appendChild(div.firstElementChild);
        bindRemove(rows);
        reindexRows(rows, name, field);
    });

    bindRemove(wrap.querySelector('.repeater-rows'));
}

function bindRemove(rows) {
    rows.querySelectorAll('.repeater-remove').forEach(btn => {
        btn.onclick = () => {
            const row = btn.closest('.repeater-row');
            const container = row.parentElement;
            const fieldWrap = container.closest('.repeater-wrap');
            const name = fieldWrap.dataset.prefix + '[' + JSON.parse(fieldWrap.dataset.field).key + ']';
            const fieldDef = JSON.parse(fieldWrap.dataset.field);
            row.remove();
            reindexRows(container, name, fieldDef);
        };
    });
}

function reindexRows(rows, name, field) {
    rows.querySelectorAll('.repeater-row').forEach((row, idx) => {
        row.querySelectorAll('input,textarea,select').forEach(el => {
            el.name = el.name.replace(/\[\d+\]/, `[${idx}]`);
            // Reindex id too so media picker can find the element
            if (el.id) {
                el.id = el.id.replace(/_\d+_/, `_${idx}_`);
            }
        });
        // Reindex preview divs and picker buttons
        row.querySelectorAll('[id$="_preview"]').forEach(el => {
            el.id = el.id.replace(/_\d+_/, `_${idx}_`);
        });
        row.querySelectorAll('button[onclick^="openMediaPicker"]').forEach(btn => {
            btn.setAttribute('onclick', btn.getAttribute('onclick').replace(/_\d+_/, `_${idx}_`));
        });
    });
}

function escHtml(str) {
    return String(str ?? '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}
</script>
@endpush
