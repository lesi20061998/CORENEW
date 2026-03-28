{{--
    Media Picker Modal — dùng chung toàn admin
    Cách dùng:
      @include('admin.components.media-picker')
    Mở modal:
      openMediaPicker(targetInputId, callback)
      - targetInputId: id của <input> sẽ nhận URL
      - callback (optional): fn(url, item) gọi sau khi chọn
--}}
<div id="media-picker-modal"
     style="display:none;position:fixed;inset:0;z-index:999999;background:rgba(0,0,0,.55);align-items:center;justify-content:center;padding:16px;">
    <div style="background:#fff;border-radius:18px;width:100%;max-width:900px;max-height:90vh;display:flex;flex-direction:column;box-shadow:0 24px 80px rgba(0,0,0,.25);overflow:hidden;">

        {{-- Header --}}
        <div style="padding:16px 20px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;gap:12px;flex-shrink:0;">
            <div style="width:36px;height:36px;border-radius:10px;background:#eff6ff;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="fa-solid fa-images" style="color:#3b82f6;font-size:15px;"></i>
            </div>
            <div style="flex:1;">
                <p style="font-size:14px;font-weight:700;color:#0f172a;">Thư viện Media</p>
                <p style="font-size:12px;color:#94a3b8;">Chọn ảnh hoặc tải lên mới</p>
            </div>
            <button onclick="closeMediaPicker()" style="background:none;border:none;cursor:pointer;color:#94a3b8;font-size:20px;padding:4px;">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        {{-- Body --}}
        <div style="display:flex;flex:1;min-height:0;overflow:hidden;">

            {{-- Sidebar folders --}}
            <div style="width:180px;min-width:180px;border-right:1px solid #f1f5f9;overflow-y:auto;padding:8px 0;flex-shrink:0;">
                <div style="padding:8px 12px 4px;font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;">Thư mục</div>
                <button onclick="mpSetFolder('')"
                        id="mp-folder-all"
                        style="width:100%;text-align:left;padding:8px 14px;border:none;background:transparent;cursor:pointer;font-size:13px;font-weight:500;color:#374151;display:flex;align-items:center;gap:8px;border-radius:0;transition:background .1s;"
                        onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background=mpCurrentFolder===''?'#eff6ff':'transparent'">
                    <i class="fa-solid fa-layer-group" style="color:#64748b;font-size:12px;width:14px;"></i> Tất cả
                </button>
                <div id="mp-folder-list"></div>
                <div style="padding:8px 12px 4px;margin-top:4px;border-top:1px solid #f8fafc;">
                    <button onclick="mpShowNewFolder()" style="width:100%;text-align:left;padding:7px 14px;border:1px dashed #cbd5e1;background:transparent;cursor:pointer;font-size:12px;color:#64748b;border-radius:8px;display:flex;align-items:center;gap:6px;transition:all .1s;"
                            onmouseover="this.style.borderColor='#3b82f6';this.style.color='#3b82f6'" onmouseout="this.style.borderColor='#cbd5e1';this.style.color='#64748b'">
                        <i class="fa-solid fa-folder-plus" style="font-size:11px;"></i> Tạo thư mục
                    </button>
                    <div id="mp-new-folder-form" style="display:none;margin-top:8px;">
                        <input id="mp-new-folder-input" type="text" placeholder="Tên thư mục..."
                               style="width:100%;padding:6px 10px;border:1px solid #e2e8f0;border-radius:7px;font-size:12px;outline:none;"
                               onkeydown="if(event.key==='Enter'){mpCreateFolder();event.preventDefault();}">
                        <div style="display:flex;gap:6px;margin-top:6px;">
                            <button onclick="mpCreateFolder()" style="flex:1;padding:5px;background:#3b82f6;color:#fff;border:none;border-radius:6px;font-size:12px;cursor:pointer;">Tạo</button>
                            <button onclick="document.getElementById('mp-new-folder-form').style.display='none'" style="flex:1;padding:5px;background:#f1f5f9;color:#64748b;border:none;border-radius:6px;font-size:12px;cursor:pointer;">Hủy</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Main area --}}
            <div style="flex:1;display:flex;flex-direction:column;min-width:0;overflow:hidden;">

                {{-- Toolbar --}}
                <div style="padding:12px 16px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;gap:10px;flex-shrink:0;">
                    <div style="flex:1;position:relative;">
                        <i class="fa-solid fa-magnifying-glass" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:#94a3b8;font-size:12px;"></i>
                        <input id="mp-search" type="text" placeholder="Tìm kiếm..."
                               oninput="mpSearch(this.value)"
                               style="width:100%;padding:7px 10px 7px 30px;border:1px solid #e2e8f0;border-radius:8px;font-size:13px;outline:none;">
                    </div>
                    <label style="display:flex;align-items:center;gap:7px;padding:7px 14px;background:#3b82f6;color:#fff;border-radius:8px;cursor:pointer;font-size:13px;font-weight:600;white-space:nowrap;flex-shrink:0;">
                        <i class="fa-solid fa-upload" style="font-size:12px;"></i> Tải lên
                        <input type="file" id="mp-upload-input" accept="image/*,application/pdf" multiple
                               style="display:none;" onchange="mpUploadFiles(this.files)">
                    </label>
                </div>

                {{-- Upload progress --}}
                <div id="mp-upload-progress" style="display:none;padding:10px 16px;background:#eff6ff;border-bottom:1px solid #dbeafe;font-size:13px;color:#2563eb;display:flex;align-items:center;gap:8px;">
                    <i class="fa-solid fa-spinner fa-spin"></i>
                    <span id="mp-upload-status">Đang tải lên...</span>
                </div>

                {{-- Grid --}}
                <div id="mp-grid" style="flex:1;overflow-y:auto;padding:14px;display:grid;grid-template-columns:repeat(auto-fill,minmax(110px,1fr));gap:10px;align-content:start;">
                    <div style="grid-column:1/-1;text-align:center;padding:40px;color:#94a3b8;">
                        <i class="fa-solid fa-spinner fa-spin" style="font-size:24px;"></i>
                    </div>
                </div>

            </div>
        </div>

        {{-- Footer --}}
        <div style="padding:12px 20px;border-top:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;flex-shrink:0;background:#fafbfc;">
            <span id="mp-selected-info" style="font-size:13px;color:#64748b;">Chưa chọn ảnh nào</span>
            <div style="display:flex;gap:8px;">
                <button onclick="closeMediaPicker()" style="padding:8px 18px;border:1px solid #e2e8f0;background:#fff;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;color:#374151;">Hủy</button>
                <button id="mp-confirm-btn" onclick="mpConfirmSelect()" disabled
                        style="padding:8px 20px;background:#3b82f6;color:#fff;border:none;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;opacity:.5;transition:opacity .15s;">
                    <i class="fa-solid fa-check" style="margin-right:6px;"></i>Chọn ảnh này
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// ── Media Picker Global State ──────────────────────────────────────────
let mpTargetInput   = null;
let mpCallback      = null;
let mpCurrentFolder = '';
let mpSelectedItems = []; // Array for multiple selection
let mpSearchTimer   = null;
let mpFolders       = [];
let mpAllowMultiple = false;

