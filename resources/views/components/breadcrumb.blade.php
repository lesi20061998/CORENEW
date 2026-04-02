@props(['items' => []])

<div class="rts-navigation-area-breadcrumb bg_light-1">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="navigator-breadcrumb-wrapper">
                    <a href="{{ route('home') }}">Trang chủ</a>
                    @foreach($items as $item)
                        @if(isset($item['label']))
                            <i class="fa-regular fa-chevron-right"></i>
                            @if($loop->last)
                                <a class="current" href="javascript:void(0);">{{ $item['label'] }}</a>
                            @else
                                <a href="{{ $item['url'] ?? 'javascript:void(0);' }}">{{ $item['label'] }}</a>
                            @endif
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
