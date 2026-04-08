@extends('layouts.app')

@section('title', setting('shop_meta_title', 'Cửa hàng - ' . setting('site_name')))
@section('meta_description', setting('shop_meta_description'))
@section('meta_keywords', setting('shop_meta_keywords'))

@push('styles')
    <style>
        /* Maintain filter reactivity and modern aesthetics within theme structure */
        .sidebar-filter-main .single-filter-box {
            margin-bottom: 30px;
        }
        .sidebar-filter-main .single-filter-box .title {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        .category-wrapper .single-category {
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            cursor: pointer;
        }
        .category-wrapper .single-category input {
            width: 18px;
            height: 18px;
            margin-right: 10px;
            cursor: pointer;
        }
        .category-wrapper .single-category label {
            cursor: pointer;
            font-size: 15px;
            transition: color 0.3s;
        }
        .category-wrapper .single-category:hover label {
            color: var(--color-primary);
        }
        .category-wrapper .single-category.active label {
            color: var(--color-primary);
            font-weight: 600;
        }
    </style>
@endpush

@push('scripts')
    <script>
        function shopFilter(config) {
            return {
                allProducts: config.allProducts,
                filteredProducts: [],
                activeCategory: config.initialCategory || '',
                currentMin: 0,
                currentMax: 0,
                sortBy: 'default',
                searchQuery: '',

                init() {
                    const prices = this.allProducts.length > 0 ? this.allProducts.map(p => p.price) : [0];
                    const minPrice = Math.floor(Math.min(...prices));
                    const maxPrice = Math.ceil(Math.max(...prices));
                    this.currentMin = minPrice;
                    this.currentMax = maxPrice;
                    this.applyFilter();
                },

                applyFilter() {
                    let result = [...this.allProducts];

                    if (this.activeCategory) {
                        result = result.filter(p => p.category_slugs.includes(this.activeCategory));
                    }

                    if (this.searchQuery) {
                        const q = this.searchQuery.toLowerCase();
                        result = result.filter(p => p.name.toLowerCase().includes(q));
                    }

                    result = result.filter(p => p.price >= this.currentMin && p.price <= this.currentMax);

                    if (this.sortBy === 'price_asc') {
                        result.sort((a, b) => (Number(a.price) || 0) - (Number(b.price) || 0));
                    } else if (this.sortBy === 'price_desc') {
                        result.sort((a, b) => (Number(b.price) || 0) - (Number(a.price) || 0));
                    } else if (this.sortBy === 'newest') {
                        result.sort((a, b) => new Date(b.created_at).getTime() - new Date(a.created_at).getTime());
                    } else {
                        // Default: sort_order ASC, then created_at DESC
                        result.sort((a, b) => (Number(a.sort_order) - Number(b.sort_order)) || (new Date(b.created_at).getTime() - new Date(a.created_at).getTime()));
                    }

                    this.filteredProducts = result;
                },

                setCategory(slug) {
                    this.activeCategory = slug;
                    this.applyFilter();
                    const url = slug ? `${window.VTM_CONFIG.baseUrl}/shop/category/${slug}` : `${window.VTM_CONFIG.baseUrl}/shop`;
                    window.history.pushState({}, '', url);
                }
            }
        }
    </script>
@endpush

@section('content')
    <div x-data='shopFilter({
                allProducts: @json($productsJson),
                initialCategory: "{{ $activeCategorySlug }}"
            })' class="shop-main-wrapper">

        <div class="rts-navigation-area-breadcrumb">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="navigator-breadcrumb-wrapper">
                            <a href="{{ route('home') }}">Trang chủ</a>
                            <x-theme-icon name="chevron-right" />
                            <a class="current" href="{{ route('shop.index') }}">Cửa hàng</a>
                            <template x-if="activeCategory">
                                <span class="d-flex align-items-center">
                                    <x-theme-icon name="chevron-right" class="ml--5 mr--5" />
                                    <span class="current" x-text="activeCategory.charAt(0).toUpperCase() + activeCategory.slice(1).replace(/-/g, ' ')"></span>
                                </span>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="section-seperator">
            <div class="container">
                <hr class="section-seperator">
            </div>
        </div>

        <div class="shop-grid-sidebar-area rts-section-gap">
            <div class="container">
                <div class="row g-0">
                    <!-- Sidebar -->
                    <div class="col-xl-3 col-lg-4 pr--70 pr_lg--10 pr_sm--10 pr_md--5 rts-sticky-column-item">
                        <div class="sidebar-filter-main">
                            <!-- Categories -->
                            <div class="single-filter-box">
                                <h5 class="title">Danh mục sản phẩm</h5>
                                <div class="filterbox-body">
                                    <div class="category-wrapper">
                                        <div class="single-category" :class="!activeCategory ? 'active' : ''" @click="setCategory('')">
                                            <input type="checkbox" :checked="!activeCategory" readonly>
                                            <label>Tất cả danh mục</label>
                                        </div>
                                        @foreach($categories as $cat)
                                            <div class="single-category" :class="activeCategory === '{{ $cat->slug }}' ? 'active' : ''" @click="setCategory('{{ $cat->slug }}')">
                                                <input type="checkbox" :checked="activeCategory === '{{ $cat->slug }}'" readonly>
                                                <label>{{ $cat->name }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- Price Filter -->
                            <div class="single-filter-box">
                                <h5 class="title">Lọc theo giá</h5>
                                <div class="filterbox-body">
                                    <div class="price-input-area">
                                        <div class="half-input-wrapper">
                                            <div class="single">
                                                <label>Từ (₫)</label>
                                                <input type="number" x-model.number="currentMin" @input.debounce.500ms="applyFilter()">
                                            </div>
                                            <div class="single">
                                                <label>Đến (₫)</label>
                                                <input type="number" x-model.number="currentMax" @input.debounce.500ms="applyFilter()">
                                            </div>
                                        </div>
                                        <div class="filter-value-min-max mt--20">
                                            <button class="rts-btn btn-primary w-100" @click="applyFilter()">Lọc giá</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Search -->
                            <div class="single-filter-box">
                                <h5 class="title">Tìm kiếm</h5>
                                <div class="filterbox-body">
                                    <div class="search-input-area-menu" style="position: relative;">
                                        <input type="text" x-model="searchQuery" @input.debounce.300ms="applyFilter()" placeholder="Tìm sản phẩm..." style="width: 100%; padding: 10px; border: 1px solid #eee; border-radius: 5px;">
                                        <x-theme-icon name="search" style="position: absolute; right: 15px; top: 15px; color: #999;" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main Content -->
                    <div class="col-xl-9 col-lg-8">
                        <div class="filter-select-area">
                            <div class="top-filter">
                                <span>Hiển thị <strong x-text="filteredProducts.length"></strong> sản phẩm</span>
                                <div class="right-end">
                                    <select 
                                        x-model="sortBy" 
                                        x-init="$( $el ).on('change', (e) => { sortBy = e.target.value; applyFilter(); })" 
                                        class="form-select border-0 bg-transparent font-bold">
                                        <option value="default">Mặc định (Ưu tiên)</option>
                                        <option value="newest">Mới nhất</option>
                                        <option value="price_asc">Giá: Thấp đến Cao</option>
                                        <option value="price_desc">Giá: Cao đến Thấp</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="tab-content mt--20">
                            <div class="row g-4">
                                <template x-if="filteredProducts.length === 0">
                                    <div class="col-12 text-center py--100">
                                        <x-theme-icon name="box-open" mb--20 style="font-size: 60px; color: #ccc;" />
                                        <h5>Không tìm thấy sản phẩm nào!</h5>
                                        <p>Vui lòng thử lại với bộ lọc khác.</p>
                                    </div>
                                </template>

                                <template x-for="product in filteredProducts" :key="product.id">
                                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="single-shopping-card-one">
                                            <div class="image-and-action-area-wrapper">
                                                <a :href="product.url" class="thumbnail-preview">
                                                    <div class="badge-container" style="position: absolute; left: 10px; top: 10px; z-index: 10; display: flex; flex-direction: column; gap: 40px;">
                                                        <!-- Discount Badge -->
                                                        <template x-if="product.discount_percent > 0">
                                                            <div class="badge" style="position: relative; left: 0; top: 0; margin-bottom: -15px;">
                                                                <span x-html="product.discount_percent + '% <br> Giảm'"></span>
                                                                <x-theme-icon name="bookmark" />
                                                            </div>
                                                        </template>
                                                        
                                                        <!-- Best Seller Badge -->
                                                        <template x-if="product.is_best_seller">
                                                            <div class="badge" style="position: relative; left: 0; top: 0; margin-bottom: -15px;">
                                                                <span style="top: 15px; left: 14px; font-size: 9px; line-height: 1;">BÁN <br> CHẠY</span>
                                                                <x-theme-icon name="bookmark" style="color: #ff4d4d !important;" />
                                                            </div>
                                                        </template>

                                                        <!-- Featured Badge -->
                                                        <template x-if="product.is_featured">
                                                            <div class="badge" style="position: relative; left: 0; top: 0;">
                                                                <span style="top: 15px; left: 14px; font-size: 9px; line-height: 1;">NỔI <br> BẬT</span>
                                                                <x-theme-icon name="bookmark" style="color: #4d94ff !important;" />
                                                            </div>
                                                        </template>
                                                    </div>
                                                    <img :src="product.thumbnail_url" :alt="product.name">
                                                </a>
                                                <div class="action-share-option">
                                                    <div class="single-action" @click="cwAction.addWishlist(product.id, $event.target)">
                                                        <x-theme-icon name="heart" />
                                                    </div>
                                                    <div class="single-action" @click="cwAction.addCompare(product.id, $event.target)">
                                                        <x-theme-icon name="arrows-retweet" />
                                                    </div>
                                                    <div class="single-action cta-quickview" @click="cwAction.quickView(product.id)">
                                                        <x-theme-icon name="eye" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="body-content">
                                                <a :href="product.url">
                                                    <h4 class="title" x-text="product.name"></h4>
                                                </a>
                                                <span class="availability" x-text="product.unit || 'Gói'"></span>
                                                <div class="price-area">
                                                    <span class="current" x-text="product.formatted_price"></span>
                                                    <template x-if="product.old_price > product.price">
                                                        <div style="display: flex; align-items: center; gap: 5px;">
                                                            <div class="previous" x-text="product.formatted_old_price"></div>
                                                            <span style="background: var(--color-danger); color: white; border-radius: 4px; padding: 2px 6px; font-size: 12px; font-weight: 700;" x-text="'-' + product.discount_percent + '%'"></span>
                                                        </div>
                                                    </template>
                                                </div>
                                                <div class="cart-counter-action" x-data="{ qty: 1 }">
                                                    <div class="quantity-edit">
                                                        <input type="text" class="input" x-model="qty" readonly>
                                                        <div class="button-wrapper-action">
                                                            <button class="button" @click="qty > 1 ? qty-- : 1"><x-theme-icon name="chevron-down" /></button>
                                                            <button class="button plus" @click="qty++"><x-theme-icon name="chevron-up" /></button>
                                                        </div>
                                                    </div>
                                                    <a href="javascript:void(0);" @click="cart.add(product.id, $event.target, qty)" class="rts-btn btn-primary radious-sm with-icon">
                                                        <div class="btn-text">Thêm</div>
                                                        <div class="arrow-icon"><x-theme-icon name="cart" /></div>
                                                        <div class="arrow-icon"><x-theme-icon name="cart" /></div>
                                                    </a>
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