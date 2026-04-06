<div class="rts-counter-area">
    <div class="container-3">
        <div class="row">
            <div class="col-lg-12">
                <div class="counter-area-main-wrapper">
                    @foreach($config['items'] ?? [] as $item)
                    <div class="single-counter-area">
                        <h2 class="title"><span class="counter">{{ $item['number'] ?? '0' }}</span>{{ $item['suffix'] ?? '' }}</h2>
                        <p>{!! nl2br(e($item['label'] ?? '')) !!}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
