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
                <h3 class="text-xl font-black text-slate-900 tracking-tighter uppercase">Thư viện phương tiện</h3>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Chọn ảnh hoặc tệp tin để chèn vào nội dung</p>
            </div>
            <div class="flex items-center gap-3">
                {{-- Search --}}
                <div class="relative hidden md:block">
                    <input type="text" x-model="search" @input.debounce.300ms="fetchMedia()" placeholder="Tìm kiếm tệp..." 
                           class="w-64 pl-9 pr-4 py-2 bg-white border border-slate-200 rounded-2xl text-xs font-bold focus:ring-4 focus:ring-blue-50 focus:border-blue-400 outline-none transition-all">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-300 text-[10px]"></i>
                </div>
                {{-- Upload btn --}}
                <div class="relative">
                    <button @click="$refs.mediaFileInput.click()" 
                            class="px-5 py-2.5 rounded-2xl bg-blue-600 text-white text-[11px] font-black uppercase tracking-widest hover:bg-blue-700 transition-all shadow-lg shadow-blue-500/20 flex items-center gap-2">
                        <template x-if="uploading">
                            <i class="fa-solid fa-spinner fa-spin"></i>
                        </template>
                        <template x-if="!uploading">
                            <i class="fa-solid fa-cloud-arrow-up"></i>
                        </template>
                        <span x-text="uploading ? 'Đang tải...' : 'Tải tệp mới'"></span>
                    </button>
                    <input type="file" x-ref="mediaFileInput" @change="uploadFile($event)" class="hidden" accept="image/*" multiple>
                </div>

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

                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6">
                        {{-- Folder Cards --}}
                        <template x-for="f in subFolders" :key="'f-'+f.id">
                            <div @dblclick="setFolder(f.id)" 
                                 class="group relative aspect-square bg-slate-50/50 rounded-[32px] border-2 border-transparent hover:border-blue-300 cursor-pointer overflow-hidden transition-all flex flex-col items-center justify-center shadow-sm">
                                <i class="fa-solid text-4xl mb-3" :class="f.icon || 'fa-folder-open'" :style="`color: ${f.color || '#3b82f6'}`"></i>
                                <span class="text-[10px] font-black uppercase text-slate-500 text-center px-4 tracking-tighter" x-text="f.name"></span>
                                <div class="absolute inset-0 bg-blue-500/5 opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity"></div>
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

                                {{-- Overlay info --}}
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex flex-col justify-end p-4">
                                    <p class="text-[9px] text-white font-black truncate uppercase tracking-tighter leading-none mb-1" x-text="item.name"></p>
                                    <p class="text-[8px] text-white/70 font-bold uppercase tracking-widest" x-text="formatSize(item.size)"></p>
                                </div>

                                {{-- Selected Check --}}
                                <div x-show="selectedItem?.id === item.id" class="absolute top-3 right-3 w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-xs shadow-lg">
                                    <i class="fa-solid fa-check"></i>
                                </div>
                            </div>
                        </template>
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
                        <template x-if="selectedItem">
                            <span class="font-black text-slate-600 uppercase tracking-widest italic">Đã chọn: <span x-text="selectedItem.name"></span></span>
                        </template>
                        <template x-if="!selectedItem">
                            <span class="font-bold uppercase tracking-widest">Đang tải toàn bộ thư viện...</span>
                        </template>
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
