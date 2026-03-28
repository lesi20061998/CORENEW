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
        margin-left: 16px;
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
        grid-template-columns: repeat(auto-fill, minmax(148px, 1fr));
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
        font-size: 32px;
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
    .media-card-info { padding: 9px 10px; }
    .media-card-name { font-size: 12px; font-weight: 600; color: #374151; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .media-card-meta { font-size: 11px; color: #94a3b8; margin-top: 2px; }

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

    {{-- ══ FOLDER TREE ══ --}}
    <div class="folder-tree">
        <div class="folder-tree-header">
            <span>Thư mục</span>
        </div>
        <div class="folder-list">
            @foreach($rootFolders as $name => $meta)
            @php $count = $folderCounts[$name] ?? 0; @endphp
            <a href="{{ route('admin.media.index', ['folder' => $name]) }}"
               class="folder-item {{ $folder === $name ? 'active' : '' }}">
                <span class="folder-icon" style="background:{{ $folder === $name ? 'rgba(37,99,235,.1)' : '#f8fafc' }};">
                    <i class="fa-solid {{ $meta['icon'] }}" style="color:{{ $folder === $name ? '#2563eb' : $meta['color'] }};"></i>
                </span>
                <span class="folder-name">{{ $name }}</span>
                <span class="folder-count">{{ $count }}</span>
            </a>
            @endforeach

            {{-- Custom folders not in root list --}}
            @foreach($folderCounts as $name => $count)
                @if(!array_key_exists($name, $rootFolders))
                <a href="{{ route('admin.media.index', ['folder' => $name]) }}"
                   class="folder-item {{ $folder === $name ? 'active' : '' }}">
                    <span class="folder-icon" style="background:#f8fafc;">
                        <i class="fa-solid fa-folder" style="color:#64748b;"></i>
                    </span>
                    <span class="folder-name">{{ $name }}</span>
                    <span class="folder-count">{{ $count }}</span>
                </a>
                @endif
            @endforeach
        </div>
    </div>

    {{-- ══ MEDIA PANEL ══ --}}
    <div class="media-panel">

        {{-- Toolbar --}}
        <div class="media-toolbar">
            <div style="display:flex;align-items:center;gap:8px;flex:1;">
                <i class="fa-solid fa-folder-open" style="color:#3b82f6;font-size:16px;"></i>
                <span style="font-size:15px;font-weight:700;color:#0f172a;">{{ $folder }}</span>
                <span style="font-size:13px;color:#94a3b8;">({{ $media->total() }} tệp)</span>
            </div>
            <div style="display:flex;align-items:center;gap:8px;">
                <button @click="selectAll()" class="btn btn-ghost btn-sm">
                    <i class="fa-solid fa-check-double"></i> Chọn tất cả
                </button>
                <button @click="clearSelection()" class="btn btn-ghost btn-sm" x-show="selected.length > 0" x-cloak>
                    Bỏ chọn
                </button>
            </div>
        </div>

        {{-- Grid --}}
        <div class="media-grid" id="media-grid">
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
            <div class="empty-state">
                <i class="fa-solid fa-images"></i>
                <p style="font-size:14px;font-weight:600;color:#64748b;">Thư mục trống</p>
                <p style="font-size:12.5px;margin-top:4px;">Tải lên tệp để bắt đầu</p>
            </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($media->hasPages())
        <div style="margin-top:16px;flex-shrink:0;">
            {{ $media->appends(['folder' => $folder])->links() }}
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
                    @foreach($rootFolders as $name => $meta)
                    <option value="{{ $name }}">{{ $name }}</option>
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
        <form action="{{ route('admin.media.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-body" style="display:flex;flex-direction:column;gap:16px;">

                {{-- Drop zone --}}
                <div class="upload-zone" id="drop-zone" onclick="document.getElementById('file-input').click()">
                    <i class="fa-solid fa-cloud-arrow-up"></i>
                    <p>Kéo thả tệp vào đây hoặc <strong style="color:#3b82f6;">nhấn để chọn</strong></p>
                    <span>PNG, JPG, GIF, PDF — tối đa 10MB</span>
                    <div id="file-preview" style="margin-top:12px;font-size:13px;color:#374151;font-weight:600;"></div>
                </div>
                <input type="file" id="file-input" name="file" required accept="image/*,application/pdf"
                       style="display:none;" onchange="previewFile(this)">

                <div>
                    <label class="form-label">Thư mục</label>
                    <select name="folder" class="form-select">
                        @foreach($rootFolders as $name => $meta)
                        <option value="{{ $name }}" {{ $folder === $name ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="form-label">Mô tả ảnh (Alt text)</label>
                    <input type="text" name="alt" placeholder="Mô tả nội dung ảnh..." class="form-input">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="document.getElementById('upload-modal').style.display='none'"
                        class="btn btn-ghost">Hủy</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-upload"></i> Tải lên ngay
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function mediaManager() {
    return {
        selected: [],
        showMoveModal: false,
        moveTarget: '{{ $folder }}',
        toast: false,
        toastMsg: '',

        init() {
            // Auto-open upload modal if no files
            @if($media->isEmpty() && $media->total() === 0)
            // document.getElementById('upload-modal').style.display = 'flex';
            @endif
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
            this.moveTarget = '{{ $folder }}';
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
                body: JSON.stringify({ ids: this.selected, folder: this.moveTarget })
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
        }
    };
}

// Upload modal helpers
function previewFile(input) {
    const preview = document.getElementById('file-preview');
    if (input.files && input.files[0]) {
        const f = input.files[0];
        preview.textContent = `✓ ${f.name} (${(f.size/1024).toFixed(1)} KB)`;
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
            previewFile(fileInput);
        }
    });
}
</script>
@endpush