function openMediaPicker(targetInputId, callback, multiple = false) {
    mpTargetInput   = targetInputId ? document.getElementById(targetInputId) : null;
    mpCallback      = callback || null;
    mpAllowMultiple = multiple;
    mpSelectedItems = [];
    
    document.getElementById('mp-confirm-btn').disabled = true;
    document.getElementById('mp-confirm-btn').style.opacity = '.5';
    document.getElementById('mp-selected-info').textContent = 'Chưa chọn ảnh nào';
    document.getElementById('mp-search').value = '';
    document.getElementById('media-picker-modal').style.display = 'flex';
    mpLoadMedia();
}

function closeMediaPicker() {
    document.getElementById('media-picker-modal').style.display = 'none';
    mpSelectedItems = [];
}

function mpSetFolder(folder) {
    mpCurrentFolder = folder;
    mpRenderFolderList();
    mpLoadMedia();
}

function mpSearch(val) {
    clearTimeout(mpSearchTimer);
    mpSearchTimer = setTimeout(() => mpLoadMedia(), 300);
}

function mpLoadMedia() {
    const grid   = document.getElementById('mp-grid');
    const search = document.getElementById('mp-search').value;
    grid.innerHTML = '<div style="grid-column:1/-1;text-align:center;padding:40px;color:#94a3b8;"><i class="fa-solid fa-spinner fa-spin" style="font-size:24px;"></i></div>';

    const params = new URLSearchParams({ folder: mpCurrentFolder, search });
    fetch("{{ route('admin.media.picker') }}?" + params, {
        credentials: 'same-origin',
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || '' }
    })
    .then(r => r.json())
    .then(data => {
        mpFolders = data.folders || [];
        mpRenderFolderList();
        mpRenderGrid(data.items || []);
    })
    .catch(() => {
        grid.innerHTML = '<div style="grid-column:1/-1;text-align:center;padding:40px;color:#ef4444;">Lỗi tải dữ liệu</div>';
    });
}

