@extends('layouts.app')

@section('title', 'Cửa hàng - ' . setting('site_name'))

@push('styles')
    <style>
        @keyframes revealBottom {
            from {
                transform: translateY(30px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Spacing & Layout Utilities */
        .space-y-10>*+* {
            margin-top: 2.5rem;
        }

        .mb-12 {
            margin-bottom: 3rem;
        }

        .mb-6 {
            margin-bottom: 1.5rem;
        }

        .mt-6 {
            margin-top: 1.5rem;
        }

        .pt-6 {
            padding-top: 1.5rem;
        }

        .px-2 {
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }

        .gap-2 {
            gap: 0.5rem;
        }

        .flex {
            display: flex;
        }

        .flex-wrap {
            flex-wrap: wrap;
        }

        /* Professional Filter Styles */
        .filter-section-title {
            font-size: 14px;
            font-weight: 800;
            color: #1a202c;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 20px;
            display: block;
            border-left: 4px solid #3b82f6;
            padding-left: 12px;
        }

        .category-tags-container {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .category-tag {
            display: inline-block;
            padding: 5px 14px;
            border-radius: 20px;
            border: 1.5px solid #3b82f6;
            background: #fff;
            font-size: 12px;
            font-weight: 600;
            color: #1d4ed8;
            white-space: nowrap;
            cursor: pointer;
            transition: all 0.2s ease;
            user-select: none;
        }

        .category-tag:hover {
            background: #eff6ff;
            transform: translateY(-1px);
        }

        .category-tag.active {
            background: #3b82f6;
            color: #fff;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .search-input-wrapper {
            position: relative;
        }

        .search-input-wrapper i {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }

        .search-input-wrapper input {
            width: 100%;
            height: 48px;
            border-radius: 12px;
            border: 1.5px solid #e2e8f0;
            padding: 0 45px 0 20px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .search-input-wrapper input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
            outline: none;
        }

        .price-filter-card {
            background: #f8fafc;
            padding: 20px;
            border-radius: 16px;
            border: 1px solid #f1f5f9;
        }

        .price-input-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .price-input-group input {
            width: 100%;
            height: 40px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            text-align: center;
            font-size: 13px;
            font-weight: 600;
        }
    </style>
@endpush

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
                    <div class="sidebar-wrapper space-y-10">

                        <!-- Search Section -->
                        <div class="filter-group mb-12">
                            <span class="filter-section-title">Tìm kiếm</span>
                            <div class="search-input-wrapper">
                                <input type="text" x-model="searchQuery" @input.debounce.300ms="applyFilter()"
                                    placeholder="Bạn tìm gì hôm nay?">
                                <i class="fa-regular fa-magnifying-glass"></i>
                            </div>
                        </div>

                        <!-- Categories Section -->
                        <div class="filter-group mb-12">
                            <span class="filter-section-title">Danh mục</span>
                            <div class="category-tags-container">
                                <div class="category-tag" :class="!activeCategory ? 'active' : ''" @click="setCategory('')">
                                    Tất cả</div>
                                @foreach($categories as $cat)
                                    <div class="category-tag" :class="activeCategory === '{{ $cat->slug }}' ? 'active' : ''"
                                        @click="setCategory('{{ $cat->slug }}')">
                                        {{ $cat->name }}
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Price Filter Section -->
                        <div class="filter-group mb-12">
                            <span class="filter-section-title">Khoảng giá (₫)</span>
                            <div class="price-filter-card">
                                <div class="price-input-group mb-6">
                                    <input type="number" x-model.number="currentMin" :min="minPriceLimit" :max="currentMax"
                                        @change="applyFilter()">
                                    <span class="text-slate-300">─</span>
                                    <input type="number" x-model.number="currentMax" :min="currentMin" :max="maxPriceLimit"
                                        @change="applyFilter()">
                                </div>

                                <template x-if="priceFilterType === 'slider' || priceFilterType === 'both'">
                                    <div class="px-2">
                                        <input type="range" class="w-100 accent-blue-500" :min="minPriceLimit"
                                            :max="maxPriceLimit" step="10000" x-model.number="currentMax"
                                            @input="applyFilter()">
                                    </div>
                                </template>

                                <template x-if="pricePresets.length > 0">
                                    <div class="mt-6 pt-6 border-top border-slate-100 flex flex-wrap gap-2 text-xs">
                                        <template x-for="preset in pricePresets">
                                            <button
                                                @click="currentMin = preset.min; currentMax = (preset.max == 0 ? maxPriceLimit : preset.max); applyFilter()"
                                                class="px-3 py-1.5 rounded-lg bg-white border border-slate-200 hover:border-blue-300 transition-colors"
                                                x-text="preset.label"></button>
                                        </template>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Clear All -->
                        <button
                            @click="searchQuery=''; activeCategory=''; currentMin=minPriceLimit; currentMax=maxPriceLimit; applyFilter()"
                            class="rts-btn w-100 py-3 rounded-xl border border-slate-200 text-slate-500 font-bold hover:bg-slate-50 transition-all text-xs">
                            <i class="fa-regular fa-rotate-right mr-2"></i> Làm mới bộ lọc
                        </button>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="col-xl-9 col-lg-12">
                    <div class="filter-select-area">
                        <div class="top-filter">
                            <span>Hiển thị <strong x-text="filteredProducts.length"></strong> sản phẩm</span>
                            <div class="right-end d-flex align-items-center">
                                <span class="mr--10 text-sm font-bold text-slate-500">Sắp xếp:</span>
                                <select x-model="sortBy" @change="applyFilter()"
                                    class="form-select !py-1 !px-3 !text-sm border-slate-200" style="min-width: 180px;">
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
                                        <div class="col-12 text-center py--100">
                                            <div class="mb--30">
                                                <i class="fa-light fa-box-open" style="font-size: 80px; color: #ccc;"></i>
                                            </div>
                                            <h5 class="mb--10">Không có sản phẩm nào!</h5>
                                            <p class="mb--30">Chúng tôi không tìm thấy kết quả phù hợp với bộ lọc hiện tại
                                                của bạn.</p>
                                            <button
                                                @click="searchQuery=''; activeCategory=''; currentMin=minPriceLimit; currentMax=maxPriceLimit; applyFilter()"
                                                class="rts-btn btn-primary">Xóa bộ lọc</button>
                                        </div>
                                </template>

                                <!-- Product Cards -->
                                <template x-for="product in filteredProducts" :key="product.id">
                                    <div class="col-lg-4 col-md-6 col-sm-6 col-12 reveal-bottom">
                                        <div class="vtm-product-card">
                                            {{-- Image Area --}}
                                            <div class="image-and-action-area-wrapper">
                                                <a :href="product.url" class="thumbnail-preview">
                                                    <template x-if="product.on_sale">
                                                        <div class="vtm-badge-ribbon">
                                                            <span x-text="product.discount_percent + '%'"></span><br>Giảm
                                                            giá
                                                        </div>
                                                    </template>
                                                    <img :src="product.thumbnail_url" :alt="product.name">
                                                </a>
                                                <div class="action-share-option">
                                                    <div class="single-action" @click="cart.add(product.id, $event.target)">
                                                        <i class="fas fa-shopping-cart"></i>
                                                    </div>
                                                    <div class="single-action">
                                                        <i class="fa-light fa-heart"></i>
                                                    </div>
                                                    <div class="single-action cta-quickview"
                                                        @click="cwAction.quickView(product.id)">
                                                        <i class="fa-regular fa-eye"></i>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Content Area --}}
                                            <div class="content-area">
                                                <div class="category-text"
                                                    x-text="product.category_names[0] || 'VietTin Mart'"></div>
                                                <a :href="product.url">
                                                    <h3 class="product-title-h3" x-text="product.name"></h3>
                                                </a>

                                                <div class="stars-area">
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                </div>

                                                <div class="price-and-btn-wrapper">
                                                    <div class="price-area">
                                                        <span class="current-price" x-text="product.formatted_price"></span>
                                                        <template x-if="product.old_price > product.price">
                                                            <span class="old-price"
                                                                x-text="product.formatted_old_price"></span>
                                                        </template>
                                                    </div>

                                                    <div class="cart-counter-action">
                                                        <template x-if="!product.has_contact_price">
                                                            <button @click="cart.add(product.id, $event.target)"
                                                                class="rts-btn btn-primary">
                                                                <i class="fas fa-shopping-cart"></i> MUA NGAY
                                                            </button>
                                                        </template>
                                                        <template x-if="product.has_contact_price">
                                                            <a :href="product.url" class="rts-btn btn-primary">
                                                                <i class="fa-solid fa-phone"></i> LIÊN HỆ
                                                            </a>
                                                        </template>
                                                    </div>
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