<div class="rts-about-area rts-section-gap2">
    <div class="container-3">
        <div class="row align-items-center {{ ($config['img_right'] ?? false) ? 'flex-row-reverse' : '' }}">
            <div class="col-lg-4">
                <div class="thumbnail-left">
                    <img src="{{ asset($config['image'] ?: 'theme/images/about/02.jpg') }}" alt="about">
                </div>
            </div>
            <div class="col-lg-8 pt_md--30 pt_sm--30 {{ ($config['img_right'] ?? false) ? 'pr--60 pr_md--10 pr_sm--10' : 'pl--60 pl_md--10 pl_sm--10' }}">
                <div class="about-content-area-1">
                    <h2 class="title">{{ $config['title'] ?? '' }}</h2>
                    <p class="disc">
                        {!! $config['content'] ?? '' !!}
                    </p>
                    <div class="check-main-wrapper">
                        @foreach($config['checks'] ?? [] as $check)
                            <div class="single-check-area">{{ $check['text'] ?? '' }}</div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
