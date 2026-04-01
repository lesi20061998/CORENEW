@php
    $p = $prefix ? $prefix . '[' : '';
    $s = $prefix ? ']' : '';
    $td = $transData ?? [];
@endphp

<div class="space-y-4">
    <div>
        <label class="form-label">
            Tên sản phẩm <span class="text-red-500">*</span>
            @if($locale) <span class="text-xs text-gray-400 font-normal">({{ strtoupper($locale) }})</span> @endif
        </label>
        <input type="text"
               name="{{ $p }}name{{ $s }}"
               value="{{ old($p . 'name' . $s, $locale ? ($td['name'] ?? '') : ($product?->name ?? '')) }}"
               {{ !$locale ? 'required' : '' }}
               placeholder="Nhập tên sản phẩm..."
               class="form-input">
    </div>

    <div>
        <label class="form-label">
            Mô tả ngắn
            @if($locale) <span class="text-xs text-gray-400 font-normal">({{ strtoupper($locale) }})</span> @endif
        </label>
        @include('components.admin.editor', [
            'name'   => ($p . 'short_description' . $s),
            'value'  => old($p . 'short_description' . $s, $locale ? ($td['short_description'] ?? '') : ($product?->short_description ?? '')),
            'height' => 180,
        ])
    </div>

    <div>
        <label class="form-label">
            Mô tả chi tiết
            @if($locale) <span class="text-xs text-gray-400 font-normal">({{ strtoupper($locale) }})</span> @endif
        </label>
        @include('components.admin.editor', [
            'name'   => ($p . 'description' . $s),
            'value'  => old($p . 'description' . $s, $locale ? ($td['description'] ?? '') : ($product?->description ?? '')),
            'height' => 360,
        ])
    </div>
</div>
