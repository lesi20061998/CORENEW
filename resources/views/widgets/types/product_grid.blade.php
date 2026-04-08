{{-- Product Grid Widget — maps to ProductGridWidget --}}
@php
    $cols = $config['columns'] ?? 5;
    $colClass = match((string)$cols) {
        '2' => 'col-lg-6',
        '3' => 'col-lg-4',
        '4' => 'col-xl-3 col-lg-4 col-md-6',
        '5' => 'col-xxl-20 col-xl-3 col-lg-4 col-md-6',
        default => 'col-xxl-2 col-xl-3 col-lg-4 col-md-6'
    };
    $wrapClass = $config['wrap_class'] ?? 'rts-grocery-feature-area rts-section-gapBottom';
@endphp

<div class="{{ $wrapClass }}" {!! $sectionStyles ?? '' !!}>
    <div class="container">
        @if(!empty($config['title']))
        <div class="row">
            <div class="col-lg-12">
                <div class="title-area-between">
                    <h2 class="title-left">{{ $config['title'] }}</h2>
                    @if(!empty($config['show_btn']) && !empty($config['btn_text']))
                    <a href="{{ $config['btn_link'] ?? route('shop.index') }}" class="rts-btn btn-primary">{{ $config['btn_text'] }}</a>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <div class="row g-4 mt--10">
            @foreach($products as $product)
            <div class="{{ $colClass }} col-sm-6 col-12">
                <x-product-card :product="$product" />
            </div>
            @endforeach
        </div>
    </div>
</div>
