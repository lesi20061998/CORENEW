@php
    $product     = $product ?? null;
    $isEdit      = isset($product) && $product;
    $languages   = $languages ?? collect();
    $hasI18n     = $languages->count() > 1;
    $defaultLang = $languages->firstWhere('is_default', true) ?? $languages->first();
    $otherLangs  = $languages->where('is_default', false);

    // Lấy bản dịch hiện có nếu đang edit
    $existingTranslations = [];
    if ($isEdit && $hasI18n) {
        foreach ($otherLangs as $lang) {
            foreach (['name','short_description','description','additional_info','meta_title','meta_description'] as $f) {
                $existingTranslations[$lang->code][$f] = $product->translate($f, $lang->code);
            }
        }
    }
    
    // Chuẩn bị dữ liệu combo cho JS
    $comboInitData = [];
    if ($isEdit) {
        $comboInitData = $product->combos->map(function($c) {
            $variantId = $c->pivot->combo_product_variant_id;
            $name = $c->name;
            $price = (float)$c->price;
            $image = $c->image;
            if ($variantId) {
                // Eager loaded variants
                $variant = $c->variants->where('id', $variantId)->first();
                if ($variant) {
                    $name .= ' (' . ($variant->label ?: ($variant->sku ?: 'Biến thể #'.$variant->id)) . ')';
                    $price = (float)($variant->price ?: $c->price);
                    if ($variant->image) $image = $variant->image;
                }
            }
            return [
                'id' => $c->id,
                'variant_id' => $variantId,
                'name' => $name,
                'image' => $image ? (str_starts_with($image, 'http') ? $image : asset($image)) : null,
                'original_price' => $price,
                'discount_type' => $c->pivot->discount_type ?? 'fixed',
                'discount_value' => $c->pivot->discount_value ?? 0,
                'combo_price' => (float)$c->pivot->combo_price,
                'is_active' => (bool)$c->pivot->is_active,
                'sort_order' => $c->pivot->sort_order
            ];
        })->toArray();
    }
    
    // Global batch settings from first item
    $firstC = collect($comboInitData)->first();
    $initBatchVal = $firstC ? $firstC['discount_value'] : 0;
    if ($firstC && $firstC['discount_type'] === 'fixed') {
        $initBatchVal = number_format($initBatchVal, 0, ',', ',');
    }
    $initBatchType = $firstC ? $firstC['discount_type'] : 'percent';
@endphp

<script>
window.comboBatchInit = {
    value: '{{ $initBatchVal }}',
    type: '{{ $initBatchType }}'
};
</script>

<script>
// Maps and context from PHP
window.attributeValuesMap = @json(isset($attributes) ? $attributes->mapWithKeys(fn($a) => [$a->id => $a->values->map(fn($v) => ['id' => $v->id, 'value' => $v->value])]) : []);
window.attributeNamesMap  = @json(isset($attributes) ? $attributes->pluck('name', 'id') : []);

window.galleryManager = function(initialImages) {
    return {
        gallery: Array.isArray(initialImages) ? initialImages : [],
        addImage() {
            openMediaPicker(null, (urls) => {
                if (Array.isArray(urls)) {
                    urls.forEach(url => {
                        if (url && !this.gallery.includes(url)) {
                            this.gallery.push(url);
                        }
                    });
                } else if (urls && !this.gallery.includes(urls)) {
                    this.gallery.push(urls);
                }
            }, true);
        },
        removeImage(idx) {
            this.gallery.splice(idx, 1);
        }
    };
};

