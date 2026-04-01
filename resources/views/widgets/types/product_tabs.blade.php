<!-- Weekly Best Selling Groceries -->
<div class="weekly-best-selling-area rts-section-gap {{ ($config['bg_light'] ?? true) ? 'bg_light-1' : '' }}" {!! $sectionStyles !!}>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="title-area-between">
                    <h2 class="title-left">{{ $config['title'] ?? 'Weekly Best Selling' }}</h2>
                    @if(!empty($config['tabs']))
                    <ul class="nav nav-tabs best-selling-grocery" id="tab-{{ $widget->id }}" role="tablist">
                        @foreach($config['tabs'] as $i => $tab)
                        <li class="nav-item">
                            <button class="nav-link {{ $i == 0 ? 'active' : '' }}" 
                                    data-bs-toggle="tab" 
                                    data-bs-target="#tab-content-{{ $widget->id }}-{{ $i }}">{{ $tab['label'] }}</button>
                        </li>
                        @endforeach
                    </ul>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="tab-content mt--30" id="tab-content-{{ $widget->id }}">
            @foreach($tabProducts as $i => $products)
            <div class="tab-pane fade {{ $i == 0 ? 'show active' : '' }}" id="tab-content-{{ $widget->id }}-{{ $i }}">
                <div class="row g-4">
                    @php 
                        $cols = $config['columns'] ?? 5;
                        $colClass = match((string)$cols) {
                            '2' => 'col-lg-6',
                            '3' => 'col-lg-4',
                            '4' => 'col-xl-3 col-lg-4 col-md-6',
                            '5' => 'col-xxl-20 col-xl-3 col-lg-4 col-md-6',
                            default => 'col-xxl-2 col-xl-3 col-lg-4 col-md-6'
                        };
                    @endphp
                    @foreach($products as $product)
                    <div class="{{ $colClass }} col-sm-6 col-12">
                        <x-product-card :product="$product" />
                    </div>
                    @endforeach
                 </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