function mpRenderFolderList() {
    const list = document.getElementById('mp-folder-list');
    const allBtn = document.getElementById('mp-folder-all');
    allBtn.style.background = mpCurrentFolder === '' ? '#eff6ff' : 'transparent';
    allBtn.style.color      = mpCurrentFolder === '' ? '#2563eb' : '#374151';
    allBtn.style.fontWeight = mpCurrentFolder === '' ? '700' : '500';

    list.innerHTML = mpFolders.map(f => `
        <button onclick="mpSetFolder('${f.replace(/'/g,"\\\\'")}')"
                style="width:100%;text-align:left;padding:8px 14px;border:none;
                       background:${mpCurrentFolder===f?'#eff6ff':'transparent'};
                       cursor:pointer;font-size:13px;font-weight:${mpCurrentFolder===f?'700':'500'};
                       color:${mpCurrentFolder===f?'#2563eb':'#374151'};
                       display:flex;align-items:center;gap:8px;transition:background .1s;"
                onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='${mpCurrentFolder===f?'#eff6ff':'transparent'}'">
            <i class="fa-solid fa-folder" style="color:${mpCurrentFolder===f?'#3b82f6':'#94a3b8'};font-size:12px;width:14px;"></i>
            <span style="flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${f}</span>
        </button>
    `).join('');
}

function mpRenderGrid(items) {
    const grid = document.getElementById('mp-grid');
    if (!items.length) {
        grid.innerHTML = `<div style="grid-column:1/-1;text-align:center;padding:50px 20px;color:#94a3b8;">
            <i class="fa-solid fa-images" style="font-size:36px;opacity:.2;display:block;margin-bottom:12px;"></i>
            <p style="font-size:13px;font-weight:600;">Thư mục trống</p>
            <p style="font-size:12px;margin-top:4px;">Tải lên ảnh để bắt đầu</p>
        </div>`;
        return;
    }

    grid.innerHTML = items.map(item => {
        const isImg = item.mime && item.mime.startsWith('image/');
        const thumb = isImg
            ? `<img src="${item.url}" style="width:100%;height:100%;object-fit:cover;display:block;" loading="lazy" onerror="this.style.display='none'">`
            : `<div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;"><i class="fa-solid fa-file-pdf" style="font-size:28px;color:#f87171;"></i></div>`;

        const isSelected = mpSelectedItems.some(i => i.id === item.id);

        return `<div class="mp-item" data-id="${item.id}" data-url="${item.url}"
                     onclick="mpSelectItem(this, ${JSON.stringify(item).replace(/"/g,'&quot;')})"
                     style="border:2px solid ${isSelected?'#3b82f6':'#e8ecf0'}; border-radius:10px; overflow:hidden; cursor:pointer; transition:all .15s; background:#fff; position:relative; ${isSelected?'box-shadow:0 0 0 3px rgba(59,130,246,.2);':''}">
            <div style="aspect-ratio:1;background:#f8fafc;overflow:hidden;position:relative;">
                ${thumb}
                ${isSelected ? `<div style="position:absolute;top:5px;right:5px;width:20px;height:20px;background:#3b82f6;color:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:10px;"><i class="fa-solid fa-check"></i></div>` : ''}
            </div>
            <div style="padding:6px 8px;">
                <p style="font-size:11px;font-weight:600;color:#374151;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${item.name}</p>
                <p style="font-size:10px;color:#94a3b8;">${(item.size/1024).toFixed(1)} KB${item.w ? ' · '+item.w+'×'+item.h : ''}</p>
            </div>
        </div>`;
    }).join('');
}

