@php $items = $config['items'] ?? []; @endphp

@if(count($items))
<div class="rts-feature-area rts-section-gap">
    <div class="container">
        <div class="row g-4">
            @foreach($items as $item)
            <div class="col-xl-20 col-lg-6 col-md-6 col-sm-6 col-12">
                <div class="single-feature-area">
                    <div class="icon">
                        <i class="{{ $item['icon'] ?? 'fa-solid fa-truck' }}" style="font-size: 2.5rem; color: #629D23;"></i>
                    </div>
                    <div class="content">
                        <h4 class="title">{{ $item['title'] ?? '' }}</h4>
                        <span>{{ $item['subtitle'] ?? '' }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif
