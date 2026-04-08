<!-- rts footer one area start -->
<div class="rts-footer-area pt--80 bg_light-1">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="footer-main-content-wrapper pb--70 pb_sm--30">
                    @widgetArea('footer')
                </div>
                <div class="social-and-payment-area-wrapper">
                    <div class="social-one-wrapper">
                        <span>Follow Us:</span>
                        <ul>
                            @if(setting('social_links.facebook'))
                            <li><a href="{{ setting('social_links.facebook') }}" target="_blank" rel="noopener"><x-theme-icon name="facebook" /></a></li>
                            @endif
                            @if(setting('social_links.twitter'))
                            <li><a href="{{ setting('social_links.twitter') }}" target="_blank" rel="noopener"><x-theme-icon name="twitter" /></a></li>
                            @endif
                            @if(setting('social_links.youtube'))
                            <li><a href="{{ setting('social_links.youtube') }}" target="_blank" rel="noopener"><x-theme-icon name="youtube" /></a></li>
                            @endif
                            @if(setting('social_links.instagram'))
                            <li><a href="{{ setting('social_links.instagram') }}" target="_blank" rel="noopener"><x-theme-icon name="instagram" /></a></li>
                            @endif
                            @if(setting('social_links.tiktok'))
                            <li><a href="{{ setting('social_links.tiktok') }}" target="_blank" rel="noopener"><x-theme-icon name="tiktok" /></a></li>
                            @endif
                            @if(setting('social_links.zalo'))
                            <li><a href="{{ setting('social_links.zalo') }}" target="_blank" rel="noopener"><x-theme-icon name="zalo" /></a></li>
                            @endif
                            @if(setting('social_links.pinterest'))
                            <li><a href="{{ setting('social_links.pinterest') }}" target="_blank" rel="noopener"><x-theme-icon name="pinterest" /></a></li>
                            @endif
                        </ul>
                    </div>
                    <div class="payment-access">
                        <span>Payment Accepts:</span>
                        <x-theme-image name="payment_methods" alt="payment-methods" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- rts footer one area end -->

<!-- rts copyright-area start -->
<div class="rts-copyright-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="copyright-between-1">
                    <p class="disc">{!! setting('site_copyright', 'Copyright 2024 <a href="#">©Ekomart</a>. All rights reserved.') !!}</p>
                    <a href="#" class="playstore-app-area">
                        <span>Download App</span>
                        <x-theme-image name="app_store" alt="Apple Store" />
                        <x-theme-image name="google_play" alt="Google Play" />
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- rts copyright-area end -->

<!-- Quick View Modal Container -->
<div id="quick-view-modal-container"></div>
<!-- Theme Overlay -->
<div id="anywhere-home"></div>

{{-- ── Floating Contact Buttons ─────────────────────────────────────── --}}
@if(setting('btn_zalo_enabled') || setting('btn_messenger_enabled') || setting('btn_phone_enabled'))
<div class="vtm-float-buttons" style="position:fixed;bottom:80px;right:20px;z-index:9999;display:flex;flex-direction:column;gap:10px;align-items:flex-end;">
    @if(setting('btn_phone_enabled') && setting('btn_phone_number'))
    <a href="tel:{{ setting('btn_phone_number') }}" class="vtm-float-btn vtm-float-phone"
       title="Gọi điện: {{ setting('btn_phone_number') }}"
       style="width:48px;height:48px;border-radius:50%;background:#629D23;color:#fff;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 12px rgba(0,0,0,.2);text-decoration:none;animation:vtm-pulse 2s infinite;">
        <i class="fa-solid fa-phone" style="font-size:18px;"></i>
    </a>
    @endif

    @if(setting('btn_zalo_enabled') && setting('btn_zalo_number'))
    <a href="https://zalo.me/{{ setting('btn_zalo_number') }}" target="_blank" rel="noopener"
       class="vtm-float-btn vtm-float-zalo"
       title="Chat Zalo"
       style="width:48px;height:48px;border-radius:50%;background:#0068FF;color:#fff;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 12px rgba(0,0,0,.2);text-decoration:none;">
        <img src="{{ asset('theme/images/icons/zalo-icon.png') }}" alt="Zalo"
             onerror="this.style.display='none';this.nextElementSibling.style.display='block';"
             style="width:28px;height:28px;object-fit:contain;">
        <span style="display:none;font-size:11px;font-weight:700;">Zalo</span>
    </a>
    @endif

    @if(setting('btn_messenger_enabled') && setting('btn_messenger_page_id'))
    <a href="https://m.me/{{ setting('btn_messenger_page_id') }}" target="_blank" rel="noopener"
       class="vtm-float-btn vtm-float-messenger"
       title="Chat Messenger"
       style="width:48px;height:48px;border-radius:50%;background:linear-gradient(135deg,#0099FF,#A033FF);color:#fff;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 12px rgba(0,0,0,.2);text-decoration:none;">
        <i class="fa-brands fa-facebook-messenger" style="font-size:22px;"></i>
    </a>
    @endif
