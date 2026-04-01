@php
    $p = $prefix; // 'add' hoặc 'edit'
@endphp

{{-- ── Tabs nội dung ── --}}
<div x-data="{ tab: 'basic' }" style="padding:16px 20px 0;">

    {{-- Tab bar --}}
    <div style="display:flex;gap:0;border-bottom:2px solid #f1f5f9;margin-bottom:16px;">
        <button type="button" @click="tab='basic'"
                :class="tab==='basic' ? 'cat-tab active' : 'cat-tab'">
            <i class="fa-solid fa-circle-info" style="font-size:11px;margin-right:4px;"></i> Cơ bản
        </button>
        <button type="button" @click="tab='content'"
                :class="tab==='content' ? 'cat-tab active' : 'cat-tab'">
            <i class="fa-solid fa-align-left" style="font-size:11px;margin-right:4px;"></i> Nội dung
        </button>
        <button type="button" @click="tab='seo'"
                :class="tab==='seo' ? 'cat-tab active' : 'cat-tab'">
            <i class="fa-solid fa-magnifying-glass-chart" style="font-size:11px;margin-right:4px;"></i> SEO
        </button>
    </div>

    {{-- Tab: Cơ bản --}}
    <div x-show="tab==='basic'" style="display:flex;flex-direction:column;gap:14px;">

        {{-- Tên --}}
        <div>
            <label class="form-label">Tên danh mục <span style="color:#ef4444;">*</span></label>
            <input type="text" name="name" id="{{ $p }}_name" required
                   class="form-input" placeholder="Ví dụ: Áo thun nam">
        </div>

        {{-- Slug --}}
        <div>
            <label class="form-label">Slug</label>
            <input type="text" name="slug" id="{{ $p }}_slug"
                   class="form-input" placeholder="ao-thun-nam"
                   style="font-family:monospace;font-size:13px;">
            <div class="slug-preview">
                <span>URL:</span>
                <code id="{{ $p }}_slug_preview">—</code>
            </div>
        </div>

        {{-- Mô tả ngắn --}}
        <div>
            <label class="form-label">Mô tả ngắn</label>
            @include('components.admin.editor', [
                'name'   => 'description',
                'value'  => '',
                'height' => 140,
            ])
        </div>

        {{-- Ảnh đại diện --}}
        <div>
            <label class="form-label">Ảnh đại diện</label>
            <input type="hidden" name="image" id="{{ $p }}_image">
            <div style="display:flex;gap:8px;align-items:center;margin-bottom:8px;">
                <input type="text" id="{{ $p }}_image_url" placeholder="https://..."
                       class="form-input"
                       oninput="document.getElementById('{{ $p }}_image').value=this.value; updateImgPreview('{{ $p }}_img_preview', this.value)">
                <button type="button"
                        onclick="openMediaPicker('{{ $p }}_image_url', function(url){ document.getElementById('{{ $p }}_image').value=url; updateImgPreview('{{ $p }}_img_preview', url); })"
                        style="flex-shrink:0;padding:8px 14px;background:#f1f5f9;border:1px solid #e2e8f0;border-radius:8px;font-size:13px;font-weight:600;color:#374151;cursor:pointer;white-space:nowrap;display:flex;align-items:center;gap:6px;"
                        onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">
                    <i class="fa-solid fa-images" style="color:#3b82f6;"></i> Chọn
                </button>
            </div>
            <div id="{{ $p }}_img_preview"></div>
        </div>

        {{-- Icon danh mục --}}
        <div>
            <label class="form-label">Icon danh mục</label>
            <input type="hidden" name="icon" id="{{ $p }}_icon">
            <div style="display:flex;gap:8px;align-items:center;margin-bottom:8px;">
                <input type="text" id="{{ $p }}_icon_url" placeholder="https:// hoặc /theme/images/icons/..."
                       class="form-input"
                       oninput="document.getElementById('{{ $p }}_icon').value=this.value; updateImgPreview('{{ $p }}_icon_preview', this.value)">
                <button type="button"
                        onclick="openMediaPicker('{{ $p }}_icon_url', function(url){ document.getElementById('{{ $p }}_icon').value=url; updateImgPreview('{{ $p }}_icon_preview', url); })"
                        style="flex-shrink:0;padding:8px 14px;background:#f1f5f9;border:1px solid #e2e8f0;border-radius:8px;font-size:13px;font-weight:600;color:#374151;cursor:pointer;white-space:nowrap;display:flex;align-items:center;gap:6px;"
                        onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">
                    <i class="fa-solid fa-images" style="color:#3b82f6;"></i> Chọn
                </button>
            </div>
            <div id="{{ $p }}_icon_preview"></div>
            <p class="form-hint">Icon nhỏ hiển thị trong dropdown danh mục (khuyến nghị 32×32px)</p>
        </div>

        {{-- Danh mục cha --}}
        <div>
            <label class="form-label">Danh mục cha</label>
            <select name="parent_id" id="{{ $p }}_parent_id" class="form-select">
                <option value="">— Danh mục gốc —</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Thứ tự & Trạng thái --}}
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
            <div>
                <label class="form-label">Thứ tự</label>
                <input type="number" name="sort_order" id="{{ $p }}_sort_order"
                       value="0" class="form-input">
            </div>
            <div>
                <label class="form-label">Trạng thái</label>
                <select name="is_active" id="{{ $p }}_is_active" class="form-select">
                    <option value="1">Hiển thị</option>
                    <option value="0">Ẩn</option>
                </select>
            </div>
        </div>

    </div>

    {{-- Tab: Nội dung --}}
    <div x-show="tab==='content'" x-cloak style="padding-bottom:4px;">
        <label class="form-label" style="margin-bottom:8px;">
            Nội dung danh mục
            <span style="font-size:11px;color:#94a3b8;font-weight:400;">(hỗ trợ HTML, hình ảnh)</span>
        </label>
        @include('components.admin.editor', ['name' => 'content', 'value' => '', 'height' => 320])
    </div>

    {{-- Tab: SEO --}}
    <div x-show="tab==='seo'" x-cloak style="display:flex;flex-direction:column;gap:14px;padding-bottom:4px;">

        {{-- SEO Preview --}}
        <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:14px;">
            <p style="font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:10px;">Xem trước Google</p>
            <p style="font-size:18px;color:#1a0dab;font-weight:400;line-height:1.3;" id="{{ $p }}_seo_title_preview">Tiêu đề trang</p>
            <p style="font-size:13px;color:#006621;margin:2px 0;">yoursite.com › danh-muc › <span id="{{ $p }}_seo_slug_preview" style="color:#006621;">slug</span></p>
            <p style="font-size:13px;color:#545454;line-height:1.5;" id="{{ $p }}_seo_desc_preview">Mô tả meta sẽ hiển thị ở đây...</p>
        </div>

        <div>
            <label class="form-label">Meta Title</label>
            <input type="text" name="meta_title" id="{{ $p }}_meta_title"
                   class="form-input" maxlength="80"
                   placeholder="Tiêu đề SEO (khuyến nghị 50–70 ký tự)">
            <div class="char-counter" id="{{ $p }}_mt_count">0/70</div>
        </div>

        <div>
            <label class="form-label">Meta Description</label>
            <textarea name="meta_description" id="{{ $p }}_meta_description"
                      rows="3" class="form-input" style="resize:none;" maxlength="200"
                      placeholder="Mô tả SEO (khuyến nghị 120–160 ký tự)"></textarea>
            <div class="char-counter" id="{{ $p }}_md_count">0/160</div>
        </div>

        <div>
            <label class="form-label">Meta Keywords</label>
            <input type="text" name="meta_keywords" id="{{ $p }}_meta_keywords"
                   class="form-input" placeholder="từ khóa 1, từ khóa 2, từ khóa 3">
            <p class="form-hint">Phân cách bằng dấu phẩy</p>
        </div>

    </div>

</div>

{{-- Spacer --}}
<div style="height:16px;"></div>

@push('scripts')
<script>
// SEO live preview cho prefix {{ $p }}
(function() {
    const p = '{{ $p }}';
    function bindSeoPreview() {
        const mt   = document.getElementById(p+'_meta_title');
        const md   = document.getElementById(p+'_meta_description');
        const slug = document.getElementById(p+'_slug');
        const ptitle = document.getElementById(p+'_seo_title_preview');
        const pdesc  = document.getElementById(p+'_seo_desc_preview');
        const pslug  = document.getElementById(p+'_seo_slug_preview');

        if (mt && ptitle) mt.addEventListener('input', () => { ptitle.textContent = mt.value || 'Tiêu đề trang'; });
        if (md && pdesc)  md.addEventListener('input', () => { pdesc.textContent  = md.value || 'Mô tả meta sẽ hiển thị ở đây...'; });
        if (slug && pslug) slug.addEventListener('input', () => { pslug.textContent = slug.value || 'slug'; });
    }
    document.addEventListener('DOMContentLoaded', bindSeoPreview);
})();
</script>
@endpush
