@extends('admin.layouts.app')
@section('title', 'Thư viện Media')
@section('page-title', 'Thư viện Media')
@section('page-subtitle', 'Quản lý hình ảnh và tệp tin')

@section('page-actions')
    <button onclick="window._mediaManager && window._mediaManager.openNewFolder()" class="btn btn-secondary">
        <i class="fa-solid fa-folder-plus"></i> Thư mục mới
    </button>
    <button onclick="document.getElementById('upload-modal').style.display='flex'" class="btn btn-primary">
        <i class="fa-solid fa-cloud-arrow-up"></i> Tải lên
    </button>
@endsection

@section('content')
<div x-data="mediaManager()" x-init="init()">

    {{-- ── Main Card ── --}}
    <div class="card" style="border-radius:40px; overflow:hidden;">

        {{-- Toolbar --}}
        <div class="px-8 py-5 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3 flex-wrap">

            {{-- Breadcrumbs --}}
            <div class="flex items-center gap-2 flex-1 min-w-0">
                <a href="{{ route('admin.media.index') }}" class="w-8 h-8 rounded-xl bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:text-blue-600 transition-all shadow-sm">
                    <i class="fa-solid fa-house text-[10px]"></i>
                </a>
                @foreach($breadcrumbs as $bc)
                    <i class="fa-solid fa-chevron-right text-[9px] text-slate-300"></i>
                    <a href="{{ route('admin.media.index', ['folder_id' => $bc->id]) }}"
                       class="px-3 py-1.5 rounded-xl bg-white border border-slate-100 text-[10px] font-black uppercase text-slate-500 hover:text-blue-600 transition-all shadow-sm">
                        {{ $bc->name }}
                    </a>
                @endforeach
                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest ml-2">
                    {{ $media->total() }} tệp &bull; {{ $folders->count() }} thư mục
                </span>
            </div>

            {{-- Search --}}
            <div class="relative hidden md:block">
                <input type="text" x-model="searchFilter" placeholder="Lọc theo tên..."
                       class="w-48 pl-9 pr-4 py-2.5 bg-white border border-slate-200 rounded-2xl text-[10px] font-black uppercase tracking-widest focus:ring-4 focus:ring-blue-50 focus:border-blue-400 outline-none transition-all">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-300 text-[10px]"></i>
            </div>

            {{-- View modes --}}
            <div class="flex bg-white border border-slate-100 p-1.5 rounded-2xl shadow-sm">
                <button @click="viewMode = 'grid'" :class="viewMode === 'grid' ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-400 hover:text-blue-600'"
                        class="w-8 h-8 rounded-xl flex items-center justify-center transition-all">
                    <i class="fa-solid fa-th-large text-xs"></i>
                </button>
                <button @click="viewMode = 'compact'" :class="viewMode === 'compact' ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-400 hover:text-blue-600'"
                        class="w-8 h-8 rounded-xl flex items-center justify-center transition-all mx-1">
                    <i class="fa-solid fa-th text-xs"></i>
                </button>
                <button @click="viewMode = 'list'" :class="viewMode === 'list' ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-400 hover:text-blue-600'"
                        class="w-8 h-8 rounded-xl flex items-center justify-center transition-all">
                    <i class="fa-solid fa-list text-xs"></i>
                </button>
            </div>
        </div>

        {{-- Content Area --}}
        <div class="p-8 bg-white" style="min-height: calc(100vh - 280px);">

            {{-- Grid / Compact View --}}
            <div x-show="viewMode === 'grid' || viewMode === 'compact'"
                 :class="viewMode === 'compact' ? 'grid-cols-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-4' : 'grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6'"
                 class="grid">

                {{-- Folders --}}
                @foreach($folders as $f)
                    <div x-show="searchFilter === '' || '{{ strtolower($f->name) }}'.includes(searchFilter.toLowerCase())"
                         @dblclick="window.location.href='{{ route('admin.media.index', ['folder_id' => $f->id]) }}'"
                         class="group relative aspect-square bg-slate-50/50 rounded-[32px] border-2 border-transparent hover:border-blue-300 cursor-pointer overflow-hidden transition-all flex flex-col items-center justify-center shadow-sm">

                        <template x-if="editingId === 'folder-{{ $f->id }}'">
                            <div class="px-4 w-full">
                                <input type="text" id="rename-input-folder-{{ $f->id }}" value="{{ $f->name }}"
                                       @keyup.enter="saveRename({{ $f->id }}, true)" @click.stop
                                       class="w-full text-[10px] font-bold text-center border-b border-blue-500 outline-none bg-transparent py-1">
                            </div>
                        </template>
                        <template x-if="editingId !== 'folder-{{ $f->id }}'">
                            <div class="flex flex-col items-center">
                                <i class="fa-solid fa-folder-open text-4xl mb-3" style="color: {{ $f->color ?? '#3b82f6' }}"></i>
                                <span class="text-[10px] font-black uppercase text-slate-500 text-center px-4 tracking-tighter">{{ $f->name }}</span>
                            </div>
                        </template>

                        <div class="absolute inset-x-0 bottom-0 py-2 bg-white/90 backdrop-blur-sm border-t border-slate-100 flex justify-center gap-4 translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                            <button @click.stop="editingId = 'folder-{{ $f->id }}'" class="text-slate-400 hover:text-blue-500 transition-colors"><i class="fa-solid fa-pencil text-[10px]"></i></button>
                            <button @click.stop="deleteFolder({{ $f->id }})" class="text-slate-400 hover:text-rose-500 transition-colors"><i class="fa-solid fa-trash-can text-[10px]"></i></button>
                        </div>
                    </div>
                @endforeach

                {{-- Files --}}
                @foreach($media as $item)
                    <div x-show="searchFilter === '' || '{{ strtolower($item->name) }}'.includes(searchFilter.toLowerCase())"
                         @click="toggle({{ $item->id }})"
                         :class="selected.includes({{ $item->id }}) ? 'border-blue-500 ring-4 ring-blue-50' : 'border-transparent'"
                         class="group relative aspect-square bg-slate-50 rounded-[32px] border-2 hover:border-blue-500 cursor-pointer overflow-hidden transition-all shadow-sm hover:shadow-xl hover:-translate-y-1">

                        {{-- Preview --}}
                        @if($item->isImage())
                            <img src="{{ $item->url }}" alt="{{ $item->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center bg-slate-100">
                                <i class="fa-solid fa-file-invoice text-3xl text-slate-200 mb-2"></i>
                                <span class="text-[9px] font-black text-slate-400 uppercase px-3 text-center">{{ pathinfo($item->file_name, PATHINFO_EXTENSION) }}</span>
                            </div>
                        @endif

                        {{-- Rename overlay --}}
                        <div x-show="editingId === 'file-{{ $item->id }}'" class="absolute inset-0 bg-white/90 backdrop-blur-sm flex items-center justify-center p-4" @click.stop>
                            <input type="text" id="rename-input-file-{{ $item->id }}" value="{{ $item->name }}"
                                   @keyup.enter="saveRename({{ $item->id }}, false)"
                                   class="w-full text-center text-[10px] font-black uppercase text-slate-900 border-b border-blue-500 outline-none bg-transparent">
                        </div>

                        {{-- Hover overlay --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex flex-col justify-end p-4">
                            <p class="text-[9px] text-white font-black truncate uppercase tracking-tighter leading-none mb-1">{{ $item->name }}</p>
                            <div class="flex gap-4 mt-2 mb-1">
                                <button @click.stop="editingId = 'file-{{ $item->id }}'" class="text-white hover:text-blue-400 transition-colors"><i class="fa-solid fa-pencil text-[10px]"></i></button>
                                <button @click.stop="deleteSingle({{ $item->id }})" class="text-white hover:text-rose-400 transition-colors"><i class="fa-solid fa-trash-can text-[10px]"></i></button>
                                <a href="{{ $item->url }}" download @click.stop class="text-white hover:text-emerald-400 transition-colors"><i class="fa-solid fa-circle-arrow-down text-[10px]"></i></a>
                                <button @click.stop="window.open('{{ $item->url }}', '_blank')" class="text-white hover:text-sky-400 transition-colors ml-auto"><i class="fa-solid fa-eye text-[10px]"></i></button>
                            </div>
                        </div>

                        {{-- Selected check --}}
                        <div x-show="selected.includes({{ $item->id }})" class="absolute top-3 right-3 w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-xs shadow-lg">
                            <i class="fa-solid fa-check"></i>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- List View --}}
            <div x-show="viewMode === 'list'" class="bg-white rounded-3xl border border-slate-100 overflow-hidden shadow-sm">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 border-b border-slate-100 text-[10px] font-black uppercase text-slate-400 tracking-widest">
                        <tr>
                            <th class="px-6 py-4">Tên tệp / thư mục</th>
                            <th class="px-6 py-4">Dung lượng</th>
                            <th class="px-6 py-4">Định dạng</th>
                            <th class="px-6 py-4">Ngày tạo</th>
                            <th class="px-6 py-4 text-right">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($folders as $f)
                            <tr x-show="searchFilter === '' || '{{ strtolower($f->name) }}'.includes(searchFilter.toLowerCase())"
                                @dblclick="window.location.href='{{ route('admin.media.index', ['folder_id' => $f->id]) }}'"
                                class="hover:bg-slate-50 border-b border-slate-100 transition-colors group cursor-pointer">
                                <td class="px-6 py-3 font-bold text-slate-700 flex items-center gap-3">
                                    <i class="fa-solid fa-folder text-amber-400"></i>
                                    <span>{{ $f->name }}</span>
                                </td>
                                <td class="px-6 py-3 text-slate-400 uppercase">--</td>
                                <td class="px-6 py-3 text-slate-400 uppercase tracking-widest text-[9px]">Folder</td>
                                <td class="px-6 py-3 text-slate-400">{{ $f->created_at->format('d/m/Y') }}</td>
                                <td class="px-6 py-3 text-right">
                                    <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button @click.stop="editingId = 'folder-{{ $f->id }}'" class="w-8 h-8 rounded-lg bg-white shadow-sm border border-slate-100 text-slate-400 hover:text-blue-500"><i class="fa-solid fa-pencil text-[10px]"></i></button>
                                        <button @click.stop="deleteFolder({{ $f->id }})" class="w-8 h-8 rounded-lg bg-white shadow-sm border border-slate-100 text-slate-400 hover:text-rose-500"><i class="fa-solid fa-trash-can text-[10px]"></i></button>
                                        <a href="{{ route('admin.media.index', ['folder_id' => $f->id]) }}" class="px-3 py-1.5 rounded-lg bg-blue-600 text-white text-[9px] font-black uppercase tracking-widest ml-1">Mở</a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        @foreach($media as $item)
                            <tr x-show="searchFilter === '' || '{{ strtolower($item->name) }}'.includes(searchFilter.toLowerCase())"
                                @click="toggle({{ $item->id }})"
                                :class="selected.includes({{ $item->id }}) ? 'bg-blue-50/50' : ''"
                                class="hover:bg-slate-50 border-b border-slate-100 transition-colors group cursor-pointer">
                                <td class="px-6 py-4 flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-slate-100 overflow-hidden border border-slate-50 shrink-0">
                                        @if($item->isImage())
                                            <img src="{{ $item->url }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center"><i class="fa-solid fa-file text-slate-300"></i></div>
                                        @endif
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="font-black text-slate-800 uppercase tracking-tight truncate max-w-[200px]">{{ $item->name }}</span>
                                        <span class="text-[9px] text-slate-400">{{ $item->file_name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-slate-500 font-bold">{{ number_format($item->size / 1024, 1) }} KB</td>
                                <td class="px-6 py-4 text-slate-400">{{ $item->created_at->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button @click.stop="editingId = 'file-{{ $item->id }}'" class="w-8 h-8 rounded-lg bg-white shadow-sm border border-slate-100 text-slate-400 hover:text-blue-500"><i class="fa-solid fa-pencil text-[10px]"></i></button>
                                        <button @click.stop="deleteSingle({{ $item->id }})" class="w-8 h-8 rounded-lg bg-white shadow-sm border border-slate-100 text-slate-400 hover:text-rose-500"><i class="fa-solid fa-trash-can text-[10px]"></i></button>
                                        <a href="{{ $item->url }}" download @click.stop class="w-8 h-8 rounded-lg bg-white shadow-sm border border-slate-100 text-slate-400 hover:text-emerald-500 flex items-center justify-center"><i class="fa-solid fa-circle-arrow-down text-[10px]"></i></a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Empty state --}}
            @if($media->isEmpty() && $folders->isEmpty())
                <div class="flex flex-col items-center justify-center py-28 text-slate-200">
                    <i class="fa-solid fa-cloud-bolt-moon text-7xl mb-6 opacity-20"></i>
                    <p class="text-xs font-black uppercase tracking-[0.3em] text-slate-300">Thư mục trống</p>
                </div>
            @endif

            {{-- Pagination --}}
            @if($media->hasPages())
                <div class="mt-8 flex justify-center">
                    {{ $media->links() }}
                </div>
            @endif
        </div>

        {{-- Info bar --}}
        <div class="px-8 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
            <div class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">
                <span x-show="selected.length > 0" class="text-blue-600 font-black">
                    <span x-text="selected.length"></span> tệp đã chọn
                </span>
                <span x-show="selected.length === 0">Sẵn sàng</span>
            </div>
            <div class="flex gap-3" x-show="selected.length > 0" x-cloak>
                <button @click="openMoveModal()" class="px-4 py-2 rounded-xl bg-blue-600 text-white text-[10px] font-black uppercase tracking-widest hover:bg-blue-700 transition-all flex items-center gap-2">
                    <i class="fa-solid fa-folder-arrow-down"></i> Chuyển thư mục
                </button>
                <button @click="bulkDelete()" class="px-4 py-2 rounded-xl bg-rose-500 text-white text-[10px] font-black uppercase tracking-widest hover:bg-rose-600 transition-all flex items-center gap-2">
                    <i class="fa-solid fa-trash"></i> Xóa
                </button>
                <button @click="clearSelection()" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-500 text-[10px] font-black uppercase tracking-widest hover:bg-slate-200 transition-all">
                    Hủy
                </button>
            </div>
        </div>
    </div>

    {{-- ── Move Modal ── --}}
    <div x-show="showMoveModal" x-cloak class="fixed inset-0 z-[9999] flex items-center justify-center p-4" @keydown.window.escape="showMoveModal=false">
        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-md" @click="showMoveModal=false"></div>
        <div class="relative bg-white rounded-[32px] shadow-2xl w-full max-w-md border border-white p-8"
             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <h3 class="text-sm font-black text-slate-900 uppercase tracking-tighter mb-1">Chuyển vào thư mục</h3>
            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-6">Chọn thư mục đích cho <span x-text="selected.length"></span> tệp đã chọn</p>
            <label class="form-label">Thư mục đích</label>
            <select x-model="moveTarget" class="form-select mb-6">
                <option value="">Thư mục gốc (Root)</option>
                @foreach($allFolders as $f)
                    <option value="{{ $f->id }}">{{ $f->name }}</option>
                @endforeach
            </select>
            <div class="flex gap-3 justify-end">
                <button @click="showMoveModal=false" class="btn btn-secondary">Hủy</button>
                <button @click="confirmMove()" class="btn btn-primary"><i class="fa-solid fa-check"></i> Xác nhận</button>
            </div>
        </div>
    </div>

    {{-- ── Toast ── --}}
    <div x-show="toast" x-cloak x-transition
         class="fixed bottom-6 right-6 bg-slate-900 text-white px-6 py-3 rounded-2xl text-[12px] font-black z-[9999] flex items-center gap-3 shadow-2xl">
        <i class="fa-solid fa-check text-emerald-400"></i>
        <span x-text="toastMsg"></span>
    </div>

    {{-- ── Upload Modal ── --}}
    <div id="upload-modal" class="fixed inset-0 z-[9999] items-center justify-center p-4" style="display:none;">
        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-md" onclick="document.getElementById('upload-modal').style.display='none'"></div>
        <div class="relative bg-white rounded-[32px] shadow-2xl w-full max-w-lg border border-white overflow-hidden"
             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-black text-slate-900 uppercase tracking-tighter">Tải lên tệp mới</h3>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">PNG, JPG, GIF, PDF... Không giới hạn số lượng</p>
                </div>
                <button onclick="document.getElementById('upload-modal').style.display='none'"
                        class="w-10 h-10 rounded-2xl bg-white text-slate-400 hover:bg-rose-500 hover:text-white transition-all shadow-sm flex items-center justify-center border border-slate-100">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="p-8 flex flex-col gap-5">
                {{-- Drop zone --}}
                <div id="drop-zone" onclick="document.getElementById('file-input').click()"
                     class="border-2 border-dashed border-slate-200 rounded-3xl p-10 flex flex-col items-center justify-center gap-3 cursor-pointer hover:border-blue-400 hover:bg-blue-50/30 transition-all text-center">
                    <i class="fa-solid fa-cloud-arrow-up text-4xl text-slate-300"></i>
                    <p class="text-[11px] font-black uppercase text-slate-500 tracking-widest">Kéo thả hoặc <span class="text-blue-600">nhấn để chọn</span></p>
                    <div id="file-preview" class="text-[11px] font-bold text-slate-600 mt-1"></div>
                </div>
                <input type="file" id="file-input" multiple accept="image/*,application/pdf" style="display:none;" onchange="previewFiles(this)">

                <div x-show="isUploading" x-cloak>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[10px] font-black uppercase text-blue-600 tracking-widest" x-text="`Đang tải: ${uploadCount}/${totalFiles}`"></span>
                        <span class="text-[10px] font-black text-slate-400" x-text="`${uploadProgress}%`"></span>
                    </div>
                    <div class="h-2 w-full bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full bg-blue-500 transition-all duration-300" :style="`width: ${uploadProgress}%`"></div>
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
            <div class="px-8 py-5 bg-slate-50 border-t border-slate-100 flex justify-end gap-3">
                <button type="button" @click="closeUploadModal()" class="btn btn-secondary">Hủy</button>
                <button type="button" @click="processBulkUpload()" class="btn btn-primary" id="upload-submit-btn" disabled style="opacity:0.5;">
                    <i class="fa-solid fa-cloud-arrow-up"></i> Bắt đầu tải lên
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
            viewMode: 'grid',
            searchFilter: '',
            editingId: null,
            showMoveModal: false,
            moveTarget: '',
            toast: false,
            toastMsg: '',
            isUploading: false,
            uploadProgress: 0,
            uploadCount: 0,
            totalFiles: 0,

            init() {
                window._mediaManager = this;
            },

            async saveRename(id, isFolder) {
                const inputId = isFolder ? `rename-input-folder-${id}` : `rename-input-file-${id}`;
                const input = document.getElementById(inputId);
                const newName = input.value.trim();
                if (!newName) return (this.editingId = null);

                const route = isFolder
                    ? "{{ route('admin.media.index') }}/folder/" + id + "/rename"
                    : "{{ route('admin.media.index') }}/file/" + id + "/rename";

                const res = await fetch(route, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                    body: JSON.stringify({ name: newName })
                });
                if (res.ok) { this.showToast('Đã đổi tên thành công'); setTimeout(() => location.reload(), 500); }
                this.editingId = null;
            },

            closeUploadModal() {
                if (this.isUploading) return;
                document.getElementById('upload-modal').style.display = 'none';
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

                for (let i = 0; i < files.length; i++) {
                    const formData = new FormData();
                    formData.append('file', files[i]);
                    formData.append('folder_id', folder);
                    formData.append('_token', '{{ csrf_token() }}');
                    try {
                        const response = await fetch("{{ route('admin.media.store') }}", {
                            method: 'POST', body: formData, headers: { 'Accept': 'application/json' }
                        });
                        if (response.ok) { this.uploadCount++; this.uploadProgress = Math.round((this.uploadCount / this.totalFiles) * 100); }
                    } catch (err) { console.error('Upload error:', err); }
                }
                this.showToast(`Đã tải lên thành công ${this.uploadCount} tệp`);
                setTimeout(() => location.reload(), 1000);
            },

            async openNewFolder() {
                const name = prompt('Nhập tên thư mục mới:');
                if (!name) return;
                const res = await fetch("{{ route('admin.media.create-folder') }}", {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                    body: JSON.stringify({ name: name, parent_id: {{ $currentFolder->id ?? 'null' }} })
                });
                if (res.ok) { this.showToast('Đã tạo thư mục'); setTimeout(() => location.reload(), 800); }
            },

            async deleteFolder(id) {
                if (!confirm('Xóa thư mục này và toàn bộ nội dung bên trong?')) return;
                const res = await fetch("{{ route('admin.media.index') }}/folder/" + id, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                });
                if (res.ok) { this.showToast('Đã xóa thư mục'); setTimeout(() => location.reload(), 800); }
            },

            toggle(id) {
                const idx = this.selected.indexOf(id);
                if (idx === -1) this.selected.push(id); else this.selected.splice(idx, 1);
            },

            clearSelection() { this.selected = []; },

            showToast(msg) {
                this.toastMsg = msg; this.toast = true;
                setTimeout(() => this.toast = false, 2500);
            },

            async deleteSingle(id) {
                if (!confirm('Xóa tệp này?')) return;
                const res = await fetch("{{ route('admin.media.index') }}/" + id, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                });
                if (res.ok) { this.showToast('Đã xóa tệp'); setTimeout(() => location.reload(), 800); }
            },

            openMoveModal() { this.moveTarget = ''; this.showMoveModal = true; },

            async confirmMove() {
                const res = await fetch("{{ route('admin.media.move') }}", {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                    body: JSON.stringify({ ids: this.selected, folder_id: this.moveTarget })
                });
                if (res.ok) { this.showMoveModal = false; this.showToast('Đã chuyển thư mục'); setTimeout(() => location.reload(), 800); }
            },

            async bulkDelete() {
                if (!confirm(`Xóa ${this.selected.length} tệp đã chọn?`)) return;
                const res = await fetch("{{ route('admin.media.bulk-delete') }}", {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                    body: JSON.stringify({ ids: this.selected })
                });
                if (res.ok) { this.showToast(`Đã xóa ${this.selected.length} tệp`); setTimeout(() => location.reload(), 800); }
            },
        };
    }

    function previewFiles(input) {
        const preview = document.getElementById('file-preview');
        const submitBtn = document.getElementById('upload-submit-btn');
        if (input.files && input.files.length > 0) {
            preview.textContent = input.files.length === 1
                ? `✓ ${input.files[0].name} (${(input.files[0].size / 1024).toFixed(1)} KB)`
                : `✓ Đã chọn ${input.files.length} tệp tin`;
            submitBtn.disabled = false;
            submitBtn.style.opacity = '1';
        } else {
            preview.textContent = '';
            submitBtn.disabled = true;
            submitBtn.style.opacity = '0.5';
        }
    }

    // Drag & drop
    document.addEventListener('DOMContentLoaded', () => {
        const dropZone = document.getElementById('drop-zone');
        const fileInput = document.getElementById('file-input');
        if (!dropZone) return;
        dropZone.addEventListener('dragover', e => { e.preventDefault(); dropZone.classList.add('border-blue-400', 'bg-blue-50'); });
        dropZone.addEventListener('dragleave', () => dropZone.classList.remove('border-blue-400', 'bg-blue-50'));
        dropZone.addEventListener('drop', e => {
            e.preventDefault();
            dropZone.classList.remove('border-blue-400', 'bg-blue-50');
            if (e.dataTransfer.files.length) { fileInput.files = e.dataTransfer.files; previewFiles(fileInput); }
        });
    });
</script>
@endpush
