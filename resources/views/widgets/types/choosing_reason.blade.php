<div class="rts-service-area rts-section-gap2 bg_light-1">
    <div class="container-3">
        <div class="row">
            <div class="col-lg-12">
                <div class="title-center-area-main">
                    <h2 class="title">{{ $config['title'] ?? '' }}</h2>
                    <p class="disc">{{ $config['subtitle'] ?? '' }}</p>
                </div>
            </div>
        </div>
        <div class="row mt--30 g-5">
            @foreach($config['items'] ?? [] as $index => $item)
            <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                <div class="single-service-area-style-one">
                    <div class="icon-area">
                        <span class="bg-text">0{{ $index + 1 }}</span>
                        <img src="{{ asset($item['icon'] ?: 'theme/images/service/01.svg') }}" alt="service">
                    </div>
                    <div class="bottom-content">
                        <h3 class="title">{{ $item['title'] ?? '' }}</h3>
                        <p class="disc">{{ $item['desc'] ?? '' }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
