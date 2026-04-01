@extends('layouts.app')

@section('title', 'Cửa hàng - ' . setting('site_name'))

@push('scripts')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        function shopFilter(config) {
            return {
                allProducts: config.allProducts,
                filteredProducts: [],
                activeCategory: config.initialCategory || '',
                priceFilterType: config.priceFilterType,
                pricePresets: config.pricePresets || [],
                minPriceLimit: 0,
                maxPriceLimit: 0,
                currentMin: 0,
                currentMax: 0,
                sortBy: 'newest',
                searchQuery: '',
                
                init() {
                    if (this.allProducts.length > 0) {
                        this.minPriceLimit = Math.floor(Math.min(...this.allProducts.map(p => p.price)));
                        this.maxPriceLimit = Math.ceil(Math.max(...this.allProducts.map(p => p.price)));
                        this.currentMin = this.minPriceLimit;
                        this.currentMax = this.maxPriceLimit;
                    }
                    this.applyFilter();
                },
                
                applyFilter() {
                    let result = [...this.allProducts];
                    
                    // Category filter
                    if (this.activeCategory) {
                        result = result.filter(p => p.category_slugs.includes(this.activeCategory));
                    }
                    
                    // Search filter
                    if (this.searchQuery) {
                        const q = this.searchQuery.toLowerCase();
                        result = result.filter(p => p.name.toLowerCase().includes(q));
                    }
                    
                    // Price filter
                    result = result.filter(p => {
                        const isMinOk = p.price >= this.currentMin;
                        const isMaxOk = (this.currentMax === 0 || this.currentMax >= this.maxPriceLimit) ? true : p.price <= this.currentMax;
                        return isMinOk && isMaxOk;
                    });
                    
                    // Sorting
                    if (this.sortBy === 'price_asc') {
                        result.sort((a, b) => a.price - b.price);
                    } else if (this.sortBy === 'price_desc') {
                        result.sort((a, b) => b.price - a.price);
                    } else if (this.sortBy === 'oldest') {
                        result.sort((a, b) => new Date(a.created_at) - new Date(b.created_at));
                    } else {
                        result.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
                    }
                    
                    this.filteredProducts = result;
                },
                
                setCategory(slug) {
                    this.activeCategory = slug;
                    this.applyFilter();
                    const url = slug ? `/shop/category/${slug}` : '/shop';
                    window.history.pushState({}, '', url);
                },

                formatPrice(price) {
                    return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(price).replace('₫', '').trim() + ' ₫';
                }
            }
        }
    </script>
@endpush

