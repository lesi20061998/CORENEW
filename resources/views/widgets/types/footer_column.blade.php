@php
    $type = $config['type'] ?? 'menu';
    $title = $config['title'] ?? 'Footer Title';
@endphp

<div class="{{ $config['col_class'] ?? 'single-footer-wized' }}">
    <h3 class="{{ $config['title_class'] ?? 'footer-title' }}">{{ $title }}</h3>
    
    @if($type === 'contact')
        <div class="call-area">
            <div class="{{ $config['icon_class'] ?? 'icon' }}"><i class="fa-solid fa-phone-rotary"></i></div>
            <div class="{{ $config['info_class'] ?? 'info' }}">
                <span>{{ $config['phone_label'] ?? 'Hotline hỗ trợ 24/7' }}</span>
                <a href="tel:{{ $config['phone'] ?? '+258 3692 2569' }}" class="number">{{ $config['phone'] ?? '+258 3692 2569' }}</a>
            </div>
        </div>
        @if(!empty($config['hours']))
            <div class="opening-hour">
                @foreach(explode("\n", $config['hours']) as $line)
                    @php 
                        $parts = explode(':', $line, 2); 
                        $day = $parts[0] ?? '';
                        $time = $parts[1] ?? '';
                    @endphp
                    <div class="single">
                        <p>{{ $day }}: <span>{{ trim($time) }}</span></p>
                    </div>
                @endforeach
            </div>
        @endif

    @elseif($type === 'menu')
        <div class="{{ $config['nav_class'] ?? 'footer-nav' }}">
            <ul>
                @foreach(\App\Models\Widget::getMenu($config['menu_slug'] ?? 'footer-info') as $item)
                    <li><a href="{{ $item['url'] }}">{{ $item['label'] }}</a></li>
                @endforeach
                @if(!empty($config['show_sitemap']))
                    <li><a href="/sitemap">Sitemap</a></li>
                @endif
            </ul>
        </div>

    @elseif($type === 'newsletter')
        <p class="disc-news-letter">{{ $config['newsletter_desc'] ?? 'Đăng ký nhận thông báo về sản phẩm mới và ưu đãi đặc biệt.' }}</p>
        <form class="footersubscribe-form" action="{{ route('newsletter.subscribe') }}" method="POST">
            @csrf
            <input name="email" type="email" placeholder="{{ $config['placeholder'] ?? 'Địa chỉ email của bạn' }}" required>
            <button class="{{ $config['btn_class'] ?? 'rts-btn btn-primary' }}" type="submit">Đăng ký</button>
        </form>
        @if(!empty($config['newsletter_note']))
            <p class="dsic">{{ $config['newsletter_note'] }}</p>
        @endif

    @elseif($type === 'html')
        {!! $config['html_content'] ?? '' !!}
    @endif
</div>
