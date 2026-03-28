@php
    $content = $config['content'] ?? '';
    $css     = $config['css']     ?? '';
@endphp

@if($content)
@if($css)
<style>{{ $css }}</style>
@endif
{!! $content !!}
@endif
