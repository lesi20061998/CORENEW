@extends('admin.layouts.app')
@section('title', $type === 'product' ? 'Danh mục sản phẩm' : 'Danh mục bài viết')
@section('page-title', $type === 'product' ? 'Danh mục sản phẩm' : 'Danh mục bài viết')
@section('page-subtitle', 'Quản lý danh mục, sub danh mục, nội dung SEO')

@php use Illuminate\Support\Str; @endphp

@push('styles')
<style>
/* Slug preview */
.slug-preview {
    display: flex; align-items: center; gap: 6px;
    margin-top: 6px; padding: 6px 10px;
    background: #f8fafc; border-radius: 7px;
    border: 1px solid #e2e8f0; font-size: 12px;
}
.slug-preview span { color: #94a3b8; }
.slug-preview code { color: #2563eb; font-family: monospace; word-break: break-all; }

/* Image picker */
.img-picker {
    border: 2px dashed #e2e8f0; border-radius: 10px;
    padding: 16px; text-align: center; cursor: pointer;
    transition: all .15s; background: #fafbfc;
}
.img-picker:hover { border-color: #3b82f6; background: #eff6ff; }
.img-picker.has-img { border-style: solid; border-color: #e2e8f0; padding: 8px; }

/* Tabs */
.cat-tab { padding: 8px 14px; font-size: 13px; font-weight: 500; color: #64748b; border: none; background: none; cursor: pointer; border-bottom: 2px solid transparent; transition: all .15s; }
.cat-tab.active { color: #2563eb; border-bottom-color: #2563eb; font-weight: 600; }

/* Char counter */
.char-counter { font-size: 11px; color: #94a3b8; text-align: right; margin-top: 4px; }
.char-counter.warn { color: #f59e0b; }
.char-counter.over { color: #ef4444; }
</style>
@endpush

@section('content')
<div style="display:grid;grid-template-columns:1fr 420px;gap:20px;align-items:start;">

{{-- ═══ CỘT TRÁI: Danh sách ═══ --}}
<div class="card" style="overflow:hidden;">
    <div class="card-header">
        <p style="font-size:13px;font-weight:700;color:#374151;">
            Tất cả danh mục
            <span style="background:#f1f5f9;color:#64748b;font-size:11px;font-weight:600;padding:2px 8px;border-radius:20px;margin-left:6px;">{{ $categories->count() }}</span>
        </p>
    </div>
    <table style="width:100%;border-collapse:collapse;">
        <thead>
            <tr style="background:#f8fafc;border-bottom:1.5px solid #f1f5f9;">
                <th class="tbl-th">Tên danh mục</th>
                <th class="tbl-th">Slug</th>
                <th class="tbl-th" style="text-align:center;">Sub</th>
                <th class="tbl-th" style="text-align:center;">Trạng thái</th>
                <th class="tbl-th" style="text-align:right;">Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @forelse($categories as $cat)
            {{-- Parent row --}}
            <tr class="tbl-tr" style="background:#fafbfc;">
                <td class="tbl-td">
                    <div style="display:flex;align-items:center;gap:10px;">
                        @if($cat->image)
                            <img src="{{ $cat->image }}" style="width:36px;height:36px;border-radius:8px;object-fit:cover;border:1.5px solid #f1f5f9;flex-shrink:0;" alt="">
                        @else
                            <span style="width:36px;height:36px;border-radius:8px;background:#fef3c7;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="fa-solid fa-folder" style="color:#f59e0b;font-size:13px;"></i>
                            </span>
                        @endif
                        <div>
                            <p style="font-size:13.5px;font-weight:700;color:#0f172a;">{{ $cat->name }}</p>
                            @if($cat->meta_title)
                                <p style="font-size:11px;color:#94a3b8;margin-top:1px;">{{ Str::limit($cat->meta_title, 40) }}</p>
                            @endif
                        </div>
                    </div>
                </td>
                <td class="tbl-td"><code style="font-size:11.5px;color:#64748b;">{{ $cat->slug }}</code></td>
                <td class="tbl-td" style="text-align:center;">
                    <span style="font-size:12px;color:#64748b;background:#f1f5f9;padding:2px 8px;border-radius:20px;">{{ $cat->children->count() }}</span>
                </td>
                <td class="tbl-td" style="text-align:center;">
                    <span class="badge {{ $cat->is_active ? 'badge-green' : 'badge-gray' }}">
                        {{ $cat->is_active ? 'Hiển thị' : 'Ẩn' }}
                    </span>
                </td>
                <td class="tbl-td" style="text-align:right;">
                    <div style="display:flex;align-items:center;justify-content:flex-end;gap:4px;">
                        <button onclick="openEdit({{ json_encode($cat) }})" class="act-btn edit" title="Sửa">
                            <i class="fa-solid fa-pencil"></i>
                        </button>
                        <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <input type="hidden" name="type" value="{{ $type }}">
                            <button onclick="return confirm('Xóa «{{ addslashes($cat->name) }}»? Sub danh mục sẽ được chuyển lên.')" class="act-btn del">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            {{-- Children rows --}}
            @foreach($cat->children as $child)
            <tr class="tbl-tr">
                <td class="tbl-td">
                    <div style="display:flex;align-items:center;gap:10px;padding-left:18px;">
                        <i class="fa-solid fa-turn-up fa-rotate-90 text-[#cbd5e1] text-[10px] flex-shrink-0"></i>
                        @if($child->image)
                            <img src="{{ $child->image }}" style="width:30px;height:30px;border-radius:6px;object-fit:cover;border:1.5px solid #f1f5f9;flex-shrink:0;" alt="">
                        @else
                            <span style="width:30px;height:30px;border-radius:6px;background:#eff6ff;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="fa-solid fa-folder-open" style="color:#3b82f6;font-size:10px;"></i>
                            </span>
                        @endif
                        <span style="font-size:13px;font-weight:500;color:#374151;">{{ $child->name }}</span>
                    </div>
                </td>
                <td class="tbl-td"><code style="font-size:11.5px;color:#94a3b8;">{{ $child->slug }}</code></td>
                <td class="tbl-td" style="text-align:center;"><span style="color:#cbd5e1;font-size:12px;">N/A</span></td>
                <td class="tbl-td" style="text-align:center;">
                    <span class="badge {{ $child->is_active ? 'badge-green' : 'badge-gray' }}">
                        {{ $child->is_active ? 'Hiển thị' : 'Ẩn' }}
                    </span>
                </td>
                <td class="tbl-td" style="text-align:right;">
                    <div style="display:flex;align-items:center;justify-content:flex-end;gap:4px;">
                        <button onclick="openEdit({{ json_encode($child) }})" class="act-btn edit" title="Sửa">
                            <i class="fa-solid fa-pencil"></i>
                        </button>
                        <form action="{{ route('admin.categories.destroy', $child) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <input type="hidden" name="type" value="{{ $type }}">
                            <button onclick="return confirm('Xóa «{{ addslashes($child->name) }}»?')" class="act-btn del">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
            @empty
            <tr>
                <td colspan="5" style="padding:60px 20px;text-align:center;color:#94a3b8;">
                    <i class="fa-solid fa-folder-open" style="font-size:40px;opacity:.3;display:block;margin-bottom:12px;"></i>
                    <p style="font-size:14px;font-weight:600;color:#64748b;">Chưa có danh mục nào</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- ═══ CỘT PHẢI: Form ═══ --}}
<div id="sidePanel" style="position:sticky;top:20px;">

    {{-- Tabs: Thêm / Sửa --}}
    <div style="display:flex;border-bottom:2px solid #f1f5f9;margin-bottom:0;background:#fff;border-radius:16px 16px 0 0;border:1px solid #e8ecf0;border-bottom:none;padding:0 16px;">
        <button class="cat-tab active" id="tabAdd" onclick="switchTab('add')">
            <i class="fa-solid fa-plus" style="font-size:11px;margin-right:4px;"></i> Thêm mới
        </button>
        <button class="cat-tab" id="tabEdit" onclick="switchTab('edit')" style="display:none;">
            <i class="fa-solid fa-pencil" style="font-size:11px;margin-right:4px;"></i> Đang sửa
        </button>
    </div>

    {{-- FORM THÊM --}}
    <div id="panelAdd" class="card" style="border-radius:0 0 16px 16px;border-top:none;">
        <form action="{{ route('admin.categories.store') }}" method="POST" id="formAdd">
            @csrf
            <input type="hidden" name="type" value="{{ $type }}">

            @include('admin.categories._form_fields', ['prefix' => 'add', 'cat' => null])

            <div style="padding:0 20px 20px;">
                <button type="submit" class="btn-primary" style="width:100%;justify-content:center;padding:11px;">
                    <i class="fa-solid fa-plus"></i> Thêm danh mục
                </button>
            </div>
        </form>
    </div>

    {{-- FORM SỬA --}}
    <div id="panelEdit" class="card" style="border-radius:0 0 16px 16px;border-top:none;display:none;">
        <form id="formEdit" method="POST">
            @csrf @method('PUT')
            <input type="hidden" name="type" value="{{ $type }}">

            @include('admin.categories._form_fields', ['prefix' => 'edit', 'cat' => null])

            <div style="padding:0 20px 20px;display:flex;gap:8px;">
                <button type="submit" class="btn-primary" style="flex:1;justify-content:center;padding:11px;">
                    <i class="fa-solid fa-floppy-disk"></i> Lưu thay đổi
                </button>
                <button type="button" onclick="switchTab('add')" class="btn-secondary" style="padding:11px 14px;">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        </form>
    </div>

</div>
</div>

@push('scripts')
<script>
// ── Slug generator (hỗ trợ tiếng Việt) ──────────────────────────────
function toSlug(str) {
    const map = {
        'à':'a','á':'a','ả':'a','ã':'a','ạ':'a',
        'ă':'a','ắ':'a','ặ':'a','ằ':'a','ẳ':'a','ẵ':'a',
        'â':'a','ấ':'a','ậ':'a','ầ':'a','ẩ':'a','ẫ':'a',
        'è':'e','é':'e','ẻ':'e','ẽ':'e','ẹ':'e',
        'ê':'e','ế':'e','ệ':'e','ề':'e','ể':'e','ễ':'e',
        'ì':'i','í':'i','ỉ':'i','ĩ':'i','ị':'i',
        'ò':'o','ó':'o','ỏ':'o','õ':'o','ọ':'o',
        'ô':'o','ố':'o','ộ':'o','ồ':'o','ổ':'o','ỗ':'o',
        'ơ':'o','ớ':'o','ợ':'o','ờ':'o','ở':'o','ỡ':'o',
        'ù':'u','ú':'u','ủ':'u','ũ':'u','ụ':'u',
        'ư':'u','ứ':'u','ự':'u','ừ':'u','ử':'u','ữ':'u',
        'ỳ':'y','ý':'y','ỷ':'y','ỹ':'y','ỵ':'y',
        'đ':'d',
        'À':'a','Á':'a','Ả':'a','Ã':'a','Ạ':'a',
        'Ă':'a','Ắ':'a','Ặ':'a','Ằ':'a','Ẳ':'a','Ẵ':'a',
        'Â':'a','Ấ':'a','Ậ':'a','Ầ':'a','Ẩ':'a','Ẫ':'a',
        'È':'e','É':'e','Ẻ':'e','Ẽ':'e','Ẹ':'e',
        'Ê':'e','Ế':'e','Ệ':'e','Ề':'e','Ể':'e','Ễ':'e',
        'Ì':'i','Í':'i','Ỉ':'i','Ĩ':'i','Ị':'i',
        'Ò':'o','Ó':'o','Ỏ':'o','Õ':'o','Ọ':'o',
        'Ô':'o','Ố':'o','Ộ':'o','Ồ':'o','Ổ':'o','Ỗ':'o',
        'Ơ':'o','Ớ':'o','Ợ':'o','Ờ':'o','Ở':'o','Ỡ':'o',
        'Ù':'u','Ú':'u','Ủ':'u','Ũ':'u','Ụ':'u',
        'Ư':'u','Ứ':'u','Ự':'u','Ừ':'u','Ử':'u','Ữ':'u',
        'Ỳ':'y','Ý':'y','Ỷ':'y','Ỹ':'y','Ỵ':'y',
        'Đ':'d',
    };
    return str.split('').map(c => map[c] || c).join('')
        .toLowerCase()
        .replace(/[^a-z0-9\s-]/g, '')
        .trim()
        .replace(/[\s]+/g, '-')
        .replace(/-+/g, '-');
}

function bindSlugAuto(nameId, slugId, previewId) {
    const nameEl    = document.getElementById(nameId);
    const slugEl    = document.getElementById(slugId);
    const previewEl = document.getElementById(previewId);
    let userEdited  = false;

    slugEl.addEventListener('input', () => { userEdited = slugEl.value.trim() !== ''; });

    nameEl.addEventListener('input', () => {
        if (!userEdited) {
            const s = toSlug(nameEl.value);
            slugEl.value = s;
            if (previewEl) previewEl.textContent = s || '(Tự động)';
        }
    });
    slugEl.addEventListener('input', () => {
        if (previewEl) previewEl.textContent = slugEl.value || '—';
    });
}

// ── TinyMCE init ─────────────────────────────────────────────────────
function initEditor(selector) {
    tinymce.init({
        selector,
        height: 320,
        menubar: false,
        plugins: 'lists link image code table',
        toolbar: 'bold italic underline | bullist numlist | link image | table | code',
        content_style: 'body { font-family: Inter, sans-serif; font-size: 14px; line-height: 1.7; }',
        branding: false,
        promotion: false,
        statusbar: false,
    });
}

// ── Char counter ──────────────────────────────────────────────────────
function bindCharCounter(inputId, counterId, max) {
    const el = document.getElementById(inputId);
    const ct = document.getElementById(counterId);
    if (!el || !ct) return;
    const update = () => {
        const len = el.value.length;
        ct.textContent = `${len}/${max}`;
        ct.className = 'char-counter' + (len > max ? ' over' : len > max * .85 ? ' warn' : '');
    };
    el.addEventListener('input', update);
    update();
}

// ── Image picker ──────────────────────────────────────────────────────
function bindImagePicker(pickerId, inputId, previewId) {
    const picker  = document.getElementById(pickerId);
    const input   = document.getElementById(inputId);
    const preview = document.getElementById(previewId);
    if (!picker) return;

    picker.addEventListener('click', () => {
        const url = prompt('Nhập URL hình ảnh:', input.value || '');
        if (url !== null) {
            input.value = url;
            updateImagePreview(picker, preview, url);
        }
    });
}

function updateImagePreview(picker, preview, url) {
    if (url) {
        picker.classList.add('has-img');
        picker.innerHTML = `<img src="${url}" style="width:100%;max-height:140px;object-fit:cover;border-radius:8px;" onerror="this.parentElement.innerHTML='<span style=color:#ef4444;font-size:12px;>URL không hợp lệ</span>'">
            <p style="font-size:11px;color:#94a3b8;margin-top:6px;">Nhấn để thay đổi</p>`;
    } else {
        picker.classList.remove('has-img');
        picker.innerHTML = `<i class="fa-solid fa-image" style="font-size:24px;color:#cbd5e1;display:block;margin-bottom:8px;"></i>
            <p style="font-size:13px;color:#94a3b8;font-weight:500;">Nhấn để thêm ảnh</p>
            <p style="font-size:11px;color:#cbd5e1;margin-top:4px;">Nhập URL hình ảnh</p>`;
    }
}

// ── Tab switch ────────────────────────────────────────────────────────
function switchTab(tab) {
    document.getElementById('panelAdd').style.display  = tab === 'add'  ? 'block' : 'none';
    document.getElementById('panelEdit').style.display = tab === 'edit' ? 'block' : 'none';
    document.getElementById('tabAdd').classList.toggle('active', tab === 'add');
    document.getElementById('tabEdit').classList.toggle('active', tab === 'edit');
    document.getElementById('tabEdit').style.display = tab === 'edit' ? 'inline-block' : 'none';
}

// ── Open edit ─────────────────────────────────────────────────────────
function openEdit(cat) {
    switchTab('edit');
    document.getElementById('formEdit').action = `/admin/categories/${cat.id}`;

    const set = (id, val) => { const el = document.getElementById(id); if (el) el.value = val ?? ''; };
    set('edit_name',             cat.name);
    set('edit_slug',             cat.slug);
    set('edit_image',            cat.image);
    // sync visible url input
    const editImgUrl = document.getElementById('edit_image_url');
    if (editImgUrl) editImgUrl.value = cat.image ?? '';
    updateImgPreview('edit_img_preview', cat.image ?? '');

    set('edit_icon',             cat.icon);
    const editIconUrl = document.getElementById('edit_icon_url');
    if (editIconUrl) editIconUrl.value = cat.icon ?? '';
    updateImgPreview('edit_icon_preview', cat.icon ?? '');
    set('edit_sort_order',       cat.sort_order);
    set('edit_is_active',        cat.is_active ? '1' : '0');
    set('edit_parent_id',        cat.parent_id ?? '');
    set('edit_meta_title',       cat.meta_title);
    set('edit_meta_description', cat.meta_description);
    set('edit_meta_keywords',    cat.meta_keywords);

    // Set VTM Editor content (description & content)
    setVtmedContent('description', cat.description ?? '');
    setVtmedContent('content',     cat.content ?? '');

    // Slug preview
    const sp = document.getElementById('edit_slug_preview');
    if (sp) sp.textContent = cat.slug || '(Tự động)';

    // Image preview
    const picker  = document.getElementById('edit_img_picker');
    const preview = document.getElementById('edit_img_preview');
    if (picker) updateImagePreview(picker, preview, cat.image);

    // Char counters
    ['edit_meta_title', 'edit_meta_description'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.dispatchEvent(new Event('input'));
    });

    document.getElementById('sidePanel').scrollIntoView({ behavior: 'smooth' });
}

// Set nội dung vào VTM Editor theo name attribute
function setVtmedContent(name, html) {
    // Tìm hidden textarea có name khớp trong form edit
    const formEdit = document.getElementById('formEdit');
    if (!formEdit) return;
    const hiddenTA = formEdit.querySelector(`textarea[name="${name}"][id$="_val"]`);
    if (!hiddenTA) return;
    const edId = hiddenTA.id.replace('_val', '');
    const edEl = document.getElementById(edId);
    if (edEl) {
        edEl.innerHTML = html;
        hiddenTA.value = html;
    }
}

// ── Init on load ──────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    bindSlugAuto('add_name', 'add_slug', 'add_slug_preview');
    bindSlugAuto('edit_name', 'edit_slug', 'edit_slug_preview');

    bindImagePicker('add_img_picker',  'add_image',  'add_img_preview');
    bindImagePicker('edit_img_picker', 'edit_image', 'edit_img_preview');

    bindCharCounter('add_meta_title',        'add_mt_count',  70);
    bindCharCounter('add_meta_description',  'add_md_count',  160);
    bindCharCounter('edit_meta_title',       'edit_mt_count', 70);
    bindCharCounter('edit_meta_description', 'edit_md_count', 160);
});
</script>
@endpush
@endsection
