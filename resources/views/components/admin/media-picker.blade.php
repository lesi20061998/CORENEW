@once
{{--
    Media Picker Modal — dùng chung toàn admin
    Cách dùng:
      @include('components.admin.media-picker')
    Mở modal:
      openMediaPicker(targetId, callback)
--}}
<div id="media-picker-modal" 
     x-data="mediaPickerData()" 
     x-show="show" 
     x-cloak 
     class="fixed inset-0 z-[99999] flex items-center justify-center p-4 sm:p-6"
     @keydown.window.escape="show = false">
    
    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-md transition-opacity" 
         x-show="show" 
         x-transition:enter="ease-out duration-300" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100" 
         x-transition:leave="ease-in duration-200" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0" 
         @click="show = false"></div>

    {{-- Modal Content --}}
    <div class="relative bg-white rounded-[40px] shadow-2xl w-full max-w-6xl max-h-[90vh] flex flex-col overflow-hidden border border-white"
         x-show="show" 
         x-transition:enter="ease-out duration-300" 
         x-transition:enter-start="opacity-0 scale-95" 
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="ease-in duration-200" 
         x-transition:leave-start="opacity-100 scale-100" 
         x-transition:leave-end="opacity-0 scale-95">

        {{-- Header --}}
        <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between shrink-0">
            <div>
                <h3 class="text-sm font-black text-slate-900 tracking-tighter uppercase">Thư viện phương tiện</h3>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Chọn ảnh hoặc tệp tin để chèn vào nội dung</p>
            </div>
            <div class="flex items-center gap-3">
                {{-- View Modes --}}
                <div class="hidden lg:flex bg-white border border-slate-100 p-1.5 rounded-2xl shadow-sm mr-2">
                    <button @click="viewMode = 'grid'" :class="viewMode === 'grid' ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-400 hover:text-blue-600'" class="w-8 h-8 rounded-xl flex items-center justify-center transition-all"><i class="fa-solid fa-th-large text-xs"></i></button>
                    <button @click="viewMode = 'compact'" :class="viewMode === 'compact' ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-400 hover:text-blue-600'" class="w-8 h-8 rounded-xl flex items-center justify-center transition-all mx-1"><i class="fa-solid fa-th text-xs"></i></button>
                    <button @click="viewMode = 'list'" :class="viewMode === 'list' ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-400 hover:text-blue-600'" class="w-8 h-8 rounded-xl flex items-center justify-center transition-all"><i class="fa-solid fa-list text-xs"></i></button>
                </div>

                {{-- Folder Creation --}}
                <button @click="openNewFolder()" class="w-10 h-10 rounded-2xl bg-white text-emerald-500 hover:bg-emerald-500 hover:text-white transition-all shadow-sm flex items-center justify-center border border-slate-100">
                    <i class="fa-solid fa-folder-plus text-xs"></i>
                </button>

                {{-- Search --}}
                <div class="relative hidden md:block">
                    <input type="text" x-model="search" @input.debounce.300ms="fetchMedia()" placeholder="Tìm kiếm tệp..." 
                           class="w-48 pl-9 pr-4 py-2.5 bg-white border border-slate-200 rounded-2xl text-[10px] font-black uppercase tracking-widest focus:ring-4 focus:ring-blue-50 focus:border-blue-400 outline-none transition-all">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-300 text-[10px]"></i>
                </div>

                {{-- Upload btn --}}
                <button @click="$refs.mediaFileInput.click()" 
                        class="px-6 py-2.5 rounded-2xl bg-slate-900 text-white text-[11px] font-black uppercase tracking-widest hover:bg-blue-600 transition-all shadow-lg shadow-slate-900/10 flex items-center gap-2">
                    <i x-show="uploading" class="fa-solid fa-spinner fa-spin"></i>
                    <i x-show="!uploading" class="fa-solid fa-cloud-arrow-up"></i>
                    <span x-text="uploading ? 'Đang tải...' : 'Tải lên'"></span>
                </button>
                <input type="file" x-ref="mediaFileInput" @change="uploadFile($event)" class="hidden" accept="image/*" multiple>

                <button @click="show = false" class="w-10 h-10 rounded-2xl bg-white text-slate-400 hover:bg-rose-500 hover:text-white transition-all shadow-sm flex items-center justify-center border border-slate-100">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        </div>

        <div class="flex flex-1 overflow-hidden">
            {{-- Main Content: Grid --}}
            <div class="flex-1 flex flex-col bg-white overflow-hidden relative">
                {{-- Loader --}}
                <div x-show="loading" class="absolute inset-0 bg-white/60 backdrop-blur-sm z-10 flex items-center justify-center">
                    <div class="w-10 h-10 border-4 border-blue-100 border-t-blue-600 rounded-full animate-spin text-[0]">.</div>
                </div>

                <div class="flex-1 overflow-y-auto p-8 scroll-smooth custom-scroll">
                    {{-- Navigation Bar for Picker --}}
                    <div class="flex items-center gap-3 mb-6">
                        <template x-if="currentFolderId">
                            <button @click="goUp()" class="px-5 py-2.5 rounded-2xl bg-slate-50 border border-slate-100 text-[10px] font-black uppercase text-slate-400 hover:bg-blue-600 hover:text-white transition-all shadow-sm flex items-center gap-2">
                                <i class="fa-solid fa-arrow-left"></i> Quay lại
                            </button>
                        </template>
                        <template x-for="r in roots" :key="r.id">
                            <button @click="setFolder(r.id)" 
                                    :class="currentFolderId === r.id ? 'bg-blue-600 text-white shadow-lg' : 'bg-slate-50 text-slate-500 hover:bg-slate-100'"
                                    class="px-5 py-2.5 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all"
                                    x-text="r.name"></button>
                        </template>
                    </div>

                    {{-- Grid View --}}
                    <div x-show="viewMode === 'grid' || viewMode === 'compact'" 
                         :class="viewMode === 'compact' ? 'grid-cols-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-4' : 'grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6'"
                         class="grid">
                        
                        {{-- Folder Cards --}}
                        <template x-for="f in subFolders" :key="'f-'+f.id">
                            <div @dblclick="setFolder(f.id)" 
                                 class="group relative aspect-square bg-slate-50/50 rounded-[32px] border-2 border-transparent hover:border-blue-300 cursor-pointer overflow-hidden transition-all flex flex-col items-center justify-center shadow-sm">
                                
                                <template x-if="editingId === 'folder-'+f.id">
                                    <div class="px-4 w-full">
                                        <input type="text" :id="'rename-folder-'+f.id" :value="f.name" 
                                               @keyup.enter="saveRename(f.id, true)" @click.stop
                                               class="w-full text-[10px] font-bold text-center border-b border-blue-500 outline-none bg-transparent py-1">
                                    </div>
                                </template>
                                <template x-if="editingId !== 'folder-'+f.id">
                                    <div class="flex flex-col items-center">
                                        <i class="fa-solid fa-folder-open text-4xl mb-3" :style="`color: ${f.color || '#3b82f6'}`"></i>
                                        <span class="text-[10px] font-black uppercase text-slate-500 text-center px-4 tracking-tighter" x-text="f.name"></span>
                                    </div>
                                </template>

                                {{-- Action overlay --}}
                                <div class="absolute inset-x-0 bottom-0 py-2 bg-white/90 backdrop-blur-sm border-t border-slate-100 flex justify-center gap-4 translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                                    <button @click.stop="editingId = 'folder-'+f.id" class="text-slate-400 hover:text-blue-500 transition-colors"><i class="fa-solid fa-pencil text-[10px]"></i></button>
                                    <button @click.stop="deleteFolder(f.id)" class="text-slate-400 hover:text-rose-500 transition-colors"><i class="fa-solid fa-trash-can text-[10px]"></i></button>
                                </div>
                            </div>
                        </template>

                        {{-- File Cards --}}
                        <template x-for="item in items" :key="item.id">
                            <div @click="selectItem(item)" 
                                 class="group relative aspect-square bg-slate-50 rounded-[32px] border-2 border-transparent hover:border-blue-500 cursor-pointer overflow-hidden transition-all shadow-sm hover:shadow-xl hover:-translate-y-1 overflow-hidden"
                                 :class="selectedItem?.id === item.id ? 'border-blue-500 ring-4 ring-blue-50' : ''">
                                
                                {{-- Preview --}}
                                <template x-if="isImage(item)">
                                    <img :src="item.url" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                </template>
                                <template x-if="!isImage(item)">
                                    <div class="w-full h-full flex flex-col items-center justify-center bg-slate-100">
                                        <i class="fa-solid fa-file-invoice text-3xl text-slate-200 mb-2"></i>
                                        <span class="text-[9px] font-black text-slate-400 uppercase px-3 text-center" x-text="item.mime.split('/')[1]"></span>
                                    </div>
                                </template>

                                {{-- Name during rename --}}
                                <div x-show="editingId === 'file-'+item.id" class="absolute inset-0 bg-white/90 backdrop-blur-sm flex items-center justify-center p-4" @click.stop>
                                    <input type="text" :id="'rename-file-'+item.id" :value="item.name" 
                                           @keyup.enter="saveRename(item.id, false)"
                                           class="w-full text-center text-[10px] font-black uppercase text-slate-900 border-b border-blue-500 outline-none bg-transparent">
                                </div>

                                {{-- Overlay info --}}
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex flex-col justify-end p-4">
                                    <p class="text-[9px] text-white font-black truncate uppercase tracking-tighter leading-none mb-1" x-text="item.name"></p>
                                    
                                    {{-- Mini Actions --}}
                                    <div class="flex gap-4 mt-2 mb-1">
                                        <button @click.stop="editingId = 'file-'+item.id" class="text-white hover:text-blue-400 transition-colors"><i class="fa-solid fa-pencil text-[10px]"></i></button>
                                        <button @click.stop="deleteItem(item.id)" class="text-white hover:text-rose-400 transition-colors"><i class="fa-solid fa-trash-can text-[10px]"></i></button>
                                        <button @click.stop="window.open(item.url, '_blank')" class="text-white hover:text-emerald-400 transition-colors ml-auto"><i class="fa-solid fa-eye text-[10px]"></i></button>
                                    </div>
                                </div>

                                {{-- Selected Check --}}
                                <div x-show="selectedItem?.id === item.id" class="absolute top-3 right-3 w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-xs shadow-lg">
                                    <i class="fa-solid fa-check"></i>
                                </div>
                            </div>
                        </template>
                    </div>

                    {{-- List View --}}
                    <div x-show="viewMode === 'list'" class="bg-white rounded-3xl border border-slate-100 overflow-hidden shadow-sm">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-slate-50 border-b border-slate-100 text-[10px] font-black uppercase text-slate-400 tracking-widest">
                                <tr>
                                    <th class="px-6 py-4">Tên tệp/thư mục</th>
                                    <th class="px-6 py-4">Dung lượng</th>
                                    <th class="px-6 py-4">Định dạng</th>
                                    <th class="px-6 py-4 text-right">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="f in subFolders" :key="'fl-'+f.id">
                                    <tr @dblclick="setFolder(f.id)" class="hover:bg-slate-50 border-b border-slate-100 transition-colors group">
                                        <td class="px-6 py-3 font-bold text-slate-700 flex items-center gap-3">
                                            <i class="fa-solid fa-folder text-amber-400"></i>
                                            <span x-text="f.name"></span>
                                        </td>
                                        <td class="px-6 py-3 text-slate-400 uppercase">--</td>
                                        <td class="px-6 py-3 text-slate-400 uppercase tracking-widest text-[9px]">Folder</td>
                                        <td class="px-6 py-3 text-right">
                                            <button @click="setFolder(f.id)" class="text-[9px] font-black uppercase text-blue-600 px-3 py-1.5 rounded-lg bg-blue-50">Mở</button>
                                        </td>
                                    </tr>
                                </template>
                                <template x-for="item in items" :key="'il-'+item.id">
                                    <tr @click="selectItem(item)" 
                                        :class="selectedItem?.id === item.id ? 'bg-blue-50/50' : ''"
                                        class="hover:bg-slate-50 border-b border-slate-100 transition-colors group cursor-pointer">
                                        <td class="px-6 py-4 flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl bg-slate-100 overflow-hidden border border-slate-50 shrink-0">
                                                <template x-if="isImage(item)">
                                                    <img :src="item.url" class="w-full h-full object-cover">
                                                </template>
                                                <template x-if="!isImage(item)">
                                                    <div class="w-full h-full flex items-center justify-center"><i class="fa-solid fa-file text-slate-300"></i></div>
                                                </template>
                                            </div>
                                            <div class="flex flex-col">
                                                <span class="font-black text-slate-800 uppercase tracking-tight truncate max-w-[200px]" x-text="item.name"></span>
                                                <span class="text-[9px] text-slate-400" x-text="item.url.split('/').pop()"></span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-slate-500 font-bold" x-text="formatSize(item.size)"></td>
                                        <td class="px-6 py-4">
                                            <span class="text-[9px] font-black px-2 py-1 rounded bg-slate-100 text-slate-400 uppercase tracking-widest" x-text="item.mime.split('/')[1]"></span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                                <button @click.stop="editingId = 'file-'+item.id" class="w-8 h-8 rounded-lg bg-white shadow-sm border border-slate-100 text-slate-400 hover:text-blue-500"><i class="fa-solid fa-pencil text-[10px]"></i></button>
                                                <button @click.stop="deleteItem(item.id)" class="w-8 h-8 rounded-lg bg-white shadow-sm border border-slate-100 text-slate-400 hover:text-rose-500"><i class="fa-solid fa-trash-can text-[10px]"></i></button>
                                                <button @click.stop="confirmSelection()" class="px-3 py-1.5 rounded-lg bg-blue-600 text-white text-[9px] font-black uppercase tracking-widest ml-1">Chọn</button>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    {{-- Empty State --}}
                    <div x-show="!loading && items.length === 0 && subFolders.length === 0" class="flex flex-col items-center justify-center h-full py-28 text-slate-200">
                        <i class="fa-solid fa-cloud-bolt-moon text-7xl mb-6 opacity-20"></i>
                        <p class="text-xs font-black uppercase tracking-[0.3em] text-slate-300">Không tìm thấy mục nào</p>
                    </div>
                </div>

                {{-- Footer: Actions --}}
                <div class="px-8 py-5 bg-slate-50 border-t border-slate-100 flex items-center justify-between shrink-0">
                    <div class="text-[10px] text-slate-400">
                        <span x-show="selectedItem" class="font-black text-slate-600 uppercase tracking-widest italic">Đã chọn: <span x-text="selectedItem?.name"></span></span>
                        <span x-show="!selectedItem" class="font-bold uppercase tracking-widest">Chưa chọn tệp nào</span>
                    </div>
                    <div class="flex gap-3">
                        <button @click="show = false" class="px-6 py-2.5 text-[10px] font-black text-slate-500 uppercase tracking-widest hover:bg-slate-200 rounded-xl transition-colors italic">Hủy bỏ</button>
                        <button @click="confirmSelection()" 
                                :disabled="!selectedItem"
                                :class="selectedItem ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/20' : 'bg-slate-200 text-slate-400 cursor-not-allowed'"
                                class="px-10 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-[.2em] transition-all italic">
                            Sử dụng tệp này
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function mediaPickerData() {
        return {
            show: false,
            loading: false,
            items: [],
            subFolders: [],
            roots: [],
            currentFolderId: null,
            search: '',
            selectedItem: null,
            targetId: null,
            callback: null,
            uploading: false,
            viewMode: 'grid',
            editingId: null,

            init() {
                window.openMediaPicker = (id, cb) => {
                    this.targetId = id;
                    this.callback = cb;
                    this.selectedItem = null;
                    this.show = true;
                    this.fetchMedia();
                };
            },

            async uploadFile(e) {
                const files = e.target.files;
                if (!files.length) return;

                const formData = new FormData();
                for (let i = 0; i < files.length; i++) {
                    formData.append('files[]', files[i]);
                }
                formData.append('folder_id', this.currentFolderId || '');
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

                this.uploading = true;
                try {
                    const response = await fetch('{{ route('admin.media.store') }}', {
                        method: 'POST',
                        body: formData,
                        headers: { 'Accept': 'application/json' }
                    });
                    const data = await response.json();
                    
                    e.target.value = ''; // clear input
                    await this.fetchMedia();
                    
                    // Auto select the FIRST of the newly uploaded files
                    if (data.items && data.items.length > 0) {
                        const newItem = this.items.find(i => i.id === data.items[0].id);
                        if (newItem) this.selectItem(newItem);
                    }
                } catch (err) {
                    alert('Lỗi tải lên: ' + err.message);
                } finally {
                    this.uploading = false;
                }
            },

            async fetchMedia() {
                this.loading = true;
                try {
                    const params = new URLSearchParams({ 
                        folder_id: this.currentFolderId || '',
                        search: this.search
                    });
                    const response = await fetch(`/admin/media/picker?${params.toString()}`);
                    const data = await response.json();
                    this.items = data.items || [];
                    this.subFolders = data.folders || [];
                    this.roots = data.roots || [];
                } catch (e) {
                    console.error('Picker error:', e);
                } finally {
                    this.loading = false;
                }
            },

            setFolder(id) {
                this.currentFolderId = id;
                this.fetchMedia();
            },

            async goUp() {
                if (!this.currentFolderId) return;
                try {
                    const response = await fetch(`/admin/media/folder/${this.currentFolderId}/parent`);
                    const data = await response.json();
                    this.setFolder(data.parent_id);
                } catch (e) {
                    this.setFolder(null);
                }
            },

            selectItem(item) {
                this.selectedItem = item;
                this.editingId = null;
            },

            async openNewFolder() {
                const name = prompt('Nhập tên thư mục mới:');
                if (!name) return;

                try {
                    const response = await fetch("{{ route('admin.media.create-folder') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            name: name,
                            parent_id: this.currentFolderId
                        })
                    });
                    if (response.ok) this.fetchMedia();
                } catch(e) {}
            },

            async saveRename(id, isFolder) {
                const inputId = isFolder ? `rename-folder-${id}` : `rename-file-${id}`;
                const input = document.getElementById(inputId);
                const newName = input.value.trim();
                if (!newName) return (this.editingId = null);

                const route = isFolder
                    ? "{{ route('admin.media.index') }}/folder/" + id + "/rename"
                    : "{{ route('admin.media.index') }}/file/" + id + "/rename";

                try {
                    const res = await fetch(route, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ name: newName })
                    });
                    if (res.ok) this.fetchMedia();
                } catch(e) {}
                this.editingId = null;
            },

            async deleteFolder(id) {
                if (!confirm('Xóa thư mục này?')) return;
                try {
                    const res = await fetch("{{ route('admin.media.index') }}/folder/" + id, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                    });
                    if (res.ok) this.fetchMedia();
                } catch(e) {}
            },

            async deleteItem(id) {
                if (!confirm('Xóa tệp này?')) return;
                try {
                    const res = await fetch("{{ route('admin.media.index') }}/" + id, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                    });
                    if (res.ok) {
                        this.fetchMedia();
                        if (this.selectedItem?.id === id) this.selectedItem = null;
                    }
                } catch(e) {}
            },

            confirmSelection() {
                if (!this.selectedItem) return;
                
                if (this.targetId) {
                    const input = document.getElementById(this.targetId);
                    if (input) {
                        input.value = this.selectedItem.url;
                        input.dispatchEvent(new Event('input'));
                        input.dispatchEvent(new Event('change'));
                        
                        // Cập nhật preview nếu có (tự động dự đoán element)
                        const previewId = this.targetId + '_preview';
                        if (window.updateImgPreview) {
                            window.updateImgPreview(previewId, this.selectedItem.url);
                        }
                    }
                }

                if (this.callback && typeof this.callback === 'function') {
                    this.callback(this.selectedItem.url, this.selectedItem);
                }

                this.show = false;
            },

            isImage(item) {
                return (item.mime || '').startsWith('image/');
            },

            formatSize(bytes) {
                if (!bytes) return '0 B';
                const k = 1024;
                const sizes = ['B', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
            }
        };
    }
</script>

<style>
    [x-cloak] { display: none !important; }
</style>
@endonce
