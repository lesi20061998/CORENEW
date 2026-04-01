@extends('admin.layouts.app')
@section('title', 'Quản trị Menu')

@section('content')
<div class="h-full bg-slate-50 -m-5 flex flex-col overflow-hidden text-[11.5px] relative" 
     x-data="menuManager({{ json_encode($menus) }}, {{ $menu->id ?? 'null' }}, {{ json_encode($categories) }}, {{ json_encode($pages) }}, {{ json_encode($posts) }}, '{{ $currentLocale }}')">
    
    {{-- Top Header Bar --}}
    <div class="bg-slate-900 text-white px-8 py-4 flex items-center justify-between shrink-0">
        <div class="flex items-center gap-6">
            <button @click="showAddMenu = true" class="bg-blue-600 hover:bg-blue-500 px-6 py-2.5 rounded-2xl text-[11px] font-black uppercase tracking-widest shadow-xl shadow-blue-500/20 active:scale-95 transition-all flex items-center gap-3">
                <i class="fa-solid fa-plus-circle"></i> Thêm menu hệ thống
            </button>
            <div class="h-6 w-[1px] bg-white/10 mx-2"></div>
            <div class="flex items-center gap-2 bg-white/5 p-1 rounded-xl border border-white/10">
                <a href="{{ route('admin.menus.index', ['locale' => 'vi']) }}" 
                   :class="currentLocale === 'vi' ? 'bg-white text-slate-900 shadow-lg' : 'text-white/40 hover:text-white'"
                   class="px-4 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all">VI</a>
                <a href="{{ route('admin.menus.index', ['locale' => 'en']) }}"
                   :class="currentLocale === 'en' ? 'bg-white text-slate-900 shadow-lg' : 'text-white/40 hover:text-white'"
                   class="px-4 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all">EN</a>
            </div>
        </div>
        <div class="text-[10px] text-white/40 font-black uppercase tracking-[0.3em] flex items-center gap-3">
            <i class="fa-solid fa-folder-tree text-blue-500"></i> Cấu trúc menu đa tầng
        </div>
    </div>

    <div class="flex-1 flex overflow-hidden">
        
        {{-- COL 1: SIDEBAR --}}
        <div class="w-64 bg-white border-r border-slate-100 flex flex-col p-4 space-y-2 overflow-hidden shadow-sm">
            <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4 flex items-center gap-2 pl-2">
                <span class="w-1 h-3 bg-blue-600 rounded-full"></span> Danh sách menu
            </h3>
            <div class="flex-1 overflow-y-auto space-y-2 custom-scroll">
                <template x-for="m in filteredMenus" :key="m.id">
                    <div class="group flex items-center gap-2">
                        <div @click="switchMenu(m.id)"
                            :class="activeMenuId === m.id ? 'bg-blue-600 text-white shadow-xl shadow-blue-500/20' : 'bg-slate-50 text-slate-600 hover:bg-white hover:shadow-md border border-transparent hover:border-slate-100'"
                            class="p-4 rounded-2xl flex-1 flex items-center gap-3 cursor-pointer transition-all">
                            <i class="fa-solid fa-folder-tree text-[9px] opacity-40"></i>
                            <span class="font-black truncate uppercase text-[11px] tracking-tight" x-text="m.name"></span>
                        </div>
                        <button @click.stop="deleteMenu(m.id)" class="w-10 h-10 rounded-xl hover:bg-rose-50 text-rose-300 hover:text-rose-600 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-all bg-slate-50/50">
                            <i class="fa-solid fa-trash-can text-[11px]"></i>
                        </button>
                    </div>
                </template>
            </div>
        </div>

        {{-- COL 2: CHỌN LỰA (SOURCE) --}}
        <div class="w-64 bg-slate-50/50 border-r border-slate-100 flex flex-col overflow-hidden">
            <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] py-5 px-6 flex items-center gap-2 bg-white border-b border-slate-100">
                <i class="fa-solid fa-plus-circle text-blue-600"></i> Nguồn liên kết
            </h3>
            <div class="flex-1 overflow-y-auto p-6 space-y-6 custom-scroll">
                
                <div x-data="{ open: true }">
                    <button @click="open = !open" class="w-full flex items-center justify-between text-[10px] font-bold text-slate-500 hover:text-blue-600 py-1 border-b border-slate-200 mb-2 uppercase tracking-widest">
                        Trang Nội Dung <i class="fa-solid fa-chevron-down text-[8px]" :class="open ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="open" x-cloak class="space-y-1">
                        <template x-for="p in pages" :key="p.id">
                            <div @click="addItem('Page', p.label, '/'+p.slug)" class="px-3 py-1.5 bg-white hover:bg-blue-50 border border-slate-200 text-[10.5px] text-slate-600 cursor-pointer flex items-center justify-between group">
                                <span class="truncate" x-text="p.label"></span>
                                <i class="fa-solid fa-plus text-[8px] opacity-0 group-hover:opacity-40"></i>
                            </div>
                        </template>
                    </div>
                </div>

                <div x-data="{ open: true }">
                    <button @click="open = !open" class="w-full flex items-center justify-between text-[10px] font-bold text-slate-500 hover:text-blue-600 py-1 border-b border-slate-200 mb-2 uppercase tracking-widest">
                        Danh mục sản phẩm <i class="fa-solid fa-chevron-down text-[8px]" :class="open ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="open" x-cloak class="space-y-1">
                        <template x-for="c in categories.filter(c => c.type === 'product')" :key="c.id">
                            <div @click="addItem('ProductCat', c.label, '/'+c.slug)" class="px-3 py-1.5 bg-white hover:bg-blue-50 border border-slate-200 text-[10.5px] text-slate-600 cursor-pointer flex items-center justify-between group">
                                <span class="truncate" x-text="c.label"></span>
                                <i class="fa-solid fa-plus text-[8px] opacity-0 group-hover:opacity-40"></i>
                            </div>
                        </template>
                    </div>
                </div>

                <div x-data="{ open: true, label: '', url: '' }">
                    <button @click="open = !open" class="w-full flex items-center justify-between text-[10px] font-bold text-slate-500 hover:text-blue-600 py-1 border-b border-slate-200 mb-2 uppercase tracking-widest">
                        Liên kết thủ công <i class="fa-solid fa-chevron-down text-[8px]" :class="open ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="open" x-cloak class="mt-2 space-y-2 p-3 bg-white border border-slate-200 rounded">
                        <div class="space-y-1">
                            <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest pl-1">Tên nhãn</label>
                            <input type="text" x-model="label" class="form-input text-[10.5px] !py-1" placeholder="vd: Khuyến mãi">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest pl-1">URL</label>
                            <input type="text" x-model="url" class="form-input text-[10.5px] !py-1" placeholder="/">
                        </div>
                        <button @click="addItem('Link', label, url); label=''; url='';" class="w-full bg-slate-800 text-white text-[9px] font-bold py-1.5 rounded uppercase hover:bg-blue-600 transition-colors mt-1">Thêm vào</button>
                    </div>
                </div>

            </div>
        </div>

        {{-- COL 3: STRUCT (CAN NEST) --}}
        <div class="flex-1 bg-white flex flex-col overflow-hidden">
            <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] py-5 px-8 flex items-center gap-2 border-b border-slate-100">
                <i class="fa-solid fa-sitemap text-blue-600"></i> Cấu trúc hiển thị
            </h3>
            
            <div class="flex-1 overflow-y-auto p-10 custom-scroll">
                <template x-if="!activeMenuId">
                    <div class="flex flex-col items-center justify-center py-24 text-slate-300 font-black uppercase tracking-[0.3em] space-y-4">
                        <i class="fa-solid fa-mouse-pointer text-4xl opacity-20"></i>
                        <p>Chọn Menu để thiết kế</p>
                    </div>
                </template>

                <template x-if="activeMenuId">
                    <div class="max-w-4xl mx-auto">
                        
                        {{-- RECURSIVE TEMPLATE FOR MENU ITEMS --}}
                        <div id="nested-menu-root" class="space-y-1 min-h-[100px]">
                            <template x-for="(item, index) in items" :key="'lvl1-'+index">
                                <div class="menu-node" :data-path="index">
                                    {{-- Item Bar --}}
                                    <div class="flex items-center bg-[#e2e8f0] border border-slate-300 p-2 pl-3 rounded shadow-sm cursor-move relative transition-all group">
                                        <div class="flex-1 flex items-center justify-between pr-2">
                                            <span class="font-bold text-slate-800" x-text="item.label"></span>
                                            <div class="flex items-center gap-10">
                                                <span class="text-[9px] text-slate-400" x-text="item.type"></span>
                                                <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                                    <button @click="removeItem(index)" class="w-7 h-7 bg-red-500 text-white rounded flex items-center justify-center hover:bg-red-600 shadow"><i class="fa-solid fa-trash text-[10px]"></i></button>
                                                    <button @click="editItem(item, index)" class="w-7 h-7 bg-blue-500 text-white rounded flex items-center justify-center hover:bg-blue-600 shadow"><i class="fa-solid fa-pencil text-[10px]"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    {{-- Lvl 2 Children --}}
                                    <div class="nested-zone pl-10 pt-1 space-y-1 min-h-[15px] border-l-2 border-slate-100 ml-4 group-hover:border-blue-100 transition-all" :data-path="index">
                                        <template x-for="(child, cIdx) in (item.children || [])" :key="'lvl2-'+index+'-'+cIdx">
                                            <div class="menu-node" :data-path="index+'.'+cIdx">
                                                <div class="flex items-center bg-[#e2e8f0] border border-slate-300 p-2 pl-3 rounded shadow-sm cursor-move group">
                                                    <div class="flex-1 flex items-center justify-between pr-2">
                                                        <span class="font-bold text-slate-700" x-text="child.label"></span>
                                                        <div class="flex items-center gap-10">
                                                            <span class="text-[8px] text-slate-400" x-text="child.type"></span>
                                                            <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                                                <button @click="removeItem(index, cIdx)" class="w-6 h-6 bg-red-500 text-white rounded flex items-center justify-center shadow"><i class="fa-solid fa-trash text-[9px]"></i></button>
                                                                <button @click="editItem(child, index, cIdx)" class="w-6 h-6 bg-blue-500 text-white rounded flex items-center justify-center shadow"><i class="fa-solid fa-pencil text-[9px]"></i></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Lvl 3 Children --}}
                                                <div class="nested-zone pl-10 pt-1 space-y-1 min-h-[5px] border-l border-slate-100 ml-5" :data-path="index+'.'+cIdx">
                                                    <template x-for="(grand, gIdx) in (child.children || [])" :key="'lvl3-'+index+'-'+cIdx+'-'+gIdx">
                                                        <div class="menu-node" :data-path="index+'.'+cIdx+'.'+gIdx">
                                                            <div class="flex items-center bg-[#e2e8f0] border border-slate-300 p-1.5 pl-3 rounded shadow-sm cursor-move group">
                                                                <div class="flex-1 flex items-center justify-between pr-2">
                                                                    <span class="font-bold text-slate-600" x-text="grand.label"></span>
                                                                    <div class="flex items-center gap-10">
                                                                        <span class="text-[8px] text-slate-300" x-text="grand.type"></span>
                                                                        <div class="flex items-center gap-0.5 opacity-0 group-hover:opacity-100 transition-opacity">
                                                                            <button @click="removeItem(index, cIdx, gIdx)" class="w-5 h-5 bg-red-500 text-white rounded flex items-center justify-center"><i class="fa-solid fa-xmark text-[8px]"></i></button>
                                                                            <button @click="editItem(grand, index, cIdx, gIdx)" class="w-5 h-5 bg-blue-500 text-white rounded flex items-center justify-center"><i class="fa-solid fa-gear text-[8px]"></i></button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            {{-- Lvl 4 --}}
                                                            <div class="nested-zone pl-10 pt-1 space-y-1 min-h-[5px] border-l border-slate-100 ml-5" :data-path="index+'.'+cIdx+'.'+gIdx">
                                                                <template x-for="(lvl4, l4Idx) in (grand.children || [])" :key="'lvl4-'+index+'-'+l4Idx">
                                                                    <div class="menu-node" :data-path="index+'.'+cIdx+'.'+gIdx+'.'+l4Idx">
                                                                        <div class="flex items-center bg-[#e2e8f0] border border-slate-300 p-1 pl-3 rounded shadow-sm cursor-move group">
                                                                            <span class="font-bold text-slate-500 flex-1 truncate" x-text="lvl4.label"></span>
                                                                            <button @click="removeItem(index, cIdx, gIdx, l4Idx)" class="w-4 h-4 text-red-400 hover:text-red-600"><i class="fa-solid fa-xmark"></i></button>
                                                                        </div>
                                                                    </div>
                                                                </template>
                                                            </div>
                                                        </div>
                                                    </template>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <div class="mt-20 border-t border-slate-100 pt-8">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Vị trí hiển thị</p>
                            <div class="grid grid-cols-4 gap-4">
                                @php $areas = [
                                    'header' => 'Menu Chính',
                                    'mobile' => 'Menu Mobile',
                                    'header_top' => 'Header Top',
                                    'topbar' => 'Topbar'
                                ]; @endphp
                                @foreach($areas as $key => $label)
                                    <label class="flex flex-col items-center gap-2 p-4 bg-slate-50 border border-slate-100 rounded-xl cursor-pointer hover:border-blue-500 transition-all group">
                                        <input type="radio" value="{{ $key }}" x-model="activeMenuArea" class="hidden peer">
                                        <div class="w-8 h-8 rounded-full border-2 border-slate-200 peer-checked:border-blue-600 peer-checked:bg-blue-600 flex items-center justify-center transition-all">
                                            <i class="fa-solid fa-check text-white text-[10px] scale-0 peer-checked:scale-100"></i>
                                        </div>
                                        <span class="text-[10px] font-bold text-slate-500 group-hover:text-blue-600">{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                    </div>
                </template>
            </div>
        </div>

        {{-- COL 4: UPDATE --}}
        <div class="w-56 bg-slate-50/50 border-l border-slate-100 flex flex-col overflow-hidden">
            <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] py-5 text-center bg-white border-b border-slate-100">Xác nhận</h3>
            <div class="p-4 space-y-4">
                <button @click="saveMenu()" id="btnSave" class="w-full bg-blue-700 hover:bg-blue-800 text-white font-black py-2.5 rounded text-[10.5px] shadow-xl shadow-blue-500/20 active:scale-95 transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i> LƯU MENU
                </button>
            </div>
        </div>

    </div>

    {{-- MODALS --}}
    <div x-show="showAddMenu" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-sm">
        <div class="bg-white rounded-lg p-8 w-full max-w-sm shadow-2xl">
            <h3 class="text-sm font-black mb-6 uppercase tracking-widest">Tạo Menu Mới</h3>
            <input type="text" x-model="newMenuName" class="form-input text-sm !py-3 rounded-xl border-slate-200 bg-slate-50" placeholder="Tên menu">
            <div class="mt-8 flex justify-end gap-3">
                <button @click="showAddMenu = false" class="text-slate-400 font-bold uppercase p-2 text-[10px]">Hủy</button>
                <button @click="createMenu()" class="bg-blue-600 text-white font-black px-6 py-2.5 rounded-xl text-[10px] shadow-lg shadow-blue-500/20">KHỞI TẠO</button>
            </div>
        </div>
    </div>
    
    <div x-show="showEditItem" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-sm">
        <div class="bg-white rounded-lg p-8 w-full max-w-md shadow-2xl">
            <h3 class="text-sm font-black mb-8 border-b border-slate-100 pb-4 uppercase tracking-widest">Cấu hình liên kết</h3>
            <div class="space-y-6">
                <div>
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-2">Nhãn hiển thị</label>
                    <input type="text" x-model="editingItemData.label" class="form-input text-xs !py-3 rounded-xl border-slate-200">
                </div>
                <div>
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-2">Đường dẫn đích (URL)</label>
                    <input type="text" x-model="editingItemData.url" class="form-input text-xs !py-3 rounded-xl border-slate-200">
                </div>
            </div>
            <div class="mt-10 flex justify-end gap-3 pt-6 border-t border-slate-100">
                <button @click="showEditItem = false" class="text-slate-400 font-bold uppercase text-[10px]">Đóng</button>
                <button @click="finishEditItem()" class="bg-blue-600 text-white font-black px-8 py-2.5 rounded-xl text-[10px] shadow-lg shadow-blue-500/20 uppercase tracking-widest">Cập nhật</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
