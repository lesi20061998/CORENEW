@php
    $resolved = resolve_icon($name, $default);
    // Nếu là class icon (vd: fa-...)
    $isFa = \Illuminate\Support\Str::contains($resolved, 'fa-') && !\Illuminate\Support\Str::contains($resolved, '/');
@endphp

@if($isFa)
    <i class="{{ $resolved }} {{ $class }}" {{ $attributes }}></i>
@else
    {{-- Nếu là ảnh (SVG/PNG) thì render thẻ img --}}
    <img src="{{ asset($resolved) }}" class="{{ $class }}" alt="{{ $name }}" {{ $attributes }}>
@endif