window.productFormManager = function(existingVariants, existingAttrMap) {
    return {
        productType: {{ (int)old('has_variants', $product?->has_variants ?? 0) }},
        activeDataTab: 'general',
        
        mainPriceStr: '{{ number_format((float)old('price', $product?->price ?? 0), 0, ',', ',') }}',
        comparePriceStr: '{{ number_format((float)old('compare_price', $product?->compare_price ?? 0), 0, ',', ',') }}',
        costPriceStr: '{{ number_format((float)old('cost_price', $product?->cost_price ?? 0), 0, ',', ',') }}',

        variants: existingVariants || [],
        selectedAttributes: Object.keys(existingAttrMap).map(Number),
        selectedValues: existingAttrMap || {},
        expandedVariants: [],
        manualVariantAttrs: {},
        parentSku: '{{ old('sku', $product?->sku) }}',
        bulkEdit: false,
        bulkPrice: '',
        bulkStock: '',
        batchDiscountValue: window.comboBatchInit.value || '0',
        batchDiscountType: window.comboBatchInit.type || 'percent',
        batchSelectedProducts: [],
        combos: @json($comboInitData ?? []),

        addCombo(pIdAndVariantId, discountVal = null, discountType = null) {
            const [pId, vId] = String(pIdAndVariantId).split(':').map(Number);
            const allP = @json($allProducts ?? [], JSON_UNESCAPED_UNICODE);
            const p = allP.find(item => item.id == pId);
            if (!p) return;

            // Check if already in combos (matching both product_id and variant_id)
            if (this.combos.some(c => c.id == pId && (c.variant_id == (vId || null)))) return;
            
            let name = p.name;
            let price = p.price;
            let image = p.image;

            if (vId) {
                const variant = (p.variants || []).find(v => v.id == vId);
                if (variant) {
                    const vLabel = (variant.label || (variant.sku || 'Variant #'+vId));
                    name += ' (' + vLabel + ')';
                    if (variant.price) price = variant.price;
                    if (variant.image) image = variant.image;
                }
            }

            this.combos.push({
                id: p.id,
                variant_id: vId || null,
                name: name,
                image: image ? (image.startsWith('http') ? image : '/'+image) : null,
                original_price: price,
                discount_type: discountType || 'percent',
                discount_value: discountVal || '0',
                combo_price: price,
                is_active: true,
                sort_order: this.combos.length
            });
        },

        addImmediateCombo() {
            if (typeof window.jQuery === 'undefined') {
                const data = document.getElementById('combo-multi-select').value;
                if (!data) { alert('Vui lòng chọn một sản phẩm trước.'); return; }
                this.addCombo(data, this.batchDiscountValue, this.batchDiscountType);
                document.getElementById('combo-multi-select').value = '';
                return;
            }
            const data = window.jQuery('#combo-multi-select').val();
            if (!data) { alert('Vui lòng chọn một sản phẩm trước.'); return; }
            this.addCombo(data, this.batchDiscountValue, this.batchDiscountType);
            window.jQuery('#combo-multi-select').val(null).trigger('change');
        },

        addBatchCombos() {
            if (this.batchSelectedProducts.length === 0) {
                alert('Vui lòng chọn ít nhất một sản phẩm.');
                return;
            }
            this.batchSelectedProducts.forEach(pId => {
                this.addCombo(pId, this.batchDiscountValue, this.batchDiscountType);
            });
            // Reset
            this.batchSelectedProducts = [];
            if (window.jQuery && $('#combo-multi-select').length) {
                $('#combo-multi-select').val(null).trigger('change');
            }
        },

        getProductNameFromList(pIdAndVariantId) {
            const [pId, vId] = String(pIdAndVariantId).split(':').map(Number);
            const allP = @json($allProducts ?? [], JSON_UNESCAPED_UNICODE);
            const p = allP.find(item => item.id == pId);
            if (!p) return 'Unknown';
            let name = p.name;
            if (vId) {
                const v = (p.variants || []).find(v => v.id == vId);
                if (v) {
                   const vLabel = (v.label || (v.sku || 'Variant #'+vId));
                   name += ' (' + vLabel + ')';
                }
            }
            return name;
        },

        formatMoney(val) {
            if (!val && val !== 0) return '';
            let s = String(val).replace(/\D/g, "");
            return s.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        },

        parseMoney(val) {
            return parseFloat(String(val).replace(/,/g, "")) || 0;
        },

        calculateFinalPrice(c) {
            let val = parseFloat(String(c.discount_value).replace(/,/g, "")) || 0;
            let mainPrice = this.parseMoney(this.mainPriceStr);
            let originalPriceB = parseFloat(c.original_price) || 0;
            let totalOriginal = mainPrice + originalPriceB;
            
            if (c.discount_type === 'percent') {
                return Math.max(0, totalOriginal * (1 - val / 100));
            } else {
                return Math.max(0, totalOriginal - val);
            }
        },
        removeCombo(idx) {
            this.combos.splice(idx, 1);
        },

        addManualVariant() {
            if (this.selectedAttributes.length === 0) {
                alert('Vui lòng chọn thuộc tính trước.'); return;
            }
            // Check if all active attributes have a selected value
            const combo = {};
            for (const aId of this.selectedAttributes) {
                const vId = this.manualVariantAttrs[aId];
                if (!vId) {
                    alert(`Vui lòng chọn giá trị cho ${window.attributeNamesMap[aId]}`); return;
                }
                combo[String(aId)] = String(vId);
            }

            // Check if already exists
            const exists = this.variants.some(v => {
                const vAttrs = v.attributes || {};
                return Object.keys(combo).every(k => String(vAttrs[k]) === String(combo[k])) &&
                       Object.keys(vAttrs).length === Object.keys(combo).length;
            });
            if (exists) { alert('Biến thể này đã tồn tại trong danh sách.'); return; }

            // Build label
            const label = Object.entries(combo).map(([aId, vId]) => {
                const vObj = (window.attributeValuesMap[aId] || []).find(v => String(v.id) === String(vId));
                return vObj ? vObj.value : vId;
            }).join(' - ');

            const newSku = this.parentSku ? `${this.parentSku}-${this.variants.length + 1}` : '';
            this.variants.push({
                id: '', label, attributes: combo, sku: newSku,
                price: '', compare_price: '', stock: 0, image: '',
                is_active: true
            });
            // Reset manual selection
            this.manualVariantAttrs = {};
        },

        toggleVariant(idx) {
            if (this.expandedVariants.includes(idx)) {
                this.expandedVariants = this.expandedVariants.filter(i => i !== idx);
            } else {
                this.expandedVariants.push(idx);
            }
        },

        generateVariants() {
            if (this.productType == 0) return;

            const activeAttrs = this.selectedAttributes.map(String).filter(aId =>
                this.selectedValues[aId] && this.selectedValues[aId].length > 0
            );
            
            if (activeAttrs.length === 0) { this.variants = []; return; }

            const combos = activeAttrs.reduce((acc, aId) => {
                const vals = (this.selectedValues[aId] || []).map(String);
                if (acc.length === 0) return vals.map(v => ({ [aId]: v }));
                return acc.flatMap(combo => vals.map(v => ({ ...combo, [aId]: v })));
            }, []);

            this.variants = combos.map((combo, index) => {
                const label = Object.entries(combo)
                    .map(([aId, vId]) => {
                        const vObj = (window.attributeValuesMap[aId] || []).find(v => String(v.id) === String(vId));
                        return vObj ? vObj.value : vId;
                    }).join(' - ');

                const existing = this.variants.find(v => {
                    const vAttrs = v.attributes || {};
                    return Object.keys(combo).every(k => String(vAttrs[k]) === String(combo[k])) &&
                           Object.keys(vAttrs).length === Object.keys(combo).length;
                });

                if (existing) {
                    return { ...existing, label, attributes: combo };
                }

                const newSku = this.parentSku ? `${this.parentSku}-${index + 1}` : '';
                return { 
                    id: '', label, attributes: combo, sku: newSku, 
                    price: '', compare_price: '', stock: 0, image: '', 
                    is_active: true 
                };
            });
        },

        removeVariant(idx) {
            if (confirm('Xác nhận xóa biến thể này?')) {
                this.variants.splice(idx, 1);
            }
        },

        applyBulk() {
            this.variants = this.variants.map(v => ({
                ...v,
                price: this.bulkPrice !== '' ? this.bulkPrice : v.price,
                stock: this.bulkStock !== '' ? this.bulkStock : v.stock,
            }));
            this.bulkEdit = false;
        }
    };
};

window.productInitData = {
    variants: @json($isEdit ? $product->variants : []),
    attrMap: @json($isEdit ? $product->productAttributes->groupBy('attribute_id')->map(fn($g) => $g->pluck('attribute_value_id')) : new \stdClass()),
    gallery: @json($product?->images ?? [])
};
</script>