function mpSelectItem(el, item) {
    if (!mpAllowMultiple) {
        // Single mode: clear others
        document.querySelectorAll('.mp-item').forEach(i => {
            i.style.borderColor = '#e8ecf0';
            i.style.boxShadow   = 'none';
            const check = i.querySelector('div[style*="top:5px"]');
            if (check) check.remove();
        });
        mpSelectedItems = [item];
        el.style.borderColor = '#3b82f6';
        el.style.boxShadow   = '0 0 0 3px rgba(59,130,246,.2)';
    } else {
        // Multiple mode: toggle
        const idx = mpSelectedItems.findIndex(i => i.id === item.id);
        if (idx > -1) {
            mpSelectedItems.splice(idx, 1);
            el.style.borderColor = '#e8ecf0';
            el.style.boxShadow   = 'none';
            const check = el.querySelector('div[style*="top:5px"]');
            if (check) check.remove();
        } else {
            mpSelectedItems.push(item);
            el.style.borderColor = '#3b82f6';
            el.style.boxShadow   = '0 0 0 3px rgba(59,130,246,.2)';
            const checkIcon = document.createElement('div');
            checkIcon.style = "position:absolute;top:5px;right:5px;width:20px;height:20px;background:#3b82f6;color:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:10px;";
            checkIcon.innerHTML = '<i class="fa-solid fa-check"></i>';
            el.querySelector('div').appendChild(checkIcon);
        }
    }

    const btn = document.getElementById('mp-confirm-btn');
    btn.disabled = mpSelectedItems.length === 0;
    btn.style.opacity = mpSelectedItems.length === 0 ? '.5' : '1';
    document.getElementById('mp-selected-info').textContent = 
        mpSelectedItems.length > 0 ? `Đã chọn ${mpSelectedItems.length} mục` : 'Chưa chọn ảnh nào';
}

function mpConfirmSelect() {
    if (mpSelectedItems.length === 0) return;

    if (mpAllowMultiple) {
        const urls = mpSelectedItems.map(i => i.url);
        if (mpCallback) mpCallback(urls, mpSelectedItems);
    } else {
        const item = mpSelectedItems[0];
        const url  = item.url;
        if (mpTargetInput) {
            mpTargetInput.value = url;
            mpTargetInput.dispatchEvent(new Event('input', { bubbles: true }));
            mpTargetInput.dispatchEvent(new Event('change', { bubbles: true }));
        }
        if (mpCallback) mpCallback(url, item);
    }

    closeMediaPicker();
}

// ── Upload ──────────────────────────────────────────────────────────────
async function mpUploadFiles(files) {
    if (!files.length) return;
    const progress = document.getElementById('mp-upload-progress');
    const status   = document.getElementById('mp-upload-status');
    progress.style.display = 'flex';

    for (let i = 0; i < files.length; i++) {
        status.textContent = `Đang tải lên ${i+1}/${files.length}: ${files[i].name}`;
        const fd = new FormData();
        fd.append('file', files[i]);
        fd.append('folder', mpCurrentFolder || 'Chung');
        fd.append('_token', document.querySelector('meta[name=csrf-token]')?.content || '');

        try {
            const res = await fetch("{{ route('admin.media.store') }}", { method: 'POST', body: fd });
            if (!res.ok) throw new Error('Upload failed');
        } catch(e) {
            status.textContent = `Lỗi: ${files[i].name}`;
        }
    }

    progress.style.display = 'none';
    document.getElementById('mp-upload-input').value = '';
    mpLoadMedia();
}

// ── Create Folder ───────────────────────────────────────────────────────
function mpShowNewFolder() {
    const form = document.getElementById('mp-new-folder-form');
    form.style.display = form.style.display === 'none' ? 'block' : 'none';
    if (form.style.display === 'block') {
        document.getElementById('mp-new-folder-input').focus();
    }
}

function mpCreateFolder() {
    const input = document.getElementById('mp-new-folder-input');
    const name  = input.value.trim();
    if (!name) return;

    fetch("{{ route('admin.media.folder') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || '',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ name })
    })
    .then(r => r.json())
    .then(data => {
        if (!mpFolders.includes(data.folder)) mpFolders.push(data.folder);
        mpRenderFolderList();
        input.value = '';
        document.getElementById('mp-new-folder-form').style.display = 'none';
        mpSetFolder(data.folder);
    });
}

// Close on backdrop click
document.getElementById('media-picker-modal').addEventListener('click', function(e) {
    if (e.target === this) closeMediaPicker();
});
</script>
