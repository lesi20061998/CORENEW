@extends('admin.layouts.app')

@section('title', 'Cấu hình Đánh giá sao')
@section('page-title', 'Đánh giá sao')
@section('page-subtitle', 'Quản lý cấu hình đánh giá bài viết, sản phẩm')

@section('page-actions')
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.settings.index') }}"
            class="btn btn-secondary bg-blue-500 text-white border-0 hover:bg-blue-600">
            <i class="fa-solid fa-rotate-left text-xs"></i> Quay lại
        </a>
        <button type="submit" form="review-settings-form"
            class="btn btn-primary bg-emerald-500 text-white border-0 hover:bg-emerald-600">
            <i class="fa-solid fa-save text-xs"></i> Lưu
        </button>
    </div>
@endsection

@section('content')
    @php
        $map = $settingsMap;
    @endphp

    <form action="{{ route('admin.settings.group.update', 'review') }}" method="POST" id="review-settings-form">
        @csrf @method('PUT')

        <div class="space-y-6 max-w-5xl">

            {{-- Section 1: Cấu hình chung --}}
            <div class="card shadow-sm border-slate-200">
                <div class="card-body p-6 space-y-6">
                    <div class="row g-4">
                        <div class="col-md-4">
                            <label class="form-label font-bold text-slate-700">Bật / Tắt đánh giá sản phẩm</label>
                            <div class="mt-2">
                                <input type="hidden" name="settings[review_product_enabled]" value="0">
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <div class="relative">
                                        <input type="checkbox" name="settings[review_product_enabled]" value="1" {{ ($map['review_product_enabled'] ?? '1') == '1' ? 'checked' : '' }}
                                            class="sr-only peer">
                                        <div
                                            class="w-12 h-6 bg-slate-200 rounded-full peer peer-checked:bg-blue-500 peer-checked:after:translate-x-6 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all">
                                        </div>
                                    </div>
                                    <span
                                        class="text-xs font-black uppercase tracking-widest text-slate-400 peer-checked:text-blue-500"
                                        x-text="'{{ ($map['review_product_enabled'] ?? '1') == '1' ? 'YES' : 'NO' }}'"></span>
                                </label>
                            </div>
                            <p class="text-[11px] text-slate-400 mt-2">Bật tùy chọn này khi sử dụng đánh giá sao trong sản
                                phẩm.</p>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label font-bold text-slate-700">Bật / Tắt đánh giá bài viết</label>
                            <div class="mt-2">
                                <input type="hidden" name="settings[review_post_enabled]" value="0">
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <div class="relative">
                                        <input type="checkbox" name="settings[review_post_enabled]" value="1" {{ ($map['review_post_enabled'] ?? '0') == '1' ? 'checked' : '' }}
                                            class="sr-only peer">
                                        <div
                                            class="w-12 h-6 bg-slate-200 rounded-full peer peer-checked:bg-blue-500 peer-checked:after:translate-x-6 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all">
                                        </div>
                                    </div>
                                    <span class="text-xs font-black uppercase tracking-widest text-slate-400">NO</span>
                                </label>
                            </div>
                            <p class="text-[11px] text-slate-400 mt-2">Bật tùy chọn này khi sử dụng đánh giá sao trong bài
                                viết.</p>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label font-bold text-slate-700">Duyệt đánh giá</label>
                            <div class="mt-2">
                                <input type="hidden" name="settings[review_auto_approve]" value="0">
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <div class="relative">
                                        <input type="checkbox" name="settings[review_auto_approve]" value="1" {{ ($map['review_auto_approve'] ?? '1') == '1' ? 'checked' : '' }}
                                            class="sr-only peer">
                                        <div
                                            class="w-12 h-6 bg-slate-200 rounded-full peer peer-checked:bg-blue-500 peer-checked:after:translate-x-6 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all">
                                        </div>
                                    </div>
                                    <span class="text-xs font-black uppercase tracking-widest text-slate-400">YES</span>
                                </label>
                            </div>
                            <p class="text-[11px] text-slate-400 mt-2">Cấu hình cho phép chủ cửa hàng duyệt các đánh giá sản
                                phẩm trước khi cho hiển thị.</p>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100">
                        <label class="form-label font-bold text-slate-700">Màu biểu tượng sao</label>
                        <div class="flex gap-2 items-center">
                            <input type="color" name="settings[review_star_color]"
                                value="{{ $map['review_star_color'] ?? '#ffc107' }}"
                                class="w-16 h-10 border-slate-200 rounded-lg p-1">
                            <span
                                class="text-sm text-slate-500 font-mono">{{ $map['review_star_color'] ?? '#ffc107' }}</span>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100">
                        <label class="form-label font-bold text-slate-700">Từ khóa không cho phép</label>
                        <textarea name="settings[review_forbidden_keywords]" rows="4"
                            class="form-control border-slate-200 rounded-xl resize-none p-4 text-sm"
                            placeholder="tệ, kém, ghét...">{{ $map['review_forbidden_keywords'] ?? '' }}</textarea>
                        <p class="text-[11px] text-slate-400 mt-2 italic">Mỗi từ khóa cách nhau bằng dấu ","</p>
                    </div>
                </div>
            </div>

            {{-- Section 2: Giao diện & Vị trí --}}
            <div class="card shadow-sm border-slate-200 overflow-hidden">
                <div class="row g-0">
                    <div
                        class="col-md-5 bg-slate-50 p-6 flex flex-col items-center justify-center border-e border-slate-100">
                        {{-- Mockup Preview as in Screenshot --}}
                        <div
                            class="w-full max-w-[280px] bg-white rounded-xl shadow-lg border border-slate-100 p-4 space-y-3">
                            <div class="h-32 bg-slate-100 rounded-lg"></div>
                            <div class="h-3 bg-slate-100 rounded w-full"></div>
                            <div class="h-3 bg-slate-100 rounded w-2/3"></div>
                            <div class="flex gap-1 pt-2">
                                @for($i = 0; $i < 5; $i++) <i class="fa-solid fa-star text-slate-300 text-xs text-blue-900"></i>
                                @endfor
                            </div>
                        </div>
                        <p class="mt-4 text-[11px] font-black uppercase tracking-widest text-slate-400">Xem trước vị trí</p>
                    </div>
                    <div class="col-md-7 p-6 space-y-6">
                        <div>
                            <label class="form-label font-bold text-slate-700 mb-3 block">Vị trí hiển thị</label>
                            <div class="space-y-2">
                                @foreach(['left' => 'Canh trái', 'center' => 'Canh giữa', 'right' => 'Canh phải'] as $val => $label)
                                    <label class="flex items-center gap-2 cursor-pointer group">
                                        <input type="radio" name="settings[review_display_position]" value="{{ $val }}" {{ ($map['review_display_position'] ?? 'center') == $val ? 'checked' : '' }}
                                            class="w-4 h-4 border-slate-300 text-blue-600 focus:ring-blue-500">
                                        <span
                                            class="text-sm font-medium text-slate-600 group-hover:text-blue-600 transition-colors">{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="pt-4 border-t border-slate-100">
                            <label class="form-label font-bold text-slate-700">Số thứ tự hiển thị</label>
                            <input type="number" name="settings[review_sort_order]"
                                value="{{ $map['review_sort_order'] ?? '45' }}"
                                class="form-control border-slate-200 rounded-lg p-2.5 text-sm">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Section 3: Trả lời review & Template --}}
            <div class="card shadow-sm border-slate-200">
                <div class="card-body p-6">
                    {{-- Template Mockups --}}
                    <div class="row g-3 mb-6">
                        <div class="col-md-4">
                            <img src="https://images.dmca.com/Badges/dmca-badge-w100-5x1-06.png?ID=ef8e9f5e-114c-473d-9d41-4c6e9f5e114c"
                                hidden> {{-- Placeholder intended for the screenshot mocks --}}
                            <div class="border rounded-xl p-2 opacity-50"><img src="https://i.imgur.com/E8YmI6K.png"
                                    class="img-fluid rounded"></div>
                        </div>
                        <div class="col-md-4">
                            <div class="border border-rose-200 rounded-xl p-2 bg-rose-50/10"><img
                                    src="https://i.imgur.com/vHqY7pG.png" class="img-fluid rounded"></div>
                        </div>
                        <div class="col-md-4">
                            <div class="border rounded-xl p-2 opacity-50"><img src="https://i.imgur.com/E8YmI6K.png"
                                    class="img-fluid rounded opacity-50"></div>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-slate-100">
                        <label class="form-label font-bold text-slate-700 mb-3 block">Trả lời review</label>
                        <div class="space-y-2">
                            @foreach(['all' => 'Cho phép tất cả mọi người trả lời', 'member' => 'Chỉ cho phép thành viên', 'admin' => 'Chỉ cho phép admin trả lời'] as $val => $label)
                                <label class="flex items-center gap-2 cursor-pointer group">
                                    <input type="radio" name="settings[review_allow_reply]" value="{{ $val }}" {{ ($map['review_allow_reply'] ?? 'all') == $val ? 'checked' : '' }}
                                        class="w-4 h-4 border-slate-300 text-blue-600 focus:ring-blue-500">
                                    <span
                                        class="text-sm font-medium text-slate-600 group-hover:text-blue-600 transition-colors">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- Section 4: Tự động đánh giá --}}
            <div class="card shadow-sm border-slate-200 bg-slate-50/30">
                <div class="card-body p-6 space-y-6">
                    <div class="row g-4">
                        <div class="col-md-4">
                            <label class="form-label font-bold text-slate-700">Bật / tắt tự động tạo đánh giá</label>
                            <div class="mt-2">
                                <input type="hidden" name="settings[review_auto_gen_enabled]" value="0">
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <div class="relative">
                                        <input type="checkbox" name="settings[review_auto_gen_enabled]" value="1" {{ ($map['review_auto_gen_enabled'] ?? '1') == '1' ? 'checked' : '' }}
                                            class="sr-only peer">
                                        <div
                                            class="w-12 h-6 bg-slate-200 rounded-full peer peer-checked:bg-blue-500 peer-checked:after:translate-x-6 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all">
                                        </div>
                                    </div>
                                    <span class="text-xs font-black uppercase tracking-widest text-slate-400">YES</span>
                                </label>
                            </div>
                            <p class="text-[11px] text-slate-400 mt-2">Tự động tạo đánh giá khi tạo mới sản phẩm.</p>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label font-bold text-slate-700">Số đánh giá nhỏ nhất tạo ra</label>
                            <input type="number" name="settings[review_auto_gen_min]"
                                value="{{ $map['review_auto_gen_min'] ?? '0' }}"
                                class="form-control border-slate-200 rounded-lg p-2.5 text-sm bg-white">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label font-bold text-slate-700">Số đánh giá lớn nhất tạo ra</label>
                            <input type="number" name="settings[review_auto_gen_max]"
                                value="{{ $map['review_auto_gen_max'] ?? '10' }}"
                                class="form-control border-slate-200 rounded-lg p-2.5 text-sm bg-white">
                        </div>
                    </div>

                    <div class="row g-4 pt-4">
                        <div class="col-md-4">
                            <label class="form-label font-bold text-slate-700">Tỉ lệ ra 5 sao</label>
                            <input type="number" name="settings[review_auto_gen_ratio_5]"
                                value="{{ $map['review_auto_gen_ratio_5'] ?? '90' }}"
                                class="form-control border-slate-200 rounded-lg p-2.5 text-sm bg-white">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label font-bold text-slate-700">Tỉ lệ ra 4 sao</label>
                            <input type="number" name="settings[review_auto_gen_ratio_4]"
                                value="{{ $map['review_auto_gen_ratio_4'] ?? '30' }}"
                                class="form-control border-slate-200 rounded-lg p-2.5 text-sm bg-white">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label font-bold text-slate-700">Tỉ lệ ra 3 sao</label>
                            <input type="number" name="settings[review_auto_gen_ratio_3]"
                                value="{{ $map['review_auto_gen_ratio_3'] ?? '0' }}"
                                class="form-control border-slate-200 rounded-lg p-2.5 text-sm bg-white">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Section 5: Dữ liệu mẫu --}}
            <div class="card shadow-sm border-slate-200 mb-10">
                <div class="card-body p-6">
                    <label class="form-label font-bold text-slate-800 text-lg mb-4 block">Loại dữ liệu mẫu</label>
                    <div class="space-y-3">
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="radio" name="settings[review_sample_type]" value="sikido" checked
                                class="w-5 h-5 border-slate-300 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm font-bold text-slate-700">Dữ liệu có sẵn</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="radio" name="settings[review_sample_type]" value="manual"
                                class="w-5 h-5 border-slate-300 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm font-bold text-slate-700">Tự tạo</span>
                        </label>
                    </div>
                </div>
            </div>

        </div>
    </form>

@endsection

@push('styles')
    <style>
        .card {
            border-radius: 1rem;
        }

        .form-label {
            font-size: 13px;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #3b82f6;
        }
    </style>
@endpush