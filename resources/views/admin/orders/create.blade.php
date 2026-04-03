@extends('admin.layouts.app')
@section('title', 'Tạo đơn hàng mới')
@section('page-title', 'Tạo đơn hàng')
@section('page-subtitle', 'Tạo đơn hàng thủ công cho khách hàng')

@section('content')
<form action="{{ route('admin.orders.store') }}" method="POST" x-data="orderManager()" x-init="init()">
    @csrf
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left: Order Items --}}
        <div class="lg:col-span-2 flex flex-col gap-6">
            <div class="card p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-base font-bold text-slate-800">Sản phẩm trong đơn</h3>
                    <button type="button" @click="addItem()" class="btn-secondary py-1.5 px-3 text-xs">
                        <i class="fa-solid fa-plus mr-1"></i> Thêm sản phẩm
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="text-left bg-slate-50 border-y border-slate-100">
                                <th class="py-3 px-4 text-[10px] font-black uppercase text-slate-400">Sản phẩm</th>
                                <th class="py-3 px-4 text-[10px] font-black uppercase text-slate-400 w-32">Giá</th>
                                <th class="py-3 px-4 text-[10px] font-black uppercase text-slate-400 w-24">SL</th>
                                <th class="py-3 px-4 text-[10px] font-black uppercase text-slate-400 w-32 text-right">Tổng</th>
                                <th class="py-3 px-4 w-10"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <template x-for="(item, index) in items" :key="index">
                                <tr>
                                    <td class="py-4 px-4 min-w-[300px]">
                                        <select :name="`items[${index}][product_id]`" 
                                                x-model="item.product_id"
                                                @change="onProductChange(index)"
                                                class="form-select w-full text-sm" required>
                                            <option value="">Chọn sản phẩm</option>
                                            @foreach($products as $p)
                                            <option value="{{ $p->id }}" data-price="{{ $p->price }}">{{ $p->name }}</option>
                                            @endforeach
                                        </select>

                                        {{-- Variant selection if product has variants --}}
                                        <div class="mt-2" x-show="getVariants(item.product_id).length > 0">
                                            <select :name="`items[${index}][variant_id]`" 
                                                    x-model="item.variant_id"
                                                    @change="onVariantChange(index)"
                                                    class="form-select w-full text-xs text-slate-500 bg-slate-50 border-dashed">
                                                <option value="">Chọn biến thể (mặc định theo giá gốc)</option>
                                                <template x-for="v in getVariants(item.product_id)" :key="v.id">
                                                    <option :value="v.id" x-text="v.label + ' - ' + formatCurrency(v.price)"></option>
                                                </template>
                                            </select>
                                        </div>
                                    </td>
                                    <td class="py-4 px-4">
                                        <span class="text-sm font-semibold text-slate-600" x-text="formatCurrency(item.price)"></span>
                                    </td>
                                    <td class="py-4 px-4">
                                        <input type="number" :name="`items[${index}][quantity]`" 
                                               x-model.number="item.quantity"
                                               class="form-input w-20 text-center py-1.5" min="1" required>
                                    </td>
                                    <td class="py-4 px-4 text-right">
                                        <span class="text-sm font-bold text-slate-900" x-text="formatCurrency(item.price * item.quantity)"></span>
                                    </td>
                                    <td class="py-4 px-4">
                                        <button type="button" @click="removeItem(index)" class="text-slate-300 hover:text-red-500 transition-colors">
                                            <i class="fa-solid fa-trash-can text-sm"></i>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                @error('items')
                <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="card p-6">
                <h3 class="text-base font-bold text-slate-800 mb-4">Ghi chú & Tùy chọn</h3>
                <div class="space-y-4">
                    <div>
                        <label class="form-label">Ghi chú của khách hàng</label>
                        <textarea name="customer_note" rows="3" class="form-input" placeholder="Yêu cầu đặc biệt của khách..."></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Phương thức thanh toán</label>
                            <select name="payment_method" class="form-select">
                                <option value="cod">COD (Thanh toán khi nhận hàng)</option>
                                <option value="bank_transfer">Chuyển khoản ngân hàng</option>
                                <option value="other">Khác</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Phí vận chuyển</label>
                            <input type="number" name="shipping_fee" x-model.number="shippingFee" class="form-input">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right: Customer Info & Summary --}}
        <div class="flex flex-col gap-6">
            <div class="card p-6">
                <h3 class="text-base font-bold text-slate-800 mb-4">Thông tin khách hàng</h3>
                <div class="space-y-4">
                    <div>
                        <label class="form-label">Họ và tên *</label>
                        <input type="text" name="customer_name" class="form-input" placeholder="VD: Nguyễn Văn A" required>
                    </div>
                    <div>
                        <label class="form-label">Số điện thoại *</label>
                        <input type="text" name="customer_phone" class="form-input" placeholder="0xxx xxx xxx" required>
                    </div>
                    <div>
                        <label class="form-label">Email (không bắt buộc)</label>
                        <input type="email" name="customer_email" class="form-input" placeholder="example@gmail.com">
                    </div>
                    <x-address-selector 
                        container-class="grid grid-cols-1 md:grid-cols-2 gap-4"
                        col-class=""
                        select-class="form-select"
                    />
                    <div class="mt-4">
                        <label class="form-label">Số nhà, tên đường *</label>
                        <input type="text" name="street_address" class="form-input" placeholder="VD: 123 Nguyễn Huệ" required>
                    </div>
                </div>
            </div>

            <div class="card p-6 bg-slate-50 border-slate-200">
                <h3 class="text-base font-bold text-slate-800 mb-4">Tổng cộng</h3>
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Tạm tính:</span>
                        <span class="font-semibold text-slate-700" x-text="formatCurrency(subtotal)"></span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Phí vận chuyển:</span>
                        <span class="font-semibold text-slate-700" x-text="formatCurrency(shippingFee)"></span>
                    </div>
                    <div class="pt-3 border-t border-slate-200 flex justify-between">
                        <span class="text-base font-bold text-slate-800">Tổng thanh toán:</span>
                        <span class="text-xl font-black text-blue-600" x-text="formatCurrency(subtotal + shippingFee)"></span>
                    </div>
                </div>

                <div class="mt-6 space-y-4">
                    <div>
                        <label class="form-label">Trạng thái đơn hàng</label>
                        <select name="status" class="form-select">
                            @foreach($statuses as $key => $s)
                            <option value="{{ $key }}">{{ $s['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Trạng thái thanh toán</label>
                        <select name="payment_status" class="form-select">
                            @foreach($paymentStatuses as $key => $s)
                            <option value="{{ $key }}">{{ $s['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn-primary w-full py-3 mt-6 text-base font-black uppercase tracking-wider">
                    <i class="fa-solid fa-check mr-2"></i> Xác nhận tạo đơn
                </button>
                <a href="{{ route('admin.orders.index') }}" class="block text-center mt-4 text-xs font-bold text-slate-400 hover:text-slate-600 transition-colors">
                    Hủy bỏ và quay lại
                </a>
            </div>
        </div>
    </div>
</form>

<script>
function orderManager() {
    return {
        items: [],
        products: @json($products),
        shippingFee: 30000,
        subtotal: 0,

        init() {
            this.addItem();
            this.$watch('items', () => this.calculateSubtotal(), { deep: true });
        },

        addItem() {
            this.items.push({
                product_id: '',
                variant_id: '',
                price: 0,
                quantity: 1
            });
        },

        removeItem(index) {
            if (this.items.length > 1) {
                this.items.splice(index, 1);
            }
        },

        getVariants(productId) {
            if (!productId) return [];
            const p = this.products.find(p => p.id == productId);
            if (!p || !p.variants) return [];
            
            return p.variants.map(v => {
                const label = v.attribute_values.map(av => av.value).join(' / ');
                return {
                    id: v.id,
                    label: label || 'Biến thể #' + v.id,
                    price: v.price || p.price
                };
            });
        },

        onProductChange(index) {
            const item = this.items[index];
            const p = this.products.find(p => p.id == item.product_id);
            if (p) {
                item.price = p.price;
                item.variant_id = ''; // Reset variant
            } else {
                item.price = 0;
            }
        },

        onVariantChange(index) {
            const item = this.items[index];
            const p = this.products.find(p => p.id == item.product_id);
            if (p && item.variant_id) {
                const v = p.variants.find(v => v.id == item.variant_id);
                if (v) {
                    item.price = v.price || p.price;
                }
            } else if (p) {
                item.price = p.price;
            }
        },

        calculateSubtotal() {
            this.subtotal = this.items.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        },

        formatCurrency(val) {
            return new Intl.NumberFormat('vi-VN').format(val) + '₫';
        }
    }
}
</script>
@endsection
