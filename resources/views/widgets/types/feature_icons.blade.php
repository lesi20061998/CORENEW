<!-- rts featrure area start -->
<div class="rts-feature-area" {!! $sectionStyles ?? '' !!}>
    <div class="container">
        <div class="row g-4">
            @foreach($features as $f)
            <div class="{{ $config['grid_class'] ?? 'col-xl-20 col-lg-6 col-md-6 col-sm-6 col-12' }}">
                <div class="single-feature-area">
                    <div class="icon">
                        <i class="fa-light {{ $f['icon'] ?? 'fa-box-open' }}" style="font-size: 30px; color: #629D23;"></i>
                    </div>
                    <div class="content">
                        <h4 class="title">{{ $f['title'] ?? '' }}</h4>
                        <span>{{ $f['sub'] ?? '' }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
<!-- rts feature area end -->