<div class="grid grid-cols-1 xl:grid-cols-[1fr_360px] gap-6 items-start" 
     x-data="productFormManager(window.productInitData.variants, window.productInitData.attrMap)">

    {{-- ═══ CỘT TRÁI: Nội dung chính ═══ --}}
    <div class="space-y-6">

        {{-- Mô tả sản phẩm --}}
        <div class="card shadow-sm border-slate-200">
            <div class="card-header bg-slate-50/50">
                <p class="text-sm font-bold text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-align-left text-blue-500"></i> Thông tin mô tả
                </p>
            </div>
            <div class="card-body space-y-4">
                @if($hasI18n)
                <div x-data="{ activeTab: '{{ $defaultLang?->code ?? 'vi' }}' }">
                    <div class="flex items-center gap-1 border-b border-slate-100 mb-4 flex-wrap">
                        @foreach($languages as $lang)
                        <button type="button"
                                @click="activeTab = '{{ $lang->code }}'"
                                :class="activeTab === '{{ $lang->code }}' ? 'border-b-2 border-blue-600 text-blue-600 bg-blue-50' : 'text-slate-500 hover:text-slate-700'"
                                class="flex items-center gap-1.5 px-4 py-2 text-xs font-bold rounded-t-xl transition-all -mb-px">
                            <i class="fa-solid fa-language opacity-60"></i> {{ $lang->name }}
                        </button>
                        @endforeach
                    </div>
                    <div x-show="activeTab === '{{ $defaultLang?->code }}'" x-cloak>
                        @include('admin.products._fields_basic', ['locale' => null, 'prefix' => ''])
                    </div>
                    @foreach($otherLangs as $lang)
                    <div x-show="activeTab === '{{ $lang->code }}'" x-cloak>
                        @include('admin.products._fields_basic', [
                            'locale' => $lang->code,
                            'prefix' => 'translations[' . $lang->code . ']',
                            'transData' => $existingTranslations[$lang->code] ?? [],
                        ])
                    </div>
                    @endforeach
                </div>
                @else
                @include('admin.products._fields_basic', ['locale' => null, 'prefix' => ''])
                @endif
            </div>
        </div>

        {{-- ══════════════════════════════════════════
             DỮ LIỆU SẢN PHẨM (Mô phỏng WooCommerce)
             ══════════════════════════════════════════ --}}
        <div class="card shadow-sm border-slate-200 overflow-hidden">
            {{-- Header: Chọn loại sản phẩm --}}
            <div class="px-5 py-4 bg-slate-50 border-b border-slate-200 flex flex-wrap items-center gap-4 justify-between">
                <div class="flex items-center gap-3">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">Dữ liệu sản phẩm <span class="text-slate-300">|</span></span>
                    <select name="has_variants" x-model="productType" class="form-select !py-1.5 !text-sm font-bold min-w-[220px] shadow-sm">
                        <option value="0">Sản phẩm đơn giản</option>
                        <option value="1">Sản phẩm có biến thể</option>
                    </select>
                </div>
                
                {{-- Removed Virtual/Downloadable checkboxes --}}
            </div>

            <div class="flex min-h-[400px]">
                {{-- Tabs dọc bên trái --}}
                <div class="w-56 bg-white border-r border-slate-200 flex-shrink-0">
                    <button type="button" @click="activeDataTab = 'general'" 
                            :class="activeDataTab === 'general' ? 'bg-blue-50 text-blue-600 border-r-2 border-blue-600' : 'text-slate-500 hover:bg-slate-50'"
                            class="w-full text-left px-5 py-3.5 flex items-center gap-3 text-sm font-bold transition-all border-b border-slate-50">
                        <i class="fa-solid fa-wrench opacity-60"></i> Cài đặt chung
                    </button>
                    <button type="button" @click="activeDataTab = 'inventory'" 
                            :class="activeDataTab === 'inventory' ? 'bg-blue-50 text-blue-600 border-r-2 border-blue-600' : 'text-slate-500 hover:bg-slate-50'"
                            class="w-full text-left px-5 py-3.5 flex items-center gap-3 text-sm font-bold transition-all border-b border-slate-50">
                        <i class="fa-solid fa-boxes-stacked opacity-60"></i> Kiểm kê kho hàng
                    </button>
                    <button type="button" @click="activeDataTab = 'shipping'" 
                            :class="activeDataTab === 'shipping' ? 'bg-blue-50 text-blue-600 border-r-2 border-blue-600' : 'text-slate-500 hover:bg-slate-50'"
                            class="w-full text-left px-5 py-3.5 flex items-center gap-3 text-sm font-bold transition-all border-b border-slate-50">
                        <i class="fa-solid fa-truck-fast opacity-60"></i> Vận chuyển
                    </button>
                    <button type="button" @click="activeDataTab = 'attributes'" 
                            :class="activeDataTab === 'attributes' ? 'bg-blue-50 text-blue-600 border-r-2 border-blue-600' : 'text-slate-500 hover:bg-slate-50'"
                            class="w-full text-left px-5 py-3.5 flex items-center gap-3 text-sm font-bold transition-all border-b border-slate-50">
                        <i class="fa-solid fa-microchip opacity-60"></i> Thuộc tính
                    </button>
                    <button type="button" @click="activeDataTab = 'variants'" x-show="productType == 1" x-cloak
                            :class="activeDataTab === 'variants' ? 'bg-blue-50 text-blue-600 border-r-2 border-blue-600' : 'text-slate-500 hover:bg-slate-50'"
                            class="w-full text-left px-5 py-3.5 flex items-center gap-3 text-sm font-bold transition-all border-b border-slate-50">
                        <i class="fa-solid fa-layer-group opacity-60"></i> Các biến thể
                    </button>
                    <button type="button" @click="activeDataTab = 'combos'" 
                            :class="activeDataTab === 'combos' ? 'bg-blue-50 text-blue-600 border-r-2 border-blue-600' : 'text-slate-500 hover:bg-slate-50'"
                            class="w-full text-left px-5 py-3.5 flex items-center gap-3 text-sm font-bold transition-all border-b border-slate-50">
                        <i class="fa-solid fa-layer-group text-emerald-500 opacity-60"></i> Combo & Bán kèm
                    </button>
                    <button type="button" @click="activeDataTab = 'advanced'" 
                            :class="activeDataTab === 'advanced' ? 'bg-blue-50 text-blue-600 border-r-2 border-blue-600' : 'text-slate-500 hover:bg-slate-50'"
                            class="w-full text-left px-5 py-3.5 flex items-center gap-3 text-sm font-bold transition-all">
                        <i class="fa-solid fa-sliders opacity-60"></i> Nâng cao
                    </button>
                </div>
                {{-- Nội dung tab bên phải --}}
                <div class="flex-1 p-8 bg-white overflow-hidden">
                    
                    {{-- Tab 1: Cài đặt chung --}}
                    <div x-show="activeDataTab === 'general'" x-cloak class="space-y-6 max-w-2xl">
                        <div class="grid grid-cols-1 md:grid-cols-[160px_1fr] items-center gap-4">
                            <label class="text-sm font-bold text-slate-500">Giá thông thường (₫)</label>
                            <div class="relative">
                                <input type="text" x-model="comparePriceStr" @input="comparePriceStr = formatMoney(comparePriceStr)"
                                       class="form-input !text-sm !py-2 bg-slate-50 border-slate-200 border-dashed hover:bg-white hover:border-blue-400 focus:bg-white transition-all">
                                <input type="hidden" name="compare_price" :value="parseMoney(comparePriceStr)">
                                <p class="text-[10px] text-slate-400 mt-1 uppercase font-black tracking-widest italic opacity-60">Giá thị trường hoặc giá gốc chưa giảm</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-[160px_1fr] items-center gap-4">
                            <label class="text-sm font-bold text-slate-700">Giá bán ưu đãi (A) (₫)</label>
                            <div class="relative">
                                <input type="text" x-model="mainPriceStr" @input="mainPriceStr = formatMoney(mainPriceStr)"
                                       class="form-input !text-base !py-3 font-black text-blue-600 border-blue-200 focus:ring-blue-100 shadow-sm transition-all focus:scale-[1.01]">
                                <input type="hidden" name="price" :value="parseMoney(mainPriceStr)">
                                <p class="text-[10px] text-blue-400 mt-1 uppercase font-black tracking-widest italic">Giá hiển thị bán cho khách hàng</p>
                            </div>
                        </div>
                    </div>

                    {{-- Tab 2: Kho hàng --}}
                    <div x-show="activeDataTab === 'inventory'" x-cloak class="space-y-6 max-w-2xl">
                        <div class="grid grid-cols-1 md:grid-cols-[160px_1fr] items-center gap-4">
                            <label class="text-sm font-bold text-slate-700">Mã SKU</label>
                            <input type="text" name="sku" x-model="parentSku" @input="generateVariants()" class="form-input !py-2 font-mono text-sm tracking-wider shadow-sm uppercase">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-[160px_1fr] items-center gap-4">
                            <label class="text-sm font-bold text-slate-700">Quản lý kho?</label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="manage_stock" value="1" class="w-4 h-4 rounded border-slate-300 text-blue-600">
                                <span class="text-sm text-slate-500">Bật quản lý kho hàng ở cấp độ sản phẩm</span>
                            </label>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-[160px_1fr] items-center gap-4">
                            <label class="text-sm font-bold text-slate-700">Số lượng kho</label>
                            <input type="number" name="stock" value="{{ old('stock', $product?->stock ?? 0) }}" class="form-input !py-2 w-32 shadow-sm">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-[160px_1fr] items-center gap-4">
                            <label class="text-sm font-bold text-slate-700">Trạng thái kho</label>
                            <select name="stock_status" class="form-select !py-2 w-48 text-sm font-bold shadow-sm">
                                <option value="in_stock"    {{ old('stock_status', $product?->stock_status ?? 'in_stock') === 'in_stock' ? 'selected' : '' }}>Còn hàng</option>
                                <option value="out_of_stock"{{ old('stock_status', $product?->stock_status) === 'out_of_stock' ? 'selected' : '' }}>Hết hàng</option>
                                <option value="backorder"   {{ old('stock_status', $product?->stock_status) === 'backorder'   ? 'selected' : '' }}>Đặt trước</option>
                            </select>
                        </div>
                    </div>

                    {{-- Tab 3: Vận chuyển --}}
                    <div x-show="activeDataTab === 'shipping'" x-cloak class="space-y-6 max-w-2xl">
                        <div class="grid grid-cols-1 md:grid-cols-[160px_1fr] items-center gap-4">
                            <label class="text-sm font-bold text-slate-700">Cân nặng (kg)</label>
                            <input type="text" name="weight" value="{{ old('weight', $product?->weight) }}" placeholder="0" class="form-input !py-2 w-32 shadow-sm font-bold text-slate-600">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-[160px_1fr] items-center gap-4">
                            <label class="text-sm font-bold text-slate-700">Kích thước (cm)</label>
                            <div class="flex items-center gap-2">
                                <input type="text" name="length" placeholder="Dài" class="form-input !py-2 w-24 text-center text-xs">
                                <input type="text" name="width" placeholder="Rộng" class="form-input !py-2 w-24 text-center text-xs">
                                <input type="text" name="height" placeholder="Cao" class="form-input !py-2 w-24 text-center text-xs">
                            </div>
                        </div>
                    </div>

                    {{-- Tab 4: Thuộc tính --}}
                    <div x-show="activeDataTab === 'attributes'" x-cloak class="space-y-6 overflow-y-auto max-h-[500px] pr-2 custom-scroll">
                        @if(isset($attributes) && $attributes->count() > 0)
                        <div class="flex items-center justify-between mb-4">
                            <p class="text-[11px] font-black text-slate-400 uppercase tracking-[.2em]">Lựa chọn thuộc tính sản phẩm</p>
                            <template x-if="productType == 1 && selectedAttributes.length > 0">
                                <button type="button" @click="generateVariants(); activeDataTab = 'variants'" 
                                        class="text-[10px] font-black bg-blue-50 text-blue-600 px-3 py-1.5 rounded-lg border border-blue-100 uppercase tracking-widest hover:bg-blue-600 hover:text-white transition-all flex items-center gap-2">
                                    Tiếp tục: Cấu hình biến thể <i class="fa-solid fa-arrow-right"></i>
                                </button>
                            </template>
                        </div>
                        <div class="space-y-4">
                                @foreach($attributes as $attribute)
                                <div class="bg-slate-50/50 p-5 rounded-2xl border border-slate-100 transition-all hover:bg-slate-50 group">
                                    <div class="flex items-center justify-between mb-4 pb-2 border-b border-white">
                                        <div class="flex items-center gap-3">
                                            <input type="checkbox" x-model="selectedAttributes" value="{{ $attribute->id }}"
                                                   class="w-4 h-4 rounded text-blue-600 accent-blue-600" @change="generateVariants()">
                                            <span class="text-sm font-bold text-slate-800 tracking-tight transition-colors group-hover:text-blue-600">{{ $attribute->name }}</span>
                                        </div>
                                    </div>
                                    <div class="flex flex-wrap gap-2.5">
                                        @foreach($attribute->values as $value)
                                        <label class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl border border-white bg-white cursor-pointer shadow-sm hover:border-blue-200 transition-all has-[:checked]:border-blue-600 has-[:checked]:bg-blue-600 has-[:checked]:text-white select-none">
                                            {{-- Use 'attributes' (simple) or 'selectedValues' (variable) based on productType --}}
                                            <template x-if="productType == 0">
                                                <input type="checkbox" name="attributes[{{ $attribute->id }}][]" value="{{ $value->id }}"
                                                       {{ isset($currentAttributes[$attribute->id]) && in_array($value->id, $currentAttributes[$attribute->id]) ? 'checked' : '' }}
                                                       class="hidden">
                                            </template>

                                            <template x-if="productType == 1">
                                                <input type="checkbox" x-model="selectedValues[{{ $attribute->id }}]" value="{{ $value->id }}"
                                                       @change="if($el.checked && !selectedAttributes.includes('{{ $attribute->id }}')) selectedAttributes.push('{{ $attribute->id }}'); generateVariants();" class="hidden">
                                            </template>
                                            
                                            <span class="text-xs font-bold">{{ $value->value }}</span>
                                        </label>
                                        @endforeach
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="p-10 text-center border-2 border-dashed border-slate-100 rounded-3xl">
                                <p class="text-sm text-slate-400 font-bold">Không có thuộc tính nào khả dụng.</p>
                            </div>
                        @endif
                    </div>

                    {{-- Tab 5: Các biến thể --}}
                    <div x-show="activeDataTab === 'variants' && productType == 1" x-cloak class="space-y-6 overflow-y-auto max-h-[500px] pr-2 custom-scroll">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex flex-col">
                                <p class="text-sm font-bold text-slate-800">Cấu hình biến thể</p>
                                <p class="text-[11px] text-slate-400 font-bold uppercase mt-1">Đã tạo <span x-text="variants.length" class="text-blue-600"></span> lựa chọn</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <button type="button" @click="generateVariants()" class="text-xs font-black text-white px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 shadow-lg shadow-blue-500/20 flex items-center gap-2 transition-all">
                                    <i class="fa-solid fa-wand-magic-sparkles"></i> TẠO BIẾN THỂ
                                </button>
                                <button type="button" @click="bulkEdit = !bulkEdit" class="text-xs font-bold text-slate-600 hover:text-blue-600 px-3 py-1.5 rounded-xl border border-slate-200 bg-white flex items-center gap-2 transition-all">
                                    <i class="fa-solid fa-pen-to-square"></i> Sửa hàng loạt
                                </button>
                            </div>
                        </div>

                        <div x-show="bulkEdit" x-cloak class="bg-blue-50 border border-blue-100 p-6 rounded-2xl mb-6 shadow-xl shadow-blue-500/5 grid grid-cols-1 sm:grid-cols-3 gap-6 items-end">
                            <div>
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-2">Giá đồng loạt</label>
                                <input type="number" x-model="bulkPrice" step="1000" class="form-input !py-2 text-sm bg-white border-blue-100">
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-2">Kho đồng loạt</label>
                                <input type="number" x-model="bulkStock" class="form-input !py-2 text-sm bg-white border-blue-100">
                            </div>
                            <button type="button" @click="applyBulk()" class="btn btn-primary !py-2.5 shadow-lg shadow-blue-500/20 uppercase tracking-widest font-black text-[10px]">CẬP NHẬT NGAY</button>
                        </div>

                        {{-- Section: Thêm biến thể thủ công --}}
                        <div class="bg-slate-50 border border-slate-100 p-6 rounded-2xl mb-6 shadow-sm">
                            <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                                <i class="fa-solid fa-plus-circle text-blue-500"></i> Thêm biến thể đơn lẻ
                            </p>
                            <div class="flex flex-wrap items-end gap-4">
                                <template x-for="aId in selectedAttributes" :key="aId">
                                    <div class="flex-1 min-w-[150px]">
                                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-tight block mb-1.5" x-text="window.attributeNamesMap[aId]"></label>
                                        <select :x-model="`manualVariantAttrs['${aId}']`" 
                                                class="form-select !py-2 !text-xs bg-white border-slate-200"
                                                x-on:change="manualVariantAttrs[aId] = $event.target.value">
                                            <option value="">-- Chọn --</option>
                                            <template x-for="v in (window.attributeValuesMap[aId] || [])" :key="v.id">
                                                <option :value="v.id" x-text="v.value"></option>
                                            </template>
                                        </select>
                                    </div>
                                </template>
                                <button type="button" @click="addManualVariant()" 
                                        class="h-[38px] px-6 bg-slate-900 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-blue-600 transition-all shadow-lg active:scale-95 flex items-center gap-2">
                                    <i class="fa-solid fa-check"></i> THÊM LẺ
                                </button>
                            </div>
                        </div>

                        <div class="border border-slate-100 rounded-2xl overflow-hidden shadow-sm bg-slate-50/30">
                            <table class="w-full text-sm">
                                <thead class="bg-white border-b border-slate-100 uppercase tracking-widest text-slate-400">
                                    <tr>
                                        <th class="px-5 py-4 text-left text-[11px] font-black w-10">#</th>
                                        <th class="px-5 py-4 text-left text-[11px] font-black">Thông tin biến thể</th>
                                        <th class="px-5 py-4 w-10 text-right"></th>
                                    </tr>
                                </thead>

                                <template x-for="(variant, idx) in variants" :key="idx">
                                    <tbody class="border-b border-slate-100 last:border-none">
                                        <tr class="hover:bg-slate-50 transition-colors cursor-pointer group" @click="toggleVariant(idx)">
                                            <td class="px-4 py-5 text-center">
                                                <i class="fa-solid fa-chevron-right text-[10px] text-slate-300 transition-transform" :class="expandedVariants.includes(idx) ? 'rotate-90 text-blue-500 font-black' : ''"></i>
                                            </td>
                                            <td class="px-5 py-5">
                                                <div class="flex items-center gap-4">
                                                    {{-- Thumbnail preview --}}
                                                    <div class="w-10 h-10 rounded-lg border border-slate-100 bg-white overflow-hidden flex-shrink-0">
                                                        <template x-if="variant.image">
                                                            <img :src="variant.image" class="w-full h-full object-cover">
                                                        </template>
                                                        <template x-if="!variant.image">
                                                            <div class="w-full h-full flex items-center justify-center bg-slate-50">
                                                                <i class="fa-solid fa-image text-slate-200 text-xs"></i>
                                                            </div>
                                                        </template>
                                                    </div>
                                                    <div class="flex flex-col">
                                                        <span class="text-xs font-black text-slate-700" x-text="variant.label"></span>
                                                        <div class="flex items-center gap-3 mt-1.5">
                                                            <span class="text-[9px] text-slate-400 font-bold uppercase tracking-tight">ID: <span x-text="variant.id || 'Mới'"></span></span>
                                                            <template x-if="variant.sku">
                                                                <span class="text-[9px] px-1.5 py-0.5 bg-slate-100 text-slate-500 rounded-md font-mono" x-text="variant.sku"></span>
                                                            </template>
                                                            <span x-show="variant.price" class="text-[9px] text-blue-600 font-black tracking-tight" x-text="new Intl.NumberFormat('vi-VN').format(variant.price) + '₫'"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- Hidden inputs for form submission --}}
                                                <template x-for="(attrValId, attrId) in variant.attributes">
                                                    <input type="hidden" :name="`variants[${idx}][attributes][${attrId}]`" :value="attrValId">
                                                </template>
                                                <input type="hidden" :name="`variants[${idx}][id]`" :value="variant.id || ''">
                                            </td>
                                            <td class="px-5 py-5 text-right">
                                                <button type="button" @click.stop="removeVariant(idx)" class="w-9 h-9 rounded-xl bg-slate-50 text-slate-300 hover:text-rose-600 hover:bg-rose-50 transition-all flex items-center justify-center group-hover:opacity-100 opacity-0 lg:opacity-30">
                                                    <i class="fa-solid fa-trash-can text-sm"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr x-show="expandedVariants.includes(idx)" x-cloak class="bg-slate-50/20 shadow-inner">
                                            <td colspan="3" class="px-8 py-8 border-t border-slate-50">
                                                <div class="grid grid-cols-1 md:grid-cols-[160px_1fr] gap-10">
                                                    {{-- Variant Image Picker --}}
                                                    <div class="space-y-3">
                                                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Ảnh biến thể</label>
                                                        <div class="relative group/img aspect-square rounded-[24px] border-2 border-dashed border-slate-200 bg-white flex items-center justify-center overflow-hidden hover:border-blue-400 transition-all cursor-pointer shadow-sm hover:shadow-xl hover:shadow-blue-500/5 active:scale-95"
                                                             @click="openMediaPicker(null, (url) => { variant.image = url; })">
                                                            <template x-if="variant.image">
                                                                <img :src="variant.image" class="w-full h-full object-cover">
                                                            </template>
                                                            <template x-if="!variant.image">
                                                                <div class="text-center">
                                                                    <i class="fa-solid fa-camera text-slate-200 text-2xl group-hover/img:text-blue-400 transition-colors mb-2 block"></i>
                                                                    <span class="text-[9px] text-slate-300 font-bold uppercase">Click chọn</span>
                                                                </div>
                                                            </template>
                                                            <div class="absolute inset-0 bg-blue-600/10 opacity-0 group-hover/img:opacity-100 transition-all"></div>
                                                            <input type="hidden" :name="`variants[${idx}][image]`" x-model="variant.image">
                                                        </div>
                                                    </div>

                                                    {{-- Variant Detailed Inputs --}}
                                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-6">
                                                        <div class="sm:col-span-2 space-y-1">
                                                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Mã SKU (Tự động)</label>
                                                            <div class="px-4 py-2.5 bg-slate-100 rounded-xl border border-slate-200 font-mono text-sm text-slate-600 tracking-widest"
                                                                 x-text="variant.sku || '---'"></div>
                                                            <input type="hidden" :name="`variants[${idx}][sku]`" x-model="variant.sku">
                                                        </div>
                                                        
                                                        <div>
                                                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1.5">Giá bán lẻ (₫)</label>
                                                            <div class="relative">
                                                                <input type="number" :name="`variants[${idx}][price]`" x-model="variant.price" 
                                                                       class="form-input !py-3 !text-sm !font-black text-blue-600 border-slate-100 focus:border-blue-400 shadow-sm">
                                                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs opacity-30 font-bold">VNĐ</span>
                                                            </div>
                                                        </div>
                                                        
                                                        <div>
                                                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1.5">Giá niêm yết (₫)</label>
                                                            <div class="relative">
                                                                <input type="number" :name="`variants[${idx}][compare_price]`" x-model="variant.compare_price" 
                                                                       class="form-input !py-3 !text-xs border-slate-100 focus:border-blue-400 shadow-sm">
                                                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-[10px] opacity-30">VNĐ</span>
                                                            </div>
                                                        </div>

                                                        <div>
                                                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1.5">Số lượng kho</label>
                                                            <input type="number" :name="`variants[${idx}][stock]`" x-model="variant.stock" 
                                                                   class="form-input !py-2.5 !text-sm border-slate-100 focus:border-blue-400 shadow-sm" placeholder="0">
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </template>
                            </table>
                            <template x-if="variants.length === 0">
                                <div class="p-16 text-center">
                                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                                        <i class="fa-solid fa-layer-group text-2xl"></i>
                                    </div>
                                    <p class="text-sm font-bold text-slate-400">Vui lòng chọn thuộc tính để tạo biến thể.</p>
                                </div>
                            </template>
                        </div>
                    </div>

                    {{-- Tab: Combo & Bán kèm --}}
                    <div x-show="activeDataTab === 'combos'" x-cloak class="space-y-6">
                        <div class="flex items-center justify-between mb-2">
                            <div>
                                <p class="text-sm font-bold text-slate-800 tracking-tight">Mua thêm Combo sản phẩm</p>
                                <p class="text-[11px] text-slate-400 font-bold uppercase mt-1">Thiết lập Upsell / Bán kèm sản phẩm</p>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div class="relative bg-blue-50/50 p-6 rounded-3xl border border-blue-100 shadow-sm mb-6">
                                <label class="text-[11px] font-black text-blue-600 uppercase tracking-widest block mb-4 flex items-center gap-2">
                                    <i class="fa-solid fa-plus-circle"></i> THIẾT LẬP COMBO HÀNG LOẠT
                                </label>
                                <div class="space-y-5">
                                    <div class="w-full">
                                        <label class="text-[10px] font-bold text-slate-400 uppercase mb-1.5 block">1. Tìm & Chọn sản phẩm mua kèm (Sản phẩm B)</label>
                                        <div class="relative" wire:ignore>
                                            <select id="combo-multi-select" 
                                                    class="form-select !py-2.5 !text-sm w-full border-slate-200">
                                                <option value="">--- Gõ tên sản phẩm cần thêm ---</option>
                                                @foreach($allProducts ?? [] as $p)
                                                    @if($p)
                                                        @if($p->has_variants && $p->variants->count() > 0)
                                                            <optgroup label="{{ $p->name }}">
                                                                @foreach($p->variants as $v)
                                                                    <option value="{{ $p->id }}:{{ $v->id }}" 
                                                                            data-image="{{ $v->image ? (str_starts_with($v->image,'http') ? $v->image : asset($v->image)) : ($p->image ? (str_starts_with($p->image,'http') ? $p->image : asset($p->image)) : asset('admin/images/placeholder.webp')) }}">
                                                                        {{ $p->name }} - {{ $v->label ?: ($v->sku ?: 'Variant #'.$v->id) }} 
                                                                        ({{ number_format($v->price ?: $p->price, 0, ',', '.') }}₫)
                                                                    </option>
                                                                @endforeach
                                                            </optgroup>
                                                        @else
                                                            <option value="{{ $p->id }}:0"
                                                                    data-image="{{ $p->image ? (str_starts_with($p->image,'http') ? $p->image : asset($p->image)) : asset('admin/images/placeholder.webp') }}">
                                                                {{ $p->name }} ({{ number_format($p->price, 0, ',', '.') }}₫)
                                                            </option>
                                                        @endif
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="flex flex-col lg:flex-row gap-4 items-end pt-5 border-t border-blue-100/50">
                                        <div class="flex-grow lg:max-w-[220px]">
                                            <label class="text-[10px] font-bold text-slate-400 uppercase mb-1.5 block">2. Mức giảm giá khi mua Combo</label>
                                            <div class="relative">
                                                <input type="text" x-model="batchDiscountValue" 
                                                       @input="if(batchDiscountType === 'fixed') { batchDiscountValue = formatMoney(batchDiscountValue) }"
                                                       class="form-input !py-2.5 !text-sm font-black border-slate-200 rounded-xl pr-16" placeholder="0">
                                                <div class="absolute right-2 top-1/2 -translate-y-1/2 flex items-center px-2 py-1 bg-slate-100 rounded-lg">
                                                    <span class="text-[10px] font-black text-slate-400" x-text="batchDiscountType === 'percent' ? '%' : '₫'"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="w-full lg:w-40">
                                            <label class="text-[10px] font-bold text-slate-400 uppercase mb-1.5 block">3. Loại giảm giá</label>
                                            <select x-model="batchDiscountType" class="form-select !py-2.5 !text-sm font-bold border-slate-200 rounded-xl bg-slate-50 border-dashed">
                                                <option value="percent">Giảm theo %</option>
                                                <option value="fixed">Giảm trừ Tiền mặt</option>
                                            </select>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <button type="button" @click="addImmediateCombo()" 
                                                    class="h-[46px] px-10 bg-blue-600 text-white rounded-xl text-[11px] font-black uppercase tracking-widest hover:bg-blue-700 shadow-xl shadow-blue-500/20 transition-all active:scale-95 flex items-center gap-2 whitespace-nowrap">
                                                <i class="fa-solid fa-plus font-bold"></i> THÊM VÀO DANH SÁCH
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <p class="text-[10px] text-slate-400 font-bold mt-3 italic">* Các sản phẩm đã chọn sẽ được thêm vào danh sách combo bên dưới với mức giảm tương ứng (So với Tổng A+B).</p>
                            </div>

                            <div class="border border-slate-100 rounded-[28px] overflow-hidden bg-slate-50/20 shadow-sm">
                                <table class="w-full text-sm">
                                    <tbody class="divide-y divide-slate-100">
                                        <template x-for="(c, idx) in combos" :key="idx">
                                            <tr class="bg-white/50 hover:bg-white transition-all duration-300 group">
                                                <td class="px-6 py-5">
                                                    <div class="flex items-center gap-4">
                                                        <div class="w-12 h-12 rounded-2xl bg-white border border-slate-100 overflow-hidden shadow-sm flex-shrink-0 group-hover:scale-105 transition-transform duration-300">
                                                            <img :src="c.image || '/admin/images/placeholder.webp'" class="w-full h-full object-cover">
                                                        </div>
                                                        <div class="flex flex-col">
                                                            <span class="text-[13px] font-black text-slate-700" x-text="c.name"></span>
                                                            <div class="flex items-center gap-2 mt-1.5">
                                                                <span class="px-2 py-0.5 bg-slate-100 text-[9px] font-black text-slate-400 uppercase rounded-lg tracking-widest">ID: <span x-text="c.id"></span></span>
                                                                <template x-if="c.variant_id">
                                                                    <span class="px-2 py-0.5 bg-blue-50 text-[9px] font-black text-blue-400 uppercase rounded-lg tracking-widest">VARIANT: <span x-text="c.variant_id"></span></span>
                                                                </template>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    {{-- Hidden Inputs for persistence --}}
                                                    <input type="hidden" :name="`combos[${idx}][id]`" :value="c.id">
                                                    <input type="hidden" :name="`combos[${idx}][variant_id]`" :value="c.variant_id">
                                                    <input type="hidden" :name="`combos[${idx}][discount_type]`" :value="batchDiscountType">
                                                    <input type="hidden" :name="`combos[${idx}][discount_value]`" :value="parseMoney(batchDiscountValue)">
                                                </td>
                                                <td class="px-6 py-5 text-right">
                                                    <button type="button" @click="removeCombo(idx)" 
                                                            class="w-10 h-10 rounded-2xl bg-rose-50 text-rose-500 hover:bg-rose-500 hover:text-white transition-all duration-300 flex items-center justify-center border border-rose-100/50 shadow-sm active:scale-90 group-hover:shadow-rose-200">
                                                        <i class="fa-solid fa-trash-can text-sm"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </template>
                                        <template x-if="combos.length === 0">
                                            <tr>
                                                <td class="px-8 py-20 text-center">
                                                    <div class="max-w-[200px] mx-auto opacity-20 group">
                                                        <div class="w-16 h-16 bg-slate-200 rounded-[2rem] flex items-center justify-center mx-auto mb-4 group-hover:rotate-12 transition-transform duration-500">
                                                            <i class="fa-solid fa-layer-group text-2xl text-slate-400"></i>
                                                        </div>
                                                        <p class="text-[11px] font-black uppercase tracking-[.2em] text-slate-500 leading-relaxed">Danh sách combo trống</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- Tab 6: Nâng cao --}}
                    <div x-show="activeDataTab === 'advanced'" x-cloak class="space-y-6 max-w-2xl">
                         <div class="grid grid-cols-1 md:grid-cols-[160px_1fr] items-center gap-4">
                            <label class="text-sm font-bold text-slate-700">Thứ tự menu</label>
                            <input type="number" name="sort_order" 
                                   value="{{ old('sort_order', $product?->sort_order ?? 0) }}" 
                                   class="form-input !py-2 w-32 shadow-sm">
                        </div>
                        {{-- Removed Allow reviews checkbox --}}
                    </div>

                </div>
            </div>
        </div>

        @include('components.admin.seo-checklist', ['context' => 'product', 'model' => $product])
    </div>

    {{-- ═══ CỘT PHẢI: Metadata & Media ═══ --}}
    <div class="space-y-6">

        {{-- Xuất bản --}}
        <div class="card shadow-md border-slate-200 overflow-hidden">
            <div class="card-header bg-slate-900 py-3.5">
                <p class="text-[10px] font-black text-white uppercase tracking-[.25em] flex items-center gap-2">
                    <i class="fa-solid fa-cloud-arrow-up text-blue-400"></i> Trạng thái & Xuất bản
                </p>
            </div>
            <div class="card-body space-y-6">
                <div>
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2.5 block">Khả năng hiển thị</label>
                    <select name="status" class="form-select !py-2.5 font-black text-sm border-slate-200">
                        <option value="active"   {{ old('status', $product?->status ?? 'active') === 'active' ? 'selected' : '' }}>CÔNG KHAI (SẴN SÀNG)</option>
                        <option value="inactive" {{ old('status', $product?->status) === 'inactive' ? 'selected' : '' }}>TẠM ẨN (LƯU TRỮ)</option>
                        <option value="draft"    {{ old('status', $product?->status) === 'draft'    ? 'selected' : '' }}>BẢN NHÁP (ĐANG SỬA)</option>
                    </select>
                </div>

                <div class="p-5 bg-blue-50/50 rounded-2xl border border-blue-100/50 space-y-5">
                    {{-- Nổi bật --}}
                    <label class="flex items-start gap-3 cursor-pointer group">
                        <div class="mt-1">
                            <input type="checkbox" name="is_featured" value="1"
                                   {{ old('is_featured', $product?->is_featured) ? 'checked' : '' }}
                                   class="w-5 h-5 accent-amber-500 rounded-lg border-amber-200 cursor-pointer">
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-bold text-slate-800 group-hover:text-amber-600 transition-colors">Sản phẩm nổi bật</p>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tight mt-0.5">Hiển thị tại trang tiêu điểm</p>
                        </div>
                    </label>

                    {{-- Yêu thích --}}
                    <label class="flex items-start gap-3 cursor-pointer group">
                        <div class="mt-1">
                            <input type="checkbox" name="is_favorite" value="1"
                                   {{ old('is_favorite', $product?->is_favorite) ? 'checked' : '' }}
                                   class="w-5 h-5 accent-rose-500 rounded-lg border-rose-200 cursor-pointer">
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-bold text-slate-800 group-hover:text-rose-600 transition-colors">Sản phẩm yêu thích</p>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tight mt-0.5">Ưu tiên hiển thị cho khách quen</p>
                        </div>
                    </label>

                    {{-- Bán chạy --}}
                    <label class="flex items-start gap-3 cursor-pointer group">
                        <div class="mt-1">
                            <input type="checkbox" name="is_best_seller" value="1"
                                   {{ old('is_best_seller', $product?->is_best_seller) ? 'checked' : '' }}
                                   class="w-5 h-5 accent-blue-500 rounded-lg border-blue-200 cursor-pointer">
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-bold text-slate-800 group-hover:text-blue-600 transition-colors">Sản phẩm bán chạy</p>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tight mt-0.5">Gán nhãn Best Seller tự động</p>
                        </div>
                    </label>
                </div>

                <div class="pt-4 border-t border-slate-50 flex flex-col gap-3">
                    <button type="submit" class="btn btn-primary w-full justify-center py-4 shadow-xl shadow-blue-600/20 group relative overflow-hidden transition-all hover:scale-[1.02] active:scale-[0.98]">
                        <span class="relative z-10 flex items-center gap-2 font-black tracking-widest text-[11px]">
                            <i class="fa-solid {{ $isEdit ? 'fa-floppy-disk' : 'fa-plus-circle' }} text-lg"></i>
                            {{ $isEdit ? 'LƯU THÀNH QUẢ' : 'TẠO SẢN PHẨM MỚI' }}
                        </span>
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="btn bg-white hover:bg-slate-50 text-slate-400 w-full justify-center py-3 text-[10px] font-black tracking-widest border border-slate-200 rounded-xl">HỦY BỎ THAY ĐỔI</a>
                </div>
            </div>
        </div>

        {{-- Hình ảnh --}}
        <div class="card shadow-sm border-slate-200" x-data="galleryManager(window.productInitData.gallery)" x-cloak>
            <div class="card-header bg-slate-50 flex items-center justify-between py-3.5">
                <p class="text-[11px] font-black text-slate-500 uppercase tracking-widest flex items-center gap-2">
                    <i class="fa-solid fa-photo-film text-orange-400"></i> Gallery Hình ảnh
                </p>
            </div>
            <div class="card-body space-y-6">
                {{-- Ảnh chính --}}
                <div class="group relative aspect-square rounded-[32px] border-4 border-dashed border-slate-100 bg-white p-2 flex items-center justify-center overflow-hidden transition-all hover:border-blue-400 hover:shadow-2xl hover:shadow-blue-500/10 active:scale-95">
                    <input type="hidden" name="image" id="product_main_image" value="{{ old('image', $product?->image) }}">
                    <img id="main_img_preview" src="{{ $product?->image ?: asset('admin/images/placeholder.webp') }}"
                         class="w-full h-full rounded-[24px] object-cover transition-transform duration-500 group-hover:scale-110 {{ !$product?->image ? 'opacity-20 grayscale' : '' }}">

                    <div class="absolute inset-0 bg-slate-900/60 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all backdrop-blur-[2px]">
                        <button type="button" onclick="openMediaPicker('product_main_image', function(url){ document.getElementById('main_img_preview').src = url; document.getElementById('main_img_preview').classList.remove('opacity-20', 'grayscale'); })"
                                class="bg-white text-slate-900 w-12 h-12 rounded-2xl shadow-2xl flex items-center justify-center hover:scale-110 active:scale-90 transition-all">
                            <i class="fa-solid fa-camera-retro text-xl"></i>
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-4 gap-2" id="gallery-container">
                    <template x-for="(img, idx) in gallery" :key="idx">
                        <div class="relative aspect-square rounded-xl border-2 border-slate-50 overflow-hidden shadow-sm group">
                            <img :src="img" class="w-full h-full object-cover transition-transform group-hover:scale-125">
                            <button type="button" @click="removeImage(idx)"
                                    class="absolute inset-0 bg-rose-600/80 text-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all backdrop-blur-[1px]">
                                <i class="fa-solid fa-trash-can text-sm"></i>
                            </button>
                        </div>
                    </template>

                    <button type="button" @click="addImage()"
                            class="aspect-square rounded-xl border-2 border-dashed border-slate-200 flex flex-col items-center justify-center text-slate-300 hover:text-blue-500 hover:border-blue-500 hover:bg-blue-50 transition-all">
                        <i class="fa-solid fa-circle-plus text-base mb-1"></i>
                    </button>
                </div>
                <input type="hidden" name="images_raw" :value="gallery.join('\n')">
            </div>
        </div>

        {{-- Phân loại --}}
        <div class="card shadow-sm border-slate-200">
             <div class="card-header bg-slate-50 py-3.5">
                <p class="text-[11px] font-black text-slate-500 uppercase tracking-widest flex items-center gap-2">
                    <i class="fa-solid fa-tags text-teal-400"></i> Cấu trúc danh mục
                </p>
            </div>
            <div class="card-body">
                @if(isset($categories) && count($categories) > 0)
                <div class="max-h-[300px] overflow-y-auto space-y-2 bg-slate-50/30 p-4 rounded-2xl border border-slate-50 pr-2 custom-scroll">
                    @php $selectedCats = old('category_ids', $isEdit ? $product->categories->pluck('id')->toArray() : []); @endphp
                    @foreach($categories as $cat)
                    <div class="flex items-center gap-2.5 py-1">
                        <input type="checkbox" name="category_ids[]" value="{{ $cat->id }}" 
                               id="cat_{{ $cat->id }}"
                               {{ in_array($cat->id, $selectedCats) ? 'checked' : '' }}
                               class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                        <label for="cat_{{ $cat->id }}" class="text-xs font-bold text-slate-600 hover:text-slate-900 cursor-pointer select-none">
                            {{ $cat->label_indented ?? $cat->name }}
                        </label>
                    </div>
                    @endforeach
                </div>
                @else
                    <p class="text-xs text-slate-400 font-bold">Chưa tạo danh mục nào.</p>
                @endif
            </div>
        </div>

        {{-- SEO --}}
        <div class="card shadow-sm border-slate-200 border-l-4 border-l-teal-400">
            <div class="card-body space-y-5">
                <p class="text-[10px] font-black text-teal-600 uppercase tracking-[.2em] flex items-center gap-2 mb-2">
                    <i class="fa-solid fa-search"></i> SEO Tối ưu hóa
                </p>
                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5 block">Tiêu đề SEO</label>
                    <input type="text" name="meta_title" value="{{ old('meta_title', $product?->meta_title) }}"
                           class="form-input !py-2 !text-xs bg-slate-50 border-none shadow-inner" placeholder="Tự động lấy tên SP nếu để trống">
                </div>
                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5 block">Mô tả SEO</label>
                    <textarea name="meta_description" rows="3" class="form-input !py-2 !text-xs bg-slate-50 border-none shadow-inner resize-none"
                               placeholder="Trích đoạn thu hút người dùng trên Google...">{{ old('meta_description', $product?->meta_description) }}</textarea>
                </div>
                <div class="flex items-center gap-2 p-2.5 bg-slate-100/50 rounded-xl border border-slate-100">
                    <span class="text-[10px] font-black text-slate-400">URL:</span>
                    <input type="text" name="slug" value="{{ old('slug', $product?->slug) }}"
                           class="flex-1 bg-transparent border-none p-0 text-[10px] font-mono text-slate-500 focus:ring-0" placeholder="duong-dan-san-pham">
                    @if($product?->slug)
                        <a href="{{ url('/' . $product->slug) }}" target="_blank" class="text-[10px] font-bold text-blue-500 hover:text-blue-600 transition flex items-center gap-1 ml-2">
                             <i class="fa-solid fa-eye"></i> Xem shop
                        </a>
                    @endif
                </div>
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5 block">Từ khóa</label>
                        <input type="text" name="meta_keywords" value="{{ old('meta_keywords', $product?->meta_keywords) }}"
                               class="form-input !py-2 !text-xs bg-slate-50 border-none shadow-inner" placeholder="từ khóa 1, từ khóa 2">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5 block">Canonical URL</label>
                        <input type="url" name="canonical_url" value="{{ old('canonical_url', $product?->canonical_url) }}"
                               class="form-input !py-2 !text-xs bg-slate-50 border-none shadow-inner" placeholder="https://...">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        const $el = $('#combo-multi-select');

        function formatProduct(state) {
            if (!state.id) return state.text;
            const img = $(state.element).data('image');
            const $state = $(
                '<div class="flex items-center gap-3 py-1">' +
                    '<div class="w-10 h-10 rounded-lg overflow-hidden border border-slate-100 flex-shrink-0 bg-white">' +
                        '<img src="' + img + '" class="w-full h-full object-cover" />' +
                    '</div>' +
                    '<div class="flex flex-col">' +
                        '<span class="text-[11px] font-black text-slate-700 leading-tight mb-0.5">' + state.text + '</span>' +
                        '<span class="text-[9px] text-blue-500 font-bold uppercase tracking-tight">Sẵn sàng chọn</span>' +
                    '</div>' +
                '</div>'
            );
            return $state;
        }

        $el.select2({
            placeholder: "--- Gõ tên sản phẩm cần thêm ---",
            allowClear: true,
            width: '100%',
            templateResult: formatProduct,
            templateSelection: formatProduct,
            dropdownCssClass: 'select2-combos-dropdown'
        });

        // Sync Select2 with Alpine.js (Search & Push mode)
        $el.on('select2:select', function (e) {
            const data = e.params.data.id;
            if (!data) return;

            const alpineEl = document.querySelector('[x-data*="productFormManager"]');
            if (alpineEl) {
                let alpineStore = null;
                if (window.Alpine && typeof window.Alpine.$data === 'function') {
                    alpineStore = window.Alpine.$data(alpineEl);
                } else if (alpineEl._x_dataStack) {
                    alpineStore = alpineEl._x_dataStack[0];
                } else if (alpineEl.__x) {
                    alpineStore = alpineEl.__x.$data;
                }

                if (alpineStore) {
                    if (!alpineStore.batchSelectedProducts.includes(data)) {
                        alpineStore.batchSelectedProducts.push(data);
                    }
                }
            }
            
            // Clear selection after add
            $(this).val(null).trigger('change');
        });
    });
