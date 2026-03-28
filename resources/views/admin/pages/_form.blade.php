<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
    <div class="xl:col-span-2 space-y-5">
        <div class="card">
            <div class="card-header">
                <h3 class="text-sm font-semibold text-gray-700">Nội dung trang</h3>
            </div>
            <div class="card-body space-y-5">
                <div>
                    <label class="form-label">Tiêu đề <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $page?->title) }}" required
                        class="form-input @error('title') border-red-400 @enderror"
                        placeholder="Nhập tiêu đề trang...">
                    @error('title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Đường dẫn (Slug)</label>
                    <input type="text" name="slug" value="{{ old('slug', $page?->slug) }}"
                        class="form-input font-mono" placeholder="duong-dan-trang">
                    <p class="text-xs text-gray-400 mt-1">Để trống để tự động tạo từ tiêu đề</p>
                </div>
                <div>
                    <label class="form-label">Nội dung</label>
                    @include('admin.components.editor', ['name' => 'content', 'value' => old('content', $page?->content), 'height' => 420])
                </div>
            </div>
        </div>

        @include('admin.components.seo-checklist', ['context' => 'page', 'model' => $page])
    </div>

    <div class="space-y-5">
        <div class="card">
            <div class="card-header">
                <h3 class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                    <i class="fa-solid fa-sliders text-gray-400"></i> Cài đặt trang
                </h3>
            </div>
            <div class="card-body space-y-4">
                <div>
                    <label class="form-label">Giao diện (Template)</label>
                    <select name="template" class="form-select">
                        @foreach($templates as $key => $label)
                            <option value="{{ $key }}" {{ old('template', $page?->template ?? 'default') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-400 mt-1">File trong <code class="bg-gray-100 px-1 rounded">resources/views/templates/</code></p>
                </div>
                <div>
                    <label class="form-label">Trạng thái</label>
                    <select name="status" class="form-select">
                        <option value="draft" {{ old('status', $page?->status ?? 'draft') === 'draft' ? 'selected' : '' }}>Bản nháp</option>
                        <option value="published" {{ old('status', $page?->status) === 'published' ? 'selected' : '' }}>Đã xuất bản</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Thứ tự hiển thị</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $page?->sort_order ?? 0) }}" min="0"
                        class="form-input">
                    <p class="text-xs text-gray-400 mt-1">Số nhỏ hơn hiển thị trước</p>
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
                    <input type="text" name="meta_title" value="{{ old('meta_title', $page?->getRawOriginal('meta_title')) }}"
                        class="form-input text-xs" placeholder="Tiêu đề hiển thị trên Google...">
                </div>
                <div>
                    <label class="form-label text-xs uppercase text-gray-400 font-bold tracking-wider mb-2 block">Mô tả SEO</label>
                    <textarea name="meta_description" rows="3"
                        class="form-input resize-none text-xs"
                        placeholder="Mô tả ngắn hiển thị trên kết quả tìm kiếm...">{{ old('meta_description', $page?->meta_description) }}</textarea>
                </div>
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="form-label text-xs uppercase text-gray-400 font-bold tracking-wider mb-2 block">Từ khóa</label>
                        <input type="text" name="meta_keywords" value="{{ old('meta_keywords', $page?->meta_keywords) }}"
                            class="form-input text-xs" placeholder="từ khóa 1, từ khóa 2">
                    </div>
                    <div>
                        <label class="form-label text-xs uppercase text-gray-400 font-bold tracking-wider mb-2 block">Canonical URL</label>
                        <input type="url" name="canonical_url" value="{{ old('canonical_url', $page?->canonical_url) }}"
                            class="form-input text-xs" placeholder="https://...">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