</div>
<style>
@keyframes vtm-pulse {
    0%,100%{box-shadow:0 4px 12px rgba(98,157,35,.4);}
    50%{box-shadow:0 4px 20px rgba(98,157,35,.8);}
}
</style>
@endif

{{-- ── Ghost Notification (Fake Order Popup) ───────────────────────── --}}
@if(setting('ghost_notif_enabled'))
@php
    $gnNames     = array_filter(array_map('trim', explode("\n", setting('ghost_notif_names', ''))));
    $gnLocations = array_filter(array_map('trim', explode("\n", setting('ghost_notif_locations', ''))));
    $gnInterval  = (int) setting('ghost_notif_interval', 30);
    $gnDuration  = (int) setting('ghost_notif_duration', 5);
@endphp
@if(!empty($gnNames) && !empty($gnLocations))
<div id="ghost-notif" style="display:none;position:fixed;bottom:20px;left:20px;z-index:9998;background:#fff;border-radius:8px;box-shadow:0 4px 20px rgba(0,0,0,.15);padding:12px 16px;max-width:280px;border-left:4px solid var(--color-primary);animation:vtm-slideIn .4s ease;">
    <div style="display:flex;align-items:center;gap:10px;">
        <div style="width:36px;height:36px;border-radius:50%;background:var(--color-primary-light);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <i class="fa-solid fa-bag-shopping" style="color:var(--color-primary);font-size:14px;"></i>
        </div>
        <div>
            <p id="ghost-notif-text" style="margin:0;font-size:13px;font-weight:600;color:#333;line-height:1.4;"></p>
            <span style="font-size:11px;color:#999;">vừa đặt hàng thành công</span>
        </div>
        <button onclick="document.getElementById('ghost-notif').style.display='none'" style="border:none;background:none;color:#999;cursor:pointer;padding:0;margin-left:auto;font-size:16px;line-height:1;">&times;</button>
    </div>
</div>
<style>
@keyframes vtm-slideIn{from{transform:translateX(-110%);opacity:0;}to{transform:translateX(0);opacity:1;}}
</style>
<script>
(function(){
    const names     = @json(array_values($gnNames));
    const locations = @json(array_values($gnLocations));
    const interval  = {{ $gnInterval }} * 1000;
    const duration  = {{ $gnDuration }} * 1000;
    const el        = document.getElementById('ghost-notif');
    const textEl    = document.getElementById('ghost-notif-text');

    function showNotif() {
        const name = names[Math.floor(Math.random() * names.length)];
        const loc  = locations[Math.floor(Math.random() * locations.length)];
        textEl.textContent = name + ' (' + loc + ')';
        el.style.display = 'block';
        el.style.animation = 'none';
        void el.offsetWidth;
        el.style.animation = 'vtm-slideIn .4s ease';
        setTimeout(() => { el.style.display = 'none'; }, duration);
    }

    // First show after 5s, then repeat
    setTimeout(function loop() {
        showNotif();
        setTimeout(loop, interval);
    }, 5000);
})();
</script>
@endif
@endif