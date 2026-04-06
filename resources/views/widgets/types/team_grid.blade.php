<div class="meet-our-expart-team rts-section-gap2">
    <div class="container-3">
        <div class="row">
            <div class="col-lg-12">
                <div class="title-center-area-main">
                    <h2 class="title">{{ $config['title'] ?? 'Đội Ngũ Chuyên Gia' }}</h2>
                    <p class="disc">{{ $config['subtitle'] ?? '' }}</p>
                </div>
            </div>
        </div>
        <div class="row g-5 mt--40">
            @foreach($config['items'] ?? [] as $member)
            <div class="col-lg-3 col-md-6 col-sm-12 col-12">
                <div class="single-team-style-one">
                    <a href="{{ $member['link'] ?? '#' }}" class="thumbnail">
                        <img src="{{ asset($member['image'] ?: 'theme/images/team/01.jpg') }}" alt="team">
                    </a>
                    <div class="bottom-content-area">
                        <div class="top">
                            <h3 class="title">{{ $member['name'] ?? '' }}</h3>
                            <span class="designation">{{ $member['role'] ?? '' }}</span>
                        </div>
                        @if(!empty($member['phone']))
                        <div class="bottom">
                            <a href="tel:{{ $member['phone'] }}" class="number">
                                <i class="fa-solid fa-phone-rotary"></i>
                                {{ $member['phone'] }}
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
