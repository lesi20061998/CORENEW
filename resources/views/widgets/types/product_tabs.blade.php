@php
    $title    = $config['title']    ?? 'Bán chạy trong tuần';
    $bgLight  = $config['bg_light'] ?? true;
    $tabs     = $config['tabs']     ?? [];
@endphp

@if(count($tabs))
<div class="weekly-best-selling-area rts-section-gap {{ $bgLight ? 'bg_light-1' : '' }}">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="title-area-between">
                    <h2 class="title-left">{{ $title }}</h2>
                    <ul class="nav nav-tabs best-selling-grocery" id="tabs-{{ $widget->id }}" role="tablist">
                        @foreach($tabs as $i => $tab)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ $i === 0 ? 'active' : '' }}"
                                data-bs-toggle="tab"
                                data-bs-target="#tab-{{ $widget->id }}-{{ $i }}"
                                type="button" role="tab">{{ $tab['label'] ?? '' }}</button>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="tab-content" id="tabs-content-{{ $widget->id }}">
                    @foreach($tabs as $i => $tab)
                    <div class="tab-pane fade {{ $i === 0 ? 'show active' : '' }}"
                        id="tab-{{ $widget->id }}-{{ $i }}"
                        role="tabpanel">
                        <div class="row g-4">
                            @forelse($tabProducts[$i] ?? [] as $product)
                            <div class="col-xxl-2 col-xl-3 col-lg-4 col-md-4 col-sm-6 col-12">
                                @include('widgets.partials.product-card', ['product' => $product])
                            </div>
                            @empty
                            <div class="col-12"><p>Chưa có sản phẩm</p></div>
                            @endforelse
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endif
