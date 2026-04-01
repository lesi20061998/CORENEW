@extends('admin.layouts.app')
@section('title', 'Thư viện Media')
@section('page-title', 'Thư viện Media')
@section('page-subtitle', 'Quản lý hình ảnh và tệp tin')

@push('styles')
<style>
    .media-layout { display: flex; gap: 0; height: calc(100vh - 60px - 48px); min-height: 0; }

    /* ── Folder Tree ── */
    .folder-tree {
        width: 230px;
        min-width: 230px;
        background: #fff;
        border: 1px solid #e8ecf0;
        border-radius: 16px;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        flex-shrink: 0;
    }
    .folder-tree-header {
        padding: 14px 16px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .folder-tree-header span { font-size: 12px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: .06em; }
    .folder-list { flex: 1; overflow-y: auto; padding: 8px 0; }
    .folder-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 9px 16px;
        cursor: pointer;
        transition: background .12s;
        text-decoration: none;
        border: none;
        background: transparent;
        width: 100%;
        text-align: left;
    }
    .folder-item:hover { background: #f8fafc; }
    .folder-item.active { background: #eff6ff; }
    .folder-item.active .folder-name { color: #2563eb; font-weight: 600; }
    .folder-icon {
        width: 32px; height: 32px;
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        font-size: 13px;
        flex-shrink: 0;
    }
    .folder-name { font-size: 13.5px; font-weight: 500; color: #374151; flex: 1; }
    .folder-count {
        font-size: 11px; font-weight: 600;
        background: #f1f5f9; color: #94a3b8;
        padding: 2px 7px; border-radius: 20px;
    }
    .folder-item.active .folder-count { background: #dbeafe; color: #2563eb; }

    /* ── Media Panel ── */
    .media-panel {
        flex: 1;
        display: flex;
        flex-direction: column;
        min-width: 0;
    }
    .media-toolbar {
        background: #fff;
        border: 1px solid #e8ecf0;
        border-radius: 16px;
        padding: 14px 18px;
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        flex-shrink: 0;
        margin-bottom: 14px;
    }
    .media-grid {
        flex: 1;
        overflow-y: auto;
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(132px, 1fr));
        gap: 12px;
        align-content: start;
    }
    .media-card {
        background: #fff;
        border: 2px solid #e8ecf0;
        border-radius: 12px;
        overflow: hidden;
        cursor: pointer;
        transition: border-color .15s, box-shadow .15s;
        position: relative;
    }
    .media-card:hover { border-color: #93c5fd; box-shadow: 0 4px 12px rgba(59,130,246,.1); }
    .media-card.selected { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,.15); }
    .media-card-thumb {
        aspect-ratio: 1;
        background: #f8fafc;
        position: relative;
        overflow: hidden;
    }
    .media-card-thumb img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .media-card-thumb .file-icon {
        width: 100%; height: 100%;
        display: flex; align-items: center; justify-content: center;
        font-size: 26px;
    }
    .folder-card .media-card-thumb {
        aspect-ratio: 16/9;
        background: #fff;
    }
    .media-card-check {
        position: absolute;
        top: 7px; left: 7px;
        width: 20px; height: 20px;
        border-radius: 6px;
        background: #fff;
        border: 2px solid #cbd5e1;
        display: flex; align-items: center; justify-content: center;
        font-size: 10px;
        color: transparent;
        transition: all .12s;
        z-index: 2;
    }
    .media-card.selected .media-card-check { background: #3b82f6; border-color: #3b82f6; color: #fff; }
    .media-card-actions {
        position: absolute;
        top: 7px; right: 7px;
        display: flex; gap: 5px;
        opacity: 0;
        transition: opacity .15s;
        z-index: 2;
    }
    .media-card:hover .media-card-actions { opacity: 1; }
    .media-card-action-btn {
        width: 28px; height: 28px;
        border-radius: 7px;
        background: rgba(255,255,255,.95);
        border: none; cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        font-size: 11px;
        color: #374151;
        transition: background .12s;
        box-shadow: 0 1px 4px rgba(0,0,0,.15);
    }
    .media-card-action-btn:hover { background: #fff; }
    .media-card-action-btn.del:hover { background: #fee2e2; color: #dc2626; }
    .media-card-info { padding: 9px 10px; min-height: 48px; display: flex; flex-direction: column; justify-content: center; }
    .media-card-name { 
        font-size: 11.5px; 
        font-weight: 600; 
        color: #374151; 
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;  
        overflow: hidden;
        line-height: 1.3;
    }
    .media-card-meta { font-size: 10.5px; color: #94a3b8; margin-top: 3px; }

    /* Upload zone */
    .upload-zone {
        border: 2px dashed #cbd5e1;
        border-radius: 12px;
        padding: 28px 20px;
        text-align: center;
        cursor: pointer;
        transition: all .15s;
        background: #fafbfc;
    }
    .upload-zone:hover, .upload-zone.drag-over { border-color: #3b82f6; background: #eff6ff; }
    .upload-zone i { font-size: 28px; color: #93c5fd; margin-bottom: 10px; display: block; }
    .upload-zone p { font-size: 13px; color: #64748b; }
    .upload-zone span { font-size: 12px; color: #94a3b8; }

    /* Bulk bar */
    .bulk-bar {
        position: fixed;
        bottom: 24px;
        left: 50%;
        transform: translateX(-50%);
        background: #0f172a;
        color: #fff;
        border-radius: 14px;
        padding: 12px 20px;
        display: flex;
        align-items: center;
        gap: 14px;
        box-shadow: 0 8px 32px rgba(0,0,0,.3);
        z-index: 100;
        font-size: 13.5px;
        font-weight: 500;
        white-space: nowrap;
    }
    .bulk-bar-btn {
        padding: 7px 14px;
        border-radius: 9px;
        border: none;
        cursor: pointer;
        font-size: 13px;
        font-weight: 600;
        display: flex; align-items: center; gap: 7px;
        transition: background .12s;
    }

    /* Modal */
    .modal-overlay {
        position: fixed; inset: 0;
        background: rgba(0,0,0,.5);
        z-index: 200;
        display: flex; align-items: center; justify-content: center;
        padding: 20px;
    }
    .modal-box {
        background: #fff;
        border-radius: 18px;
        width: 100%;
        max-width: 480px;
        box-shadow: 0 20px 60px rgba(0,0,0,.2);
    }
    .modal-header {
        padding: 18px 22px;
        border-bottom: 1px solid #f1f5f9;
        display: flex; align-items: center; justify-content: space-between;
    }
    .modal-title { font-size: 15px; font-weight: 700; color: #0f172a; }
    .modal-body { padding: 22px; }
    .modal-footer { padding: 16px 22px; border-top: 1px solid #f1f5f9; display: flex; justify-content: flex-end; gap: 10px; }

    .empty-state { grid-column: 1/-1; padding: 60px 20px; text-align: center; color: #94a3b8; }
    .empty-state i { font-size: 48px; opacity: .2; display: block; margin-bottom: 14px; }
</style>
@endpush

@section('page-actions')
<button onclick="document.getElementById('upload-modal').style.display='flex'" class="btn btn-primary">
    <i class="fa-solid fa-upload"></i> Tải lên
</button>
@endsection

@section('content')

<div class="media-layout" x-data="mediaManager()" x-init="init()">

    {{-- ══ MEDIA PANEL ══ --}}
    <div class="media-panel" style="margin-left:0; width:100%;">

        {{-- Breadcrumbs & Navigation --}}
        <div class="media-toolbar" style="margin-bottom:12px;">
            <nav class="flex items-center gap-3 overflow-hidden">
                <a href="{{ route('admin.media.index') }}" class="px-3 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 hover:bg-blue-600 hover:text-white transition-all shadow-sm shrink-0 gap-2">
                    <i class="fa-solid fa-house text-xs"></i>
                    <span class="text-[10px] font-black uppercase tracking-widest">Gốc</span>
                </a>
                <i class="fa-solid fa-chevron-right text-[10px] text-slate-300"></i>
                @foreach($breadcrumbs as $bc)
                    <a href="{{ route('admin.media.index', ['folder_id' => $bc->id]) }}" class="px-4 py-2 rounded-xl {{ $currentFolder && $currentFolder->id == $bc->id ? 'bg-blue-600 text-white shadow-blue-200' : 'bg-slate-50 text-slate-600 hover:bg-blue-50' }} text-[11px] font-black uppercase transition-all border border-slate-100 whitespace-nowrap shadow-sm">
                        {{ $bc->name }}
                    </a>
                    @if(!$loop->last)
                        <i class="fa-solid fa-chevron-right text-[10px] text-slate-300"></i>
                    @endif
                @endforeach
                @if($currentFolder)
                    <span class="text-[10px] font-black uppercase text-slate-300 ml-2 tracking-widest italic opacity-60">({{ $folders->count() }} Thư mục, {{ $media->total() }} Tệp)</span>
                @else
                    <span class="text-[11px] font-black uppercase text-slate-600 bg-white px-4 py-2 rounded-xl border border-slate-100 shadow-sm">Kho tài liệu chính</span>
                @endif
            </nav>
            <div class="flex items-center gap-3 ml-auto">
                <button @click="openNewFolder()" class="btn btn-secondary border-none bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white" style="height:44px;border-radius:14px;">
                    <i class="fa-solid fa-folder-plus"></i> Thư mục mới
                </button>
                <div class="w-[1px] h-6 bg-slate-100"></div>
                <button @click="selectAll()" class="w-10 h-10 rounded-xl bg-white border border-slate-100 text-slate-400 hover:text-blue-600 transition-all shadow-sm flex items-center justify-center">
                    <i class="fa-solid fa-check-double"></i>
                </button>
            </div>
        </div>

        {{-- Grid --}}
        <div class="media-grid" id="media-grid">
            {{-- Folders --}}
            @foreach($folders as $f)
            <div class="media-card folder-card" style="border-color:#f1f5f9;background:#fdfdfd;" 
                 @dblclick="window.location.href='{{ route('admin.media.index', ['folder_id' => $f->id]) }}'">
                <div class="media-card-thumb" style="background:#fff7ed; position:relative;">
                    <div class="file-icon" style="color:{{ $f->color ?? '#f97316' }};">
                        <i class="fa-solid {{ $f->icon ?? 'fa-folder' }}"></i>
                    </div>
                    <div class="media-card-actions">
                        <button class="media-card-action-btn del"
                                @click.stop="deleteFolder({{ $f->id }})"
                                title="Xóa thư mục">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </div>
                </div>
                <div class="media-card-info" style="text-align:center;">
                    <div class="media-card-name" style="font-weight:700; color:{{ $f->color ?? '#374151' }};" title="{{ $f->name }}">
                        {{ $f->name }}
                    </div>
                </div>
            </div>
            @endforeach

            {{-- Files --}}
            @forelse($media as $item)
            <div class="media-card" :class="selected.includes({{ $item->id }}) ? 'selected' : ''"
                 @click="toggle({{ $item->id }})">
                <div class="media-card-thumb">
                    @if($item->isImage())
                        <img src="{{ $item->url }}" alt="{{ $item->alt ?? $item->name }}" loading="lazy">
                    @else
                        <div class="file-icon">
                            <i class="fa-solid fa-file-pdf" style="color:#f87171;"></i>
                        </div>
                    @endif
                    <div class="media-card-check">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <div class="media-card-actions">
                        <button class="media-card-action-btn"
                                @click.stop="copyUrl('{{ $item->url }}')"
                                title="Sao chép URL">
                            <i class="fa-solid fa-clipboard"></i>
                        </button>
                        <button class="media-card-action-btn del"
                                @click.stop="deleteSingle({{ $item->id }})"
                                title="Xóa">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </div>
                <div class="media-card-info">
                    <div class="media-card-name" title="{{ $item->name }}">{{ $item->name }}</div>
                    <div class="media-card-meta">
                        {{ number_format($item->size / 1024, 1) }} KB
                        @if($item->width) · {{ $item->width }}×{{ $item->height }}@endif
                    </div>
                </div>
            </div>
            @empty
            @if($folders->isEmpty())
                <div class="empty-state">
                    <i class="fa-solid fa-images"></i>
                    <p style="font-size:14px;font-weight:600;color:#64748b;">Thư mục trống</p>
                    <p style="font-size:12.5px;margin-top:4px;">Tải lên tệp để bắt đầu</p>
                </div>
            @endif
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($media->hasPages())
        <div style="margin-top:16px;flex-shrink:0;">
            {{ $media->appends(['folder_id' => $currentFolder ? $currentFolder->id : null])->links() }}
        </div>
        @endif
    </div>

    {{-- ══ BULK ACTION BAR ══ --}}
    <div class="bulk-bar" x-show="selected.length > 0" x-cloak>
        <span><span x-text="selected.length"></span> tệp đã chọn</span>
        <button class="bulk-bar-btn" style="background:#3b82f6;color:#fff;"
                @click="openMoveModal()">
            <i class="fa-solid fa-folder-arrow-down"></i> Chuyển thư mục
        </button>
        <button class="bulk-bar-btn" style="background:#dc2626;color:#fff;"
                @click="bulkDelete()">
            <i class="fa-solid fa-trash"></i> Xóa
        </button>
        <button class="bulk-bar-btn" style="background:rgba(255,255,255,.1);color:#cbd5e1;"
                @click="clearSelection()">
            Hủy
        </button>
    </div>

    {{-- ══ MOVE MODAL ══ --}}
    <div class="modal-overlay" x-show="showMoveModal" x-cloak @click.self="showMoveModal=false">
        <div class="modal-box">
            <div class="modal-header">
                <span class="modal-title"><i class="fa-solid fa-folder-arrow-down" style="color:#3b82f6;margin-right:8px;"></i>Chuyển vào thư mục</span>
                <button @click="showMoveModal=false" style="background:none;border:none;cursor:pointer;color:#94a3b8;font-size:18px;">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="modal-body">
                <label class="form-label">Chọn thư mục đích</label>
                <select x-model="moveTarget" class="form-select">
                    <option value="">Thư mục gốc (Root)</option>
                    @foreach($allFolders as $f)
                    <option value="{{ $f->id }}">{{ $f->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="modal-footer">
                <button @click="showMoveModal=false" class="btn btn-ghost">Hủy</button>
                <button @click="confirmMove()" class="btn btn-primary">
                    <i class="fa-solid fa-check"></i> Xác nhận
                </button>
            </div>
        </div>
    </div>

    {{-- ══ COPY TOAST ══ --}}
    <div x-show="toast" x-cloak x-transition
         style="position:fixed;bottom:80px;right:24px;background:#0f172a;color:#fff;padding:10px 18px;border-radius:10px;font-size:13px;font-weight:600;z-index:300;display:flex;align-items:center;gap:8px;">
        <i class="fa-solid fa-check" style="color:#4ade80;"></i>
        <span x-text="toastMsg"></span>
    </div>

    {{-- ══ UPLOAD MODAL ══ --}}
    <div id="upload-modal" class="modal-overlay" style="display:none;">
        <div class="modal-box" style="max-width:520px;">
            <div class="modal-header">
                <span class="modal-title"><i class="fa-solid fa-upload" style="color:#3b82f6;margin-right:8px;"></i>Tải lên tệp mới</span>
                <button onclick="document.getElementById('upload-modal').style.display='none'"
                        style="background:none;border:none;cursor:pointer;color:#94a3b8;font-size:18px;">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="modal-body" style="display:flex;flex-direction:column;gap:16px;">
                {{-- Drop zone --}}
                <div class="upload-zone" id="drop-zone" onclick="document.getElementById('file-input').click()">
                    <i class="fa-solid fa-cloud-arrow-up"></i>
                    <p>Kéo thả nhiều tệp vào đây hoặc <strong style="color:#3b82f6;">nhấn để chọn</strong></p>
                    <span>PNG, JPG, GIF, PDF... Không giới hạn số lượng</span>
                    <div id="file-preview" style="margin-top:12px;font-size:13px;color:#374151;font-weight:600;max-height:100px;overflow-y:auto;background:#fff;padding:0 10px;border-radius:8px;"></div>
                </div>
                <input type="file" id="file-input" multiple accept="image/*,application/pdf"
                       style="display:none;" onchange="previewFiles(this)">

                <div x-show="isUploading" x-cloak class="mt-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[11px] font-black uppercase text-blue-600 tracking-widest" x-text="`Đang tải: ${uploadCount}/${totalFiles}`"></span>
                        <span class="text-[11px] font-black text-slate-400" x-text="`${uploadProgress}%`"></span >
                    </div>
                    <div class="h-2 w-full bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full bg-blue-500 transition-all duration-300" :style="`width: ${uploadProgress}%`"></div >
                    </div>
                </div>

                <div>
                    <label class="form-label">Thư mục đích</label>
                    <select id="upload-folder" class="form-select">
                        <option value="">Lưu trữ chính</option>
                        @foreach($allFolders as $f)
                        <option value="{{ $f->id }}" {{ ($currentFolder && $currentFolder->id === $f->id) ? 'selected' : '' }}>{{ $f->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" @click="closeUploadModal()" class="btn btn-ghost">Hủy</button>
                <button type="button" @click="processBulkUpload()" class="btn btn-primary" id="upload-submit-btn" disabled style="opacity:0.5;">
                    <i class="fa-solid fa-upload"></i> Bắt đầu tải lên
                </button>
            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
function mediaManager() {
    return {
        selected: [],
        showMoveModal: false,
        moveTarget: '',
        toast: false,
        toastMsg: '',
        
        // Upload states
        isUploading: false,
        uploadProgress: 0,
        uploadCount: 0,
        totalFiles: 0,

        init() {
            // ...
        },

        closeUploadModal() {
            if (this.isUploading) return;
            document.getElementById('upload-modal').style.display='none';
            document.getElementById('file-preview').innerHTML = '';
            document.getElementById('file-input').value = '';
            document.getElementById('upload-submit-btn').disabled = true;
            document.getElementById('upload-submit-btn').style.opacity = '0.5';
        },

        async processBulkUpload() {
            const files = document.getElementById('file-input').files;
            if (!files.length) return;

            const folder = document.getElementById('upload-folder').value;
            this.totalFiles = files.length;
            this.uploadCount = 0;
            this.isUploading = true;
            this.uploadProgress = 0;

            const batchSize = 1; // Upload one by one for maximum reliability
            
            for (let i = 0; i < files.length; i++) {
                const formData = new FormData();
                formData.append('file', files[i]);
                formData.append('folder_id', folder);
                formData.append('_token', '{{ csrf_token() }}');

                try {
                    const response = await fetch("{{ route('admin.media.store') }}", {
                        method: 'POST',
                        body: formData,
                        headers: { 'Accept': 'application/json' }
                    });
                    
                    if (response.ok) {
                        this.uploadCount++;
                        this.uploadProgress = Math.round((this.uploadCount / this.totalFiles) * 100);
                    } else {
                        console.error('Upload failed for file:', files[i].name);
                    }
                } catch (err) {
                    console.error('Network error during upload:', err);
                }
            }

            this.showToast(`Đã tải lên thành công ${this.uploadCount} tệp`);
            setTimeout(() => location.reload(), 1000);
        },
        async openNewFolder() {
            const name = prompt('Nhập tên thư mục mới:');
            if (!name) return;

            const res = await fetch("{{ route('admin.media.create-folder') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ 
                    name: name, 
                    parent_id: {{ $currentFolder->id ?? 'null' }}
                })
            });

            if (res.ok) {
                this.showToast('Đã tạo thư mục');
                setTimeout(() => location.reload(), 800);
            }
        },

        async deleteFolder(id) {
            if (!confirm('Xóa thư mục này và toàn bộ nội dung bên trong?')) return;
            const res = await fetch("{{ route('admin.media.index') }}/folder/" + id, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
            });
            if (res.ok) {
                this.showToast('Đã xóa thư mục');
                setTimeout(() => location.reload(), 800);
            }
        },

        toggle(id) {
            const idx = this.selected.indexOf(id);
            if (idx === -1) this.selected.push(id);
            else this.selected.splice(idx, 1);
        },

        selectAll() {
            this.selected = [
                @foreach($media as $item)
                {{ $item->id }},
                @endforeach
            ];
        },

        clearSelection() { this.selected = []; },

        copyUrl(url) {
            navigator.clipboard.writeText(url);
            this.showToast('Đã sao chép URL');
        },

        showToast(msg) {
            this.toastMsg = msg;
            this.toast = true;
            setTimeout(() => this.toast = false, 2000);
        },

        async deleteSingle(id) {
            if (!confirm('Xóa tệp này?')) return;
            const res = await fetch("{{ route('admin.media.index') }}/" + id, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
            });
            if (res.ok) {
                this.showToast('Đã xóa tệp');
                setTimeout(() => location.reload(), 800);
            }
        },

        openMoveModal() {
            this.moveTarget = '';
            this.showMoveModal = true;
        },

        async confirmMove() {
            const res = await fetch("{{ route('admin.media.move') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ ids: this.selected, folder_id: this.moveTarget })
            });
            if (res.ok) {
                this.showMoveModal = false;
                this.showToast('Đã chuyển thư mục');
                setTimeout(() => location.reload(), 800);
            }
        },

        async bulkDelete() {
            if (!confirm(`Xóa ${this.selected.length} tệp đã chọn?`)) return;
            const res = await fetch("{{ route('admin.media.bulk-delete') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ ids: this.selected })
            });
            if (res.ok) {
                this.showToast(`Đã xóa ${this.selected.length} tệp`);
                setTimeout(() => location.reload(), 800);
            }
        },
    };
}

// Upload modal helpers
function previewFiles(input) {
    const preview = document.getElementById('file-preview');
    const submitBtn = document.getElementById('upload-submit-btn');
    
    if (input.files && input.files.length > 0) {
        if (input.files.length === 1) {
            const f = input.files[0];
            preview.textContent = `✓ ${f.name} (${(f.size/1024).toFixed(1)} KB)`;
        } else {
            preview.textContent = `✓ Đã chọn ${input.files.length} tệp tin`;
        }
        submitBtn.disabled = false;
        submitBtn.style.opacity = '1';
    } else {
        preview.textContent = '';
        submitBtn.disabled = true;
        submitBtn.style.opacity = '0.5';
    }
}

// Drag & drop
const dropZone = document.getElementById('drop-zone');
const fileInput = document.getElementById('file-input');

if (dropZone) {
    dropZone.addEventListener('dragover', e => { e.preventDefault(); dropZone.classList.add('drag-over'); });
    dropZone.addEventListener('dragleave', () => dropZone.classList.remove('drag-over'));
    dropZone.addEventListener('drop', e => {
        e.preventDefault();
        dropZone.classList.remove('drag-over');
        if (e.dataTransfer.files.length) {
            fileInput.files = e.dataTransfer.files;
            previewFiles(fileInput);
        }
    });
}
</script>
@endpush