@section('content')
<div x-data='shopFilter({
    allProducts: @json($productsJson),
    initialCategory: "{{ $activeCategorySlug }}",
    pricePresets: @json(json_decode(setting("price_presets", "[]"), true)),
    priceFilterType: "{{ setting("price_filter_type", "slider") }}"
})' class="shop-grid-sidebar-area rts-section-gap">
    
    <div class="rts-navigation-area-breadcrumb">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="navigator-breadcrumb-wrapper">
                        <a href="{{ route('home') }}">Trang chủ</a>
                        <i class="fa-regular fa-chevron-right"></i>
                        <a class="current" href="{{ route('shop.index') }}">Cửa hàng</a>
                        <template x-if="activeCategory">
                            <span>
                                <i class="fa-regular fa-chevron-right"></i>
                                <span class="current" x-text="activeCategory"></span>
                            </span>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt--20">
        <div class="row g-0">
            <!-- Sidebar -->
            <div class="col-xl-3 col-lg-12 pr--70 pr_lg--10 pr_sm--10 pr_md--5 rts-sticky-column-item">
                <div class="sidebar-filter-main">
                    
                    <!-- Search Box -->
                    <div class="single-filter-box mb--30">
                        <h5 class="title">Tìm kiếm</h5>
                        <div class="filterbox-body">
                            <div class="search-input-area">
                                <input type="text" x-model="searchQuery" @input.debounce.300ms="applyFilter()" placeholder="Nhập tên sản phẩm...">
                            </div>
                        </div>
                    </div>

                    <!-- Price Filter -->
                    <div class="single-filter-box mb--30">
                        <h5 class="title">Lọc theo giá</h5>
                        <div class="filterbox-body">
                            
                            <div class="price-input-area">
                                <div class="row g-2 mb--15">
                                    <div class="col-6">
                                        <label class="text-[10px] uppercase font-bold text-slate-400 block mb-1">Từ (₫)</label>
                                        <input type="number" x-model.number="currentMin" class="form-control !py-2 !text-xs border-slate-200" :min="minPriceLimit" :max="currentMax">
                                    </div>
                                    <div class="col-6">
                                        <label class="text-[10px] uppercase font-bold text-slate-400 block mb-1">Đến (₫)</label>
                                        <input type="number" x-model.number="currentMax" class="form-control !py-2 !text-xs border-slate-200" :min="currentMin" :max="maxPriceLimit">
                                    </div>
                                </div>

                                <!-- Slider Mode -->
                                <template x-if="priceFilterType === 'slider' || priceFilterType === 'both'">
                                    <div class="mb--20">
                                        <input type="range" class="w-100 accent-primary" 
                                               :min="minPriceLimit" :max="maxPriceLimit" step="10000"
                                               x-model.number="currentMax">
                                        <div class="mt--5 d-flex justify-content-between text-muted text-xs">
                                            <span x-text="formatPrice(minPriceLimit)"></span>
                                            <span x-text="formatPrice(maxPriceLimit)"></span>
                                        </div>
                                    </div>
                                </template>

                                <button @click="applyFilter()" class="rts-btn btn-primary radious-sm w-100 py-2 !text-sm">
                                    Lọc ngay <i class="fa-regular fa-filter ml--5"></i>
                                </button>
                            </div>

                            <!-- Presets Mode -->
                            <template x-if="priceFilterType === 'presets' || priceFilterType === 'both'">
                                <div class="category-wrapper mt--20 pt--20 border-top">
                                    <p class="text-[10px] uppercase font-bold text-slate-400 mb-3 tracking-wider">Khoảng giá phổ biến</p>
                                    <div class="single-category mb--10">
                                        <label class="d-flex align-items-center cursor-pointer mb-0">
                                            <input type="radio" name="price_range" @change="currentMin = 0; currentMax = maxPriceLimit; applyFilter()" checked class="mr--10">
                                            <span class="text-sm">Tất cả giá</span>
                                        </label>
                                    </div>
                                    <template x-for="preset in pricePresets">
                                        <div class="single-category mb--10">
                                            <label class="d-flex align-items-center cursor-pointer mb-0">
                                                <input type="radio" name="price_range" 
                                                       @change="currentMin = preset.min; currentMax = (preset.max == 0 ? maxPriceLimit : preset.max); applyFilter()"
                                                       class="mr--10">
                                                <span class="text-sm" x-text="preset.label"></span>
                                            </label>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Categories -->
                    <div class="single-filter-box">
                        <h5 class="title">Danh mục sản phẩm</h5>
                        <div class="filterbox-body">
                            <div class="category-wrapper">
                                <div class="single-category">
                                    <a href="javascript:void(0)" @click="setCategory('')" :class="!activeCategory ? 'active text-primary font-bold' : ''">
                                        Tất cả danh mục
                                    </a>
                                </div>
                                @foreach($categories as $cat)
                                <div class="single-category">
                                    <a href="javascript:void(0)" @click="setCategory('{{ $cat->slug }}')" 
                                       :class="activeCategory === '{{ $cat->slug }}' ? 'active text-primary font-bold' : ''">
                                        {{ $cat->name }} ({{ $cat->products_count }})
                                    </a>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-xl-9 col-lg-12">
                <div class="filter-select-area">
                    <div class="top-filter">
                        <span>Hiển thị <strong x-text="filteredProducts.length"></strong> sản phẩm</span>
                        <div class="right-end d-flex align-items-center">
                            <span class="mr--10 text-sm font-bold text-slate-500">Sắp xếp:</span>
                            <select x-model="sortBy" @change="applyFilter()" class="form-select !py-1 !px-3 !text-sm border-slate-200" style="min-width: 180px;">
                                <option value="newest">Mới nhất</option>
                                <option value="price_asc">Giá: Thấp đến Cao</option>
                                <option value="price_desc">Giá: Cao đến Thấp</option>
                                <option value="oldest">Cũ nhất</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="tab-content mt--20">
                    <div class="tab-pane fade show active">
                        <div class="row g-4">
                            <!-- Empty State -->
                            <template x-if="filteredProducts.length === 0">
                                <div class="col-12 text-center py--100 bg_light-1 rounded">
                                    <div class="mb--30">
                                        <i class="fa-light fa-box-open" style="font-size: 80px; color: #ccc;"></i>
                                    </div>
                                    <h5 class="mb--10">Không có sản phẩm nào!</h5>
                                    <p class="mb--30">Chúng tôi không tìm thấy kết quả phù hợp với bộ lọc hiện tại của bạn.</p>
                                    <button @click="searchQuery=''; activeCategory=''; currentMin=minPriceLimit; currentMax=maxPriceLimit; applyFilter()" class="rts-btn btn-primary radious-sm px--30">Xóa bộ lọc</button>
                                </div>
                            </template>

                            <!-- Product Cards -->
                            <template x-for="product in filteredProducts" :key="product.id">
                                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                                    <div class="single-shopping-card-one h-100">
                                        <div class="image-and-action-area-wrapper">
                                            <a :href="product.url" class="thumbnail-preview">
                                                <div x-show="product.on_sale" class="badge">
                                                    <span x-text="product.discount_percent + '%' + '\n Bán chạy'"></span><i class="fa-solid fa-bookmark"></i>
                                                </div>
                                                <img :src="product.thumbnail_url" :alt="product.name">
                                            </a>
                                            <div class="action-share-option">
                                                <div class="single-action"><i class="fa-light fa-heart"></i></div>
                                                <div class="single-action"><i class="fa-solid fa-arrows-retweet"></i></div>
                                                <div class="single-action"><i class="fa-regular fa-eye"></i></div>
                                            </div>
                                        </div>
                                        <div class="body-content">
                                            <a :href="product.url">
                                                <h4 class="title" x-text="product.name"></h4>
                                            </a>
                                            <span class="availability" x-text="product.unit"></span>
                                            <div class="price-area">
                                                <span class="current" x-text="product.formatted_price"></span>
                                                <template x-if="product.old_price > product.price">
                                                    <div class="previous" x-text="product.formatted_old_price"></div>
                                                </template>
                                            </div>
                                            <div class="cart-counter-action">
                                                <template x-if="!product.has_contact_price">
                                                    <button @click="cart.add(product.id, $event.target)"
                                                       class="rts-btn btn-primary radious-sm with-icon w-100 justify-content-center">
                                                        <div class="btn-text">Thêm vào giỏ</div>
                                                        <div class="arrow-icon"><i class="fa-regular fa-cart-shopping"></i></div>
                                                    </button>
                                                </template>
                                                <template x-if="product.has_contact_price">
                                                    <a :href="product.url" class="rts-btn btn-primary radious-sm w-100 justify-content-center">
                                                        Liên hệ
                                                    </a>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
