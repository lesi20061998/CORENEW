@extends('admin.layouts.app')
@section('title', $module['title'] . ' — Cài đặt')
@section('page-title', $module['title'])
@section('page-subtitle', $module['description'])

@section('page-actions')
<a href="{{ route('admin.settings.index') }}" class="btn btn-secondary">
    <i class="fa-solid fa-arrow-left text-xs"></i> Quay lại
</a>
@endsection

@section('content')
@php
    $sections = $settings->groupBy('section');
@endphp

<form action="{{ route('admin.settings.group.update', $module['group']) }}" method="POST">
    @csrf @method('PUT')

    <div class="space-y-5 max-w-3xl">

        @forelse($sections as $sectionName => $sectionSettings)
        <div class="card">
            {{-- Section header --}}
            <div class="card-header flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
                    <i class="{{ $module['icon'] }} text-blue-500 text-sm"></i>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-800">{{ $sectionName ?: $module['title'] }}</p>
                    <p class="text-xs text-gray-400">{{ $sectionSettings->count() }} trường</p>
                </div>
            </div>

            {{-- Fields --}}
            <div class="card-body space-y-5">
                @foreach($sectionSettings as $setting)
                <div>
                    <label class="form-label">
                        {{ $setting->label ?: ucfirst(str_replace('_', ' ', $setting->key)) }}
                    </label>
                    @if($setting->description)
                        <p class="text-xs text-gray-400 mb-1.5">{{ $setting->description }}</p>
                    @endif

                    @if($setting->key === 'price_presets')
                        {{-- ── Price Presets Visual Editor ── --}}
                        @php $presets = json_decode($setting->value, true) ?: []; @endphp

                        <textarea name="settings[price_presets]" id="price-presets-json" style="display:none;">{{ $setting->value }}</textarea>

                        <div id="presets-list" style="display:flex;flex-direction:column;gap:8px;margin-bottom:10px;">
                            @foreach($presets as $preset)
                            <div class="preset-row" style="display:flex;align-items:center;gap:8px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:8px 10px;">
                                <span class="drag-handle" style="cursor:grab;color:#94a3b8;font-size:14px;flex-shrink:0;user-select:none;display:flex;align-items:center;justify-content:center;width:20px;" title="Kéo để sắp xếp"><i class="fa-solid fa-grip-vertical"></i></span>
                                <input type="text" class="preset-label form-input" style="flex:2;min-width:0;"
                                       value="{{ $preset['label'] }}" placeholder="Tên nút (vd: Dưới 200k)">
                                <div style="display:flex;align-items:center;gap:6px;flex:3;min-width:0;">
                                    <div style="position:relative;flex:1;">
                                        <span style="position:absolute;left:8px;top:50%;transform:translateY(-50%);font-size:11px;color:#94a3b8;pointer-events:none;">từ ₫</span>
                                        <input type="number" class="preset-min form-input" style="padding-left:34px;"
                                               value="{{ $preset['min'] }}" min="0" step="1000">
                                    </div>
                                    <span style="color:#cbd5e1;flex-shrink:0;">—</span>
                                    <div style="position:relative;flex:1;">
                                        <span style="position:absolute;left:8px;top:50%;transform:translateY(-50%);font-size:11px;color:#94a3b8;pointer-events:none;">đến ₫</span>
                                        <input type="number" class="preset-max form-input" style="padding-left:34px;"
                                               value="{{ $preset['max'] }}" min="0" step="1000" placeholder="0=∞">
                                    </div>
                                </div>
                                <button type="button" class="btn-remove-preset"
                                        style="flex-shrink:0;width:30px;height:30px;border-radius:6px;border:1px solid #fca5a5;background:#fff5f5;color:#ef4444;font-size:13px;line-height:1;cursor:pointer;display:flex;align-items:center;justify-content:center;"><i class="fa-solid fa-trash-can"></i></button>
                            </div>
                            @endforeach
                        </div>

                        <button type="button" id="btn-add-preset"
                                style="display:inline-flex;align-items:center;gap:6px;padding:7px 14px;border-radius:8px;border:1.5px dashed #93c5fd;background:#eff6ff;color:#3b82f6;font-size:13px;font-weight:600;cursor:pointer;margin-bottom:14px;">
                            + Thêm mốc giá
                        </button>

                        <div>
                            <p style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#94a3b8;margin-bottom:6px;">Xem trước — khách hàng sẽ thấy:</p>
                            <div id="presets-preview" style="display:flex;flex-wrap:wrap;gap:6px;padding:12px;background:#f1f5f9;border-radius:8px;min-height:38px;"></div>
                        </div>

                        <script>
                        (function(){
                            const list    = document.getElementById('presets-list');
                            const jsonEl  = document.getElementById('price-presets-json');
                            const preview = document.getElementById('presets-preview');

                            function fmt(v) {
                                v = parseInt(v)||0;
                                if (!v) return '∞';
                                if (v>=1000000) return (v/1000000).toFixed(v%1000000?1:0).replace('.0','')+'tr';
                                if (v>=1000)    return (v/1000).toFixed(v%1000?1:0).replace('.0','')+'k';
                                return v+'';
                            }

                            function sync() {
                                const data = [];
                                list.querySelectorAll('.preset-row').forEach(row => {
                                    const label = row.querySelector('.preset-label').value.trim();
                                    const min   = parseInt(row.querySelector('.preset-min').value)||0;
                                    const max   = parseInt(row.querySelector('.preset-max').value)||0;
                                    if (label) data.push({label,min,max});
                                });
                                jsonEl.value = JSON.stringify(data);
                                preview.innerHTML = data.length
                                    ? data.map(p=>`<span style="padding:5px 14px;border-radius:20px;border:1.5px solid #3b82f6;background:#fff;font-size:13px;font-weight:600;color:#1d4ed8;white-space:nowrap;">${p.label} <span style="font-weight:400;color:#64748b;font-size:11px;">${fmt(p.min)}–${fmt(p.max)}</span></span>`).join('')
                                    : '<span style="font-size:12px;color:#94a3b8;">Chưa có mốc nào</span>';
                            }

                            function newRow() {
                                const d = document.createElement('div');
                                d.className = 'preset-row';
                                d.style.cssText = 'display:flex;align-items:center;gap:8px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:8px 10px;';
                                d.innerHTML = `
                                    <span class="drag-handle" style="cursor:grab;color:#94a3b8;font-size:14px;flex-shrink:0;user-select:none;"><i class="fa-solid fa-grip-vertical"></i></span>
                                    <input type="text" class="preset-label form-input" style="flex:2;min-width:0;" placeholder="Tên nút (vd: Dưới 200k)">
                                    <div style="display:flex;align-items:center;gap:6px;flex:3;min-width:0;">
                                        <div style="position:relative;flex:1;">
                                            <span style="position:absolute;left:8px;top:50%;transform:translateY(-50%);font-size:11px;color:#94a3b8;pointer-events:none;">từ ₫</span>
                                            <input type="number" class="preset-min form-input" style="padding-left:34px;" value="0" min="0" step="1000">
                                        </div>
                                        <span style="color:#cbd5e1;flex-shrink:0;">—</span>
                                        <div style="position:relative;flex:1;">
                                            <span style="position:absolute;left:8px;top:50%;transform:translateY(-50%);font-size:11px;color:#94a3b8;pointer-events:none;">đến ₫</span>
                                            <input type="number" class="preset-max form-input" style="padding-left:34px;" value="0" min="0" step="1000" placeholder="0=∞">
                                        </div>
                                    </div>
                                    <button type="button" class="btn-remove-preset"
                                            style="flex-shrink:0;width:30px;height:30px;border-radius:6px;border:1px solid #fca5a5;background:#fff5f5;color:#ef4444;font-size:13px;line-height:1;cursor:pointer;display:flex;align-items:center;justify-content:center;"><i class="fa-solid fa-trash-can"></i></button>`;
                                list.appendChild(d);
                                d.querySelector('.preset-label').focus();
                                sync();
                            }

                            list.addEventListener('input', sync);
                            list.addEventListener('click', e => {
                                if (e.target.classList.contains('btn-remove-preset')) {
                                    e.target.closest('.preset-row').remove(); sync();
                                }
                            });
                            document.getElementById('btn-add-preset').addEventListener('click', newRow);
                            document.querySelector('form').addEventListener('submit', sync);

                            // Drag-to-reorder
                            let dragging = null;
                            list.addEventListener('mousedown', e => {
                                if (e.target.closest('.drag-handle')) {
                                    dragging = e.target.closest('.preset-row');
                                    dragging.setAttribute('draggable','true');
                                }
                            });
                            list.addEventListener('dragstart', e => { setTimeout(()=>dragging&&(dragging.style.opacity='.4'),0); });
                            list.addEventListener('dragover', e => {
                                e.preventDefault();
                                const over = e.target.closest('.preset-row');
                                if (over && over !== dragging) {
                                    const after = e.clientY > over.getBoundingClientRect().top + over.offsetHeight/2;
                                    list.insertBefore(dragging, after ? over.nextSibling : over);
                                }
                            });
                            list.addEventListener('dragend', () => {
                                if (dragging) { dragging.style.opacity='1'; dragging.removeAttribute('draggable'); dragging=null; }
                                sync();
                            });

                            sync();
                        })();
                        </script>

                    @elseif($setting->type === 'boolean')
                        <input type="hidden" name="settings[{{ $setting->key }}]" value="0">
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="settings[{{ $setting->key }}]" value="1"
                                   {{ $setting->value ? 'checked' : '' }}
                                   class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm text-gray-600">Bật</span>
                        </label>

                    @elseif($setting->type === 'textarea')
                        <textarea name="settings[{{ $setting->key }}]" rows="4"
                                  class="form-input resize-none">{{ $setting->value }}</textarea>

                    @elseif($setting->type === 'image')
                        @php $settingInputId = 'setting_img_' . $setting->key; @endphp
                        <div style="display:flex;gap:8px;align-items:center;">
                            <input type="text" name="settings[{{ $setting->key }}]"
                                   id="{{ $settingInputId }}"
                                   value="{{ $setting->value }}"
                                   placeholder="URL hình ảnh..."
                                   class="form-input"
                                   oninput="updateImgPreview('{{ $settingInputId }}_preview', this.value)">
                            <button type="button"
                                    onclick="openMediaPicker('{{ $settingInputId }}', function(url){ updateImgPreview('{{ $settingInputId }}_preview', url); })"
                                    style="flex-shrink:0;padding:8px 14px;background:#f1f5f9;border:1px solid #e2e8f0;border-radius:8px;font-size:13px;font-weight:600;color:#374151;cursor:pointer;white-space:nowrap;display:flex;align-items:center;gap:6px;"
                                    onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">
                                <i class="fa-solid fa-images" style="color:#3b82f6;"></i> Chọn
                            </button>
                        </div>
                        <div id="{{ $settingInputId }}_preview" style="margin-top:6px;">
                            @if($setting->value)
                                <img src="{{ $setting->value }}" style="height:60px;border-radius:8px;object-fit:cover;border:1px solid #e2e8f0;">
                            @endif
                        </div>

                    @elseif($setting->type === 'color')
                        <div class="flex items-center gap-2">
                            <input type="color"
                                   value="{{ $setting->value ?: '#000000' }}"
                                   class="w-10 h-10 rounded-lg border border-gray-200 cursor-pointer p-0.5 flex-shrink-0"
                                   oninput="document.getElementById('color_text_{{ $setting->key }}').value=this.value">
                            <input type="text" id="color_text_{{ $setting->key }}"
                                   name="settings[{{ $setting->key }}]"
                                   value="{{ $setting->value }}"
                                   class="form-input w-36"
                                   oninput="this.previousElementSibling.value=this.value">
                        </div>

                    @elseif($setting->type === 'json' && is_array($setting->value) && $setting->key !== 'price_presets')
                        <div class="space-y-2.5 p-4 bg-slate-50 rounded-xl border border-slate-200">
                            @foreach($setting->value as $subKey => $subValue)
                            <div class="flex items-center gap-2">
                                <div class="w-32 flex-shrink-0">
                                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider text-right block">{{ str_replace('_', ' ', $subKey) }}</span>
                                </div>
                                <input type="text" name="settings[{{ $setting->key }}][{{ $subKey }}]"
                                       value="{{ $subValue }}"
                                       class="form-input flex-1 !text-sm"
                                       placeholder="Nhập giá trị cho {{ $subKey }}...">
                            </div>
                            @endforeach
                        </div>

                    @elseif($setting->type === 'select' && $setting->options)
                        <select name="settings[{{ $setting->key }}]" class="form-select">
                            @foreach(json_decode($setting->options, true) ?? [] as $val => $lbl)
                                <option value="{{ $val }}" {{ $setting->value == $val ? 'selected' : '' }}>{{ $lbl }}</option>
                            @endforeach
                        </select>

                    @else
                        <input type="text" name="settings[{{ $setting->key }}]"
                               value="{{ $setting->value }}"
                               class="form-input">
                    @endif
                </div>
                @endforeach

                {{-- ── VietQR Live Preview ── --}}
                @if($sectionName === 'VietQR')
                @php
                    $qBankId  = $sectionSettings->firstWhere('key','vietqr_bank_id')?->value ?? '';
                    $qAccNo   = $sectionSettings->firstWhere('key','vietqr_account_no')?->value ?? '';
                    $qAccName = $sectionSettings->firstWhere('key','vietqr_account_name')?->value ?? '';
                    $qTpl     = $sectionSettings->firstWhere('key','vietqr_template')?->value ?? 'compact2';
                @endphp
                <div style="border-top:1px solid #f1f5f9;padding-top:20px;margin-top:4px;">
                    <p style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#94a3b8;margin-bottom:12px;">
                        Xem trước QR — cập nhật realtime
                    </p>
                    <div style="display:flex;gap:24px;align-items:flex-start;flex-wrap:wrap;">
                        {{-- QR Image --}}
                        <div id="vietqr-preview-wrap" style="flex-shrink:0;text-align:center;">
                            <div id="vietqr-img-box" style="width:180px;height:180px;border-radius:12px;border:2px dashed #e2e8f0;display:flex;align-items:center;justify-content:center;background:#f8fafc;overflow:hidden;">
                                <span id="vietqr-placeholder" style="font-size:12px;color:#94a3b8;">Nhập thông tin<br>để xem QR</span>
                                <img id="vietqr-img" src="" alt="VietQR" style="display:none;width:100%;height:100%;object-fit:contain;border-radius:10px;">
                            </div>
                            <p id="vietqr-acc-label" style="font-size:11px;color:#64748b;margin-top:6px;"></p>
                        </div>
                        {{-- Info --}}
                        <div style="flex:1;min-width:200px;">
                            <p style="font-size:12px;color:#64748b;margin-bottom:8px;">URL được tạo:</p>
                            <code id="vietqr-url-display" style="font-size:11px;word-break:break-all;color:#3b82f6;background:#eff6ff;padding:8px;border-radius:6px;display:block;min-height:36px;"></code>
                            <p style="font-size:11px;color:#94a3b8;margin-top:8px;">
                                Số tiền và nội dung CK sẽ được điền tự động theo từng đơn hàng.
                            </p>
                        </div>
                    </div>
                </div>
                <script>
                (function(){
                    // Map setting keys → input elements
                    function getVal(key) {
                        const el = document.querySelector('[name="settings[' + key + ']"]');
                        return el ? el.value.trim() : '';
                    }

                    const img      = document.getElementById('vietqr-img');
                    const ph       = document.getElementById('vietqr-placeholder');
                    const accLabel = document.getElementById('vietqr-acc-label');
                    const urlDisp  = document.getElementById('vietqr-url-display');

                    function update() {
                        const bankId  = getVal('vietqr_bank_id');
                        const accNo   = getVal('vietqr_account_no');
                        const accName = getVal('vietqr_account_name');
                        const tpl     = getVal('vietqr_template') || 'compact2';
                        const desc    = getVal('vietqr_description') || 'Thanh toan don hang';

                        if (bankId && accNo) {
                            const url = 'https://img.vietqr.io/image/'
                                + encodeURIComponent(bankId) + '-'
                                + encodeURIComponent(accNo) + '-'
                                + tpl + '.png'
                                + '?amount=0'
                                + '&addInfo=' + encodeURIComponent(desc)
                                + (accName ? '&accountName=' + encodeURIComponent(accName) : '');

                            img.src = url;
                            img.style.display = 'block';
                            ph.style.display  = 'none';
                            urlDisp.textContent = url;
                            accLabel.textContent = accName ? accName + ' — ' + accNo : accNo;
                        } else {
                            img.style.display = 'none';
                            ph.style.display  = 'block';
                            urlDisp.textContent = '';
                            accLabel.textContent = '';
                        }
                    }

                    // Watch all VietQR inputs
                    ['vietqr_bank_id','vietqr_account_no','vietqr_account_name','vietqr_template','vietqr_description']
                        .forEach(key => {
                            const el = document.querySelector('[name="settings[' + key + ']"]');
                            if (el) el.addEventListener('input', update);
                            if (el && el.tagName === 'SELECT') el.addEventListener('change', update);
                        });

                    update();
                })();
                </script>
                @endif

            </div>
        </div>
        @empty
        <div class="card card-body text-center py-10">
            <p class="text-sm text-gray-400">Chưa có cài đặt nào trong nhóm này.</p>
        </div>
        @endforelse

        @if($settings->isNotEmpty())
        <div class="flex items-center gap-3 pt-1">
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-check text-xs"></i> Lưu cài đặt
            </button>
            <a href="{{ route('admin.settings.index') }}" class="btn btn-secondary">Hủy</a>
        </div>
        @endif

    </div>
</form>
@endsection