function menuManager(menus, activeId, categories, pages, posts, currentLocale) {
    return {
        menus: menus,
        filteredMenus: menus,
        currentLocale: currentLocale,
        activeMenuId: activeId,
        activeMenu: null,
        activeMenuArea: '',
        items: [],
        categories: categories,
        pages: pages,
        posts: posts,
        
        showAddMenu: false,
        newMenuName: '',
        showEditItem: false,
        editingItemData: { label: '', url: '', type: '' },
        editPath: [],

        init() {
            if (this.activeMenuId) {
                this.loadMenu(this.activeMenuId);
                this.$nextTick(() => this.initSortable());
            }
        },

        initSortable() {
            // Root
            const rootEl = document.getElementById('nested-menu-root');
            if(rootEl) {
                new Sortable(rootEl, {
                    group: 'menu-nesting',
                    animation: 200,
                    ghostClass: 'sortable-ghost',
                    chosenClass: 'sortable-chosen',
                    dragClass: 'sortable-drag',
                    fallbackOnBody: true,
                    swapThreshold: 0.65,
                    onEnd: (evt) => { this.handleDragEnd(evt); }
                });
            }

            // Zones
            const zones = document.querySelectorAll('.nested-zone');
            zones.forEach(zone => {
                new Sortable(zone, {
                    group: 'menu-nesting',
                    animation: 200,
                    ghostClass: 'sortable-ghost',
                    chosenClass: 'sortable-chosen',
                    dragClass: 'sortable-drag',
                    swapThreshold: 0.65,
                    onEnd: (evt) => { this.handleDragEnd(evt); }
                });
            });
        },

        handleDragEnd(evt) {
            const fromPath = evt.from.getAttribute('data-path'); // null for root, "0", "0.1" for zones
            const toPath = evt.to.getAttribute('data-path');
            const oldIdx = evt.oldIndex;
            const newIdx = evt.newIndex;

            if (fromPath === toPath && oldIdx === newIdx) return;

            // Deep clone items
            let newItems = JSON.parse(JSON.stringify(this.items));
            
            // Get source item
            let sourceList = this.getListByPath(newItems, fromPath);
            let targetList = this.getListByPath(newItems, toPath);
            
            const [item] = sourceList.splice(oldIdx, 1);
            targetList.splice(newIdx, 0, item);
            
            this.items = newItems;
            this.$nextTick(() => this.initSortable());
        },

        getListByPath(obj, path) {
            if (!path || path === 'root') return obj;
            let parts = path.split('.');
            let current = obj;
            for (let part of parts) {
                current = current[part].children;
            }
            return current;
        },

        switchMenu(id) {
            window.location.href = `{{ url('/admin/menus') }}/${id}/edit?locale=${this.currentLocale}`;
        },

        loadMenu(id) {
            this.activeMenu = this.menus.find(m => m.id === id);
            if (this.activeMenu) {
                this.items = @json($items);
                this.activeMenuArea = this.activeMenu.area;
            }
        },

        addItem(type, label, url) {
            if (!this.activeMenuId) return;
            this.items.push({ type: type, label: label, url: url, children: [] });
            this.$nextTick(() => this.initSortable());
        },

        removeItem(pIdx, cIdx = null, gIdx = null, l4Idx = null) {
            if (!confirm('Xóa?')) return;
            if (l4Idx !== null) this.items[pIdx].children[cIdx].children[gIdx].children.splice(l4Idx, 1);
            else if (gIdx !== null) this.items[pIdx].children[cIdx].children.splice(gIdx, 1);
            else if (cIdx !== null) this.items[pIdx].children.splice(cIdx, 1);
            else this.items.splice(pIdx, 1);
            this.$nextTick(() => this.initSortable());
        },

        editItem(item, pIdx, cIdx = null, gIdx = null, l4Idx = null) {
            this.editingItemData = JSON.parse(JSON.stringify(item));
            this.editPath = [pIdx, cIdx, gIdx, l4Idx];
            this.showEditItem = true;
        },

        finishEditItem() {
            let [p, c, g, l4] = this.editPath;
            if (l4 !== null) this.items[p].children[c].children[g].children[l4] = this.editingItemData;
            else if (g !== null) this.items[p].children[c].children[g] = this.editingItemData;
            else if (c !== null) this.items[p].children[c] = this.editingItemData;
            else this.items[p] = this.editingItemData;
            this.showEditItem = false;
        },

        saveMenu() {
            fetch(`{{ url('/admin/menus') }}/${this.activeMenuId}`, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ items: this.items, area: this.activeMenuArea, name: this.activeMenu.name, locale: this.currentLocale })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    Alpine.store('toast').fire('Đã lưu dữ liệu', 'Cấu trúc menu đã được cập nhật thành công.', 'success');
                    
                    const btn = document.getElementById('btnSave');
                    btn.classList.add('ring-4', 'ring-emerald-500/30');
                    setTimeout(() => { btn.classList.remove('ring-4', 'ring-emerald-500/30'); }, 1000);
                }
            });
        },

        deleteMenu(id) {
            if (!confirm('Xóa hoàn toàn Menu này?')) return;
            fetch(`{{ url('/admin/menus') }}/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            }).then(r => r.json()).then(data => { if(data.success) window.location.href = `{{ route('admin.menus.index') }}?locale=${this.currentLocale}`; });
        },

        createMenu() {
            if (!this.newMenuName) return;
            fetch(`{{ route('admin.menus.store') }}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ name: this.newMenuName, locale: this.currentLocale })
            }).then(r => { if(r.redirected) window.location.href = r.url; });
        }
    }
}
</script>
<style>
    .custom-scroll::-webkit-scrollbar { width: 3px; height: 3px; }
    .custom-scroll::-webkit-scrollbar-track { background: transparent; }
    .custom-scroll::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.05); border-radius: 10px; }
    .sortable-ghost { opacity: 1 !important; visibility: visible !important; border-left: 5px solid #3b82f6 !important; background: #f8fafc !important; }
    .sortable-chosen { opacity: 1 !important; background: #fff !important; box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; transform: none !important; }
    .sortable-drag { opacity: 1 !important; }
</style>
@endpush