</script>
<style>
    .custom-scroll::-webkit-scrollbar { width: 4px; }
    .custom-scroll::-webkit-scrollbar-track { background: transparent; }
    .custom-scroll::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    .custom-scroll::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
    
    /* Select2 Tweaks */
    .select2-combos-dropdown .select2-results__option {
        padding: 6px 12px !important;
        border-bottom: 1px solid #f1f5f9;
    }
    .select2-combos-dropdown .select2-results__option--highlighted {
        background-color: #f8fafc !important;
        color: inherit !important;
    }
    .select2-combos-dropdown .select2-results__group {
        font-size: 10px !important;
        font-weight: 900 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.05em !important;
        color: #94a3b8 !important;
        background: #f8fafc !important;
        padding: 8px 12px !important;
    }
    .select2-container--default .select2-selection--multiple {
        border-color: #e2e8f0 !important;
        border-radius: 0.75rem !important;
        padding: 4px 8px !important;
        min-height: 45px !important;
        transition: all 0.2s;
    }
    .select2-container--default.select2-container--focus .select2-selection--multiple {
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #eff6ff !important;
        border-color: #dbeafe !important;
        color: #1e40af !important;
        border-radius: 6px !important;
        font-size: 11px !important;
        font-weight: 700 !important;
        padding: 2px 8px !important;
    }
</style>
@endpush
