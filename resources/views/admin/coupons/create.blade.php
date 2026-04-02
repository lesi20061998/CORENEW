@extends('admin.layouts.app')
@section('title', 'Tạo mã giảm giá')
@section('page-title', 'Tạo mã giảm giá mới')
@section('page-actions')
    <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">
        <i class="fa-solid fa-arrow-left mr-2"></i> Quay lại
    </a>
@endsection

@section('content')
<form action="{{ route('admin.coupons.store') }}" method="POST">
    @csrf
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-6">
            <div class="card">
                <div class="card-header"><h3 class="card-title">Thông tin cơ bản</h3></div>
                <div class="card-body space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label font-bold">Mã giảm giá (Code) <span class="text-rose-500">*</span></label>
                            <input type="text" name="code" value="{{ old('code') }}" class="form-input uppercase" placeholder="VD: GiamGia20K" required>
                            @error('code') <p class="text-rose-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="form-label font-bold">Tên chương trình (Gợi nhớ)</label>
                            <input type="text" name="name" value="{{ old('name') }}" class="form-input" placeholder="VD: Lì xì tết 2026">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h3 class="card-title">Giá trị & Điều kiện</h3></div>
                <div class="card-body space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="form-label font-bold">Loại giảm giá</label>
                            <select name="type" class="form-select">
                                <option value="fixed" {{ old('type') == 'fixed' ? 'selected' : '' }}>Số tiền cố định (VNĐ)</option>
                                <option value="percentage" {{ old('type') == 'percentage' ? 'selected' : '' }}>Phần trăm (%)</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label font-bold">Giá trị giảm <span class="text-rose-500">*</span></label>
                            <input type="text" name="value" value="{{ old('value') }}" class="form-input money-input" required>
                        </div>
                        <div>
                            <label class="form-label font-bold">Giá trị tối thiểu đơn hàng</label>
                            <input type="text" name="min_order_value" value="{{ old('min_order_value', 0) }}" class="form-input money-input">
                            <span class="text-[10px] text-slate-400">Đơn đạt mức này mới được áp dụng</span>
                        </div>
                    </div>

                    <div id="max_discount_container" class="{{ old('type') == 'percentage' ? '' : 'hidden' }}">
                        <label class="form-label font-bold">Giảm tối đa (VNĐ)</label>
                        <input type="text" name="max_discount_value" value="{{ old('max_discount_value') }}" class="form-input money-input" placeholder="Bỏ trống nếu không giới hạn">
                        <span class="text-[10px] text-slate-400">VD: Giảm 10% nhưng không quá 50.000đ</span>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h3 class="card-title">Giới hạn sử dụng</h3></div>
                <div class="card-body space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label font-bold">Tổng lượt sử dụng tối đa</label>
                            <input type="number" name="usage_limit" value="{{ old('usage_limit') }}" class="form-input" placeholder="Vô hạn">
                        </div>
                        <div>
                            <label class="form-label font-bold">Lượt sử dụng / Mỗi khách hàng</label>
                            <input type="number" name="usage_limit_per_user" value="{{ old('usage_limit_per_user', 1) }}" class="form-input" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="card">
                <div class="card-header"><h3 class="card-title">Thời gian hiệu lực</h3></div>
                <div class="card-body space-y-4">
                    <div>
                        <label class="form-label font-bold">Ngày bắt đầu</label>
                        <input type="datetime-local" name="start_date" value="{{ old('start_date') }}" class="form-input">
                    </div>
                    <div>
                        <label class="form-label font-bold">Ngày kết thúc</label>
                        <input type="datetime-local" name="end_date" value="{{ old('end_date') }}" class="form-input">
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h3 class="card-title">Trạng thái</h3></div>
                <div class="card-body">
                    <select name="is_active" class="form-select">
                        <option value="1" {{ old('is_active', 1) == 1 ? 'selected' : '' }}>Kích hoạt</option>
                        <option value="0" {{ old('is_active', 1) == 0 ? 'selected' : '' }}>Tạm dừng</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-full py-4 text-lg">
                <i class="fa-solid fa-floppy-disk mr-2"></i> Lưu mã giảm giá
            </button>
        </div>
    </div>
</form>

@push('scripts')
<script>
    document.querySelector('select[name="type"]').addEventListener('change', function() {
        const container = document.getElementById('max_discount_container');
        if (this.value === 'percentage') {
            container.classList.remove('hidden');
        } else {
            container.classList.add('hidden');
        }
    });

    // Money Input Formatter
    function formatMoney(value) {
        if (!value) return "";
        let number = value.replace(/\D/g, "");
        return new Intl.NumberFormat('vi-VN').format(number);
    }

    document.querySelectorAll('.money-input').forEach(input => {
        // Initial format
        input.value = formatMoney(input.value);

        input.addEventListener('input', function(e) {
            let cursorPosition = this.selectionStart;
            let originalLength = this.value.length;
            
            this.value = formatMoney(this.value);
            
            let newLength = this.value.length;
            this.setSelectionRange(cursorPosition + (newLength - originalLength), cursorPosition + (newLength - originalLength));
        });
    });
</script>
@endpush
@endsection
