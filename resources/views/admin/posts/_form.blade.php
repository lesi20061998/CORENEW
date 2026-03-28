<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

    <div class="xl:col-span-2 space-y-5">
        <div class="card">
            <div class="card-header">
                <h3 class="text-sm font-semibold text-gray-700">Nội dung bài viết</h3>
            </div>
            <div class="card-body space-y-5">
                <div>
                    <label class="form-label">Tiêu đề <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $post?->title) }}" required
                        class="form-input @error('title') border-red-400 @enderror"
                        placeholder="Nhập tiêu đề bài viết...">
                    @error('title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                {{-- Slug moved to SEO sidebar --}}
                <div>
                    <label class="form-label">Nội dung</label>
                    @include('admin.components.editor', ['name' => 'content', 'value' => old('content', $post?->content), 'height' => 420])
                </div>
                <div>
                    <label class="form-label">Tóm tắt</label>
                    @include('admin.components.editor', ['name' => 'excerpt', 'value' => old('excerpt', $post?->getRawOriginal('excerpt')), 'height' => 180])
                </div>
            </div>
        </div>

        @include('admin.components.seo-checklist', ['context' => 'post', 'model' => $post])
    </div>

    <div class="space-y-5">
        <div class="card">
            <div class="card-header">
                <h3 class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                    <i class="fa-solid fa-circle-dot text-gray-400"></i> Xuất bản
                </h3>
            </div>
            <div class="card-body space-y-4">
                <div>
                    <label class="form-label">Trạng thái</label>
                    <select name="status" class="form-select">
                        <option value="draft" {{ old('status', $post?->status ?? 'draft') === 'draft' ? 'selected' : '' }}>Bản nháp</option>
                        <option value="published" {{ old('status', $post?->status) === 'published' ? 'selected' : '' }}>Đã xuất bản</option>
                        <option value="scheduled" {{ old('status', $post?->status) === 'scheduled' ? 'selected' : '' }}>Lên lịch</option>
                    </select>
                </div>
                <label class="flex items-center gap-2.5 cursor-pointer p-3 rounded-lg border border-gray-100 hover:bg-gray-50 transition">
                    <input type="checkbox" name="is_featured" value="1" id="is_featured"
                        {{ old('is_featured', $post?->is_featured) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <div>
                        <p class="text-sm font-medium text-gray-700">Bài viết nổi bật</p>
                        <p class="text-xs text-gray-400">Hiển thị ở vị trí ưu tiên</p>
                    </div>
                </label>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                    <i class="fa-solid fa-folder text-gray-400"></i> Danh mục & Thẻ
                </h3>
            </div>
            <div class="card-body space-y-4">
                <div>
                    <label class="form-label">Danh mục</label>
                    <select name="category_id" class="form-select">
                        <option value="">— Không có —</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $post?->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Thẻ (Tags)</label>
                    <input type="text" name="tags[]"
                        placeholder="tin tức, khuyến mãi, nổi bật"
                        value="{{ old('tags', $post ? $post->tags->pluck('name')->implode(', ') : '') }}"
                        class="form-input">
                    <p class="text-xs text-gray-400 mt-1">Phân cách bằng dấu phẩy</p>
                </div>
            </div>
        </div>

        {{-- SEO moved to main col --}}

        <div class="card">
            <div class="card-header">
                <h3 class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                    <i class="fa-solid fa-image text-gray-400"></i> Ảnh đại diện
                </h3>
            </div>
            <div class="card-body">
                <div style="display:flex;gap:8px;align-items:center;">
                    <input type="text" name="thumbnail" id="post_thumbnail" placeholder="https://..."
                        value="{{ old('thumbnail', $post?->thumbnail) }}"
                        class="form-input" oninput="updateImgPreview('post_thumb_preview', this.value)">
                    <button type="button" onclick="openMediaPicker('post_thumbnail', function(url){ updateImgPreview('post_thumb_preview', url); })"
                            style="flex-shrink:0;padding:8px 14px;background:#f1f5f9;border:1px solid #e2e8f0;border-radius:8px;font-size:13px;font-weight:600;color:#374151;cursor:pointer;white-space:nowrap;display:flex;align-items:center;gap:6px;"
                            onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">
                        <i class="fa-solid fa-images" style="color:#3b82f6;"></i> Chọn
                    </button>
                </div>
                <div id="post_thumb_preview" style="margin-top:8px;">
                    @if($post?->thumbnail)
                        <img src="{{ $post->thumbnail }}" class="rounded-lg w-full object-cover" style="max-height:140px" alt="">
                    @else
                        <div class="rounded-lg bg-gray-50 border-2 border-dashed border-gray-200 flex items-center justify-center" style="height:80px;">
                            <p class="text-xs text-gray-400">Chưa có ảnh</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h3 class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                    <i class="fa-solid fa-magnifying-glass text-gray-400"></i> SEO
                </h3>
            </div>
            <div class="card-body space-y-4">
                <div>
                    <label class="form-label text-xs uppercase text-gray-400 font-bold tracking-wider mb-2 block">Tiêu đề SEO</label>
                    <input type="text" name="meta_title" value="{{ old('meta_title', $post?->getRawOriginal('meta_title')) }}"
                        class="form-input text-xs" placeholder="Tiêu đề hiển thị trên Google...">
                </div>
                <div>
                    <label class="form-label text-xs uppercase text-gray-400 font-bold tracking-wider mb-2 block">Mô tả SEO</label>
                    <textarea name="meta_description" rows="3"
                        class="form-input resize-none text-xs"
                        placeholder="Mô tả ngắn hiển thị trên kết quả tìm kiếm...">{{ old('meta_description', $post?->meta_description) }}</textarea>
                </div>
                <div>
                    <label class="form-label text-xs uppercase text-gray-400 font-bold tracking-wider mb-2 block">Đường dẫn (Slug)</label>
                    <div class="flex items-center gap-2 p-2.5 bg-gray-50 rounded-lg border border-gray-100 mb-4">
                        <input type="text" name="slug" value="{{ old('slug', $post?->slug) }}"
                               class="flex-1 bg-transparent border-none p-0 text-xs font-mono text-gray-500 focus:ring-0" placeholder="duong-dan-bai-viet">
                        @if($post?->slug)
                            <a href="{{ url('/' . $post->slug) }}" target="_blank" class="text-[10px] font-bold text-blue-500 hover:text-blue-600">
                                <i class="fa-solid fa-eye"></i> Xem
                            </a>
                        @endif
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="form-label text-xs uppercase text-gray-400 font-bold tracking-wider mb-2 block">Từ khóa</label>
                        <input type="text" name="meta_keywords" value="{{ old('meta_keywords', $post?->meta_keywords) }}"
                            class="form-input text-xs" placeholder="từ khóa 1, từ khóa 2">
                    </div>
                    <div>
                        <label class="form-label text-xs uppercase text-gray-400 font-bold tracking-wider mb-2 block">Canonical URL</label>
                        <input type="url" name="canonical_url" value="{{ old('canonical_url', $post?->canonical_url) }}"
                            class="form-input text-xs" placeholder="https://...">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
