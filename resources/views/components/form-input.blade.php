@props([
    'label' => '',
    'name' => '',
    'type' => 'text',
    'id' => null,
    'value' => '',
    'placeholder' => '',
    'required' => false,
    'containerClass' => 'single-input',
    'inputClass' => '',
])

@php
    $id = $id ?? $name;
@endphp

<div class="{{ $containerClass }}">
    @if($label)
        <label for="{{ $id }}" class="form-label font-bold text-slate-700 mb-2 block">
            {{ $label }} @if($required) <span class="text-red-500">*</span> @endif
        </label>
    @endif
    
    <input 
        id="{{ $id }}" 
        name="{{ $name }}" 
        type="{{ $type }}" 
        value="{{ $value }}" 
        placeholder="{{ $placeholder }}"
        {{ $attributes->merge(['class' => 'form-control ' . $inputClass]) }}
    >
    
    {{-- Chỗ để JS hiển thị thông báo lỗi --}}
    <div id="error-{{ $id }}" class="error-msg text-danger mt-1 font-bold italic" style="font-size: 11px; height: 14px;"></div>
</div>
