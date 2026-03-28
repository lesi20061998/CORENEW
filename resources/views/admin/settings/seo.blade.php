@extends('admin.layouts.app')
@section('title', 'SEO — Cài đặt')
@section('page-title', 'SEO')
@section('page-subtitle', 'Quản lý thông tin hỗ trợ SEO website')

@section('page-actions')
<a href="{{ route('admin.settings.index') }}" class="btn btn-secondary">
    <i class="fa-solid fa-arrow-left text-xs"></i> Quay lại
</a>
@endsection

@section('content')
@php
    $map = $settings->pluck('value', 'key');
@endphp

<div class="flex gap-6 items-start">

{{-- Sidebar nav --}}
<div class="w-48 flex-shrink-0 sticky top-6">
    <nav class="space-y-1">
        <a href="#sec-general" class="seo-nav-link flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm text-gray-600 hover:bg-gray-100 transition-colors">
            <i class="fa-solid fa-gear w-4 text-center text-gray-400 text-xs"></i> Cấu hình chung
        </a>
        <a href="#sec-opengraph" class="seo-nav-link flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm text-gray-600 hover:bg-gray-100 transition-colors">
            <i class="fa-brands fa-facebook w-4 text-center text-gray-400 text-xs"></i> Open Graph
        </a>
        <a href="#sec-twitter" class="seo-nav-link flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm text-gray-600 hover:bg-gray-100 transition-colors">
            <i class="fa-brands fa-x-twitter w-4 text-center text-gray-400 text-xs"></i> Twitter / X
        </a>
        <a href="#sec-script" class="seo-nav-link flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm text-gray-600 hover:bg-gray-100 transition-colors">
            <i class="fa-solid fa-code w-4 text-center text-gray-400 text-xs"></i> Script
        </a>
        <a href="#sec-robots" class="seo-nav-link flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm text-gray-600 hover:bg-gray-100 transition-colors">
            <i class="fa-solid fa-robot w-4 text-center text-gray-400 text-xs"></i> File Robots
        </a>
        <a href="#sec-redirect" class="seo-nav-link flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm text-gray-600 hover:bg-gray-100 transition-colors">
            <i class="fa-solid fa-diamond-turn-right w-4 text-center text-gray-400 text-xs"></i> Chuyển hướng 404
        </a>
    </nav>
</div>

{{-- Main form --}}
<form action="{{ route('admin.settings.group.update', 'seo') }}" method="POST" class="flex-1 min-w-0">
@csrf @method('PUT')
<div class="space-y-5 max-w-3xl">

{{-- Cấu hình chung --}}
<div class="card scroll-mt-4" id="sec-general">
    <div class="card-header flex items-center gap-3">
        <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-gear text-blue-500 text-sm"></i>
        </div>
        <div>
            <p class="text-sm font-semibold text-gray-800">Cấu hình chung</p>
            <p class="text-xs text-gray-400">Quản lý thông tin hỗ trợ seo cơ bản của website</p>
        </div>
    </div>
    <div class="card-body space-y-5">

        {{-- Favicon --}}
        <div>
            <label class="form-label">Favicon</label>
            <div class="flex gap-2 items-center">
                <input type="text" name="settings[seo_favicon]" id="seo_favicon_input"
                       value="{{ $map['seo_favicon'] ?? '' }}"
                       placeholder="logo/logo-sulynuoc-tiehnm.png"
                       class="form-input"
                       oninput="updateImgPreview('seo_favicon_preview', this.value)">
                <button type="button"
                        onclick="openMediaPicker('seo_favicon_input', function(url){ updateImgPreview('seo_favicon_preview', url); })"
                        class="flex-shrink-0 px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100 transition-colors flex items-center gap-1.5">
                    <i class="fa-solid fa-images text-blue-500 text-xs"></i> Đổi ảnh
                </button>
            </div>
            <div id="seo_favicon_preview" class="mt-2">
                @if(!empty($map['seo_favicon']))
                    <img src="{{ $map['seo_favicon'] }}" class="h-16 rounded-lg border border-gray-200 object-contain">
                @endif
            </div>
        </div>

        {{-- Meta title --}}
        <div>
            <label class="form-label">Meta title (Shop) <span class="text-red-400">(*)</span></label>
            <input type="text" name="settings[seo_meta_title]"
                   value="{{ $map['seo_meta_title'] ?? '' }}"
                   class="form-input"
                   placeholder="Tiêu đề SEO trang chủ...">
        </div>

        {{-- Meta description --}}
        <div>
            <label class="form-label">Meta description (Mô tả trang chủ) <span class="text-red-400">(*)</span></label>
            <textarea name="settings[seo_meta_desc]" rows="4"
                      class="form-input resize-none"
                      placeholder="Mô tả ngắn về website...">{{ $map['seo_meta_desc'] ?? '' }}</textarea>
        </div>

        {{-- Meta keywords --}}
        <div>
            <label class="form-label">Meta keyword (Từ khóa trang chủ) <span class="text-red-400">(*)</span></label>
            <textarea name="settings[seo_meta_keywords]" rows="4"
                      class="form-input resize-none"
                      placeholder="từ khóa 1, từ khóa 2, từ khóa 3...">{{ $map['seo_meta_keywords'] ?? '' }}</textarea>
        </div>

        {{-- Robots --}}
        <div>
            <label class="form-label">Meta Robots</label>
            <select name="settings[seo_meta_robots]" class="form-select">
                @foreach(['index, follow','noindex, follow','index, nofollow','noindex, nofollow'] as $r)
                <option value="{{ $r }}" {{ ($map['seo_meta_robots'] ?? 'index, follow') === $r ? 'selected' : '' }}>{{ $r }}</option>
                @endforeach
            </select>
            <p class="text-xs text-gray-400 mt-1">Mặc định <code>index, follow</code> — cho phép Google index và theo dõi link.</p>
        </div>

    </div>
</div>

{{-- Open Graph --}}
<div class="card scroll-mt-4" id="sec-opengraph">
    <div class="card-header flex items-center gap-3">
        <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
            <i class="fa-brands fa-facebook text-blue-600 text-sm"></i>
        </div>
        <div>
            <p class="text-sm font-semibold text-gray-800">Open Graph</p>
            <p class="text-xs text-gray-400">Hiển thị khi chia sẻ lên Facebook, Zalo, LinkedIn, Telegram, Pinterest…</p>
        </div>
    </div>
    <div class="card-body space-y-5">

        {{-- OG Image --}}
        <div>
            <label class="form-label">OG Image (Ảnh chia sẻ mạng xã hội)</label>
            <p class="text-xs text-gray-400 mb-1.5">Kích thước khuyến nghị: <strong>1200 × 630 px</strong>. Dùng cho Facebook, Zalo, LinkedIn, Telegram…</p>
            <div class="flex gap-2 items-center">
                <input type="text" name="settings[seo_og_image]" id="seo_og_image_input"
                       value="{{ $map['seo_og_image'] ?? '' }}"
                       class="form-input"
                       oninput="updateImgPreview('seo_og_image_preview', this.value)"
                       placeholder="https://example.com/og-image.jpg">
                <button type="button"
                        onclick="openMediaPicker('seo_og_image_input', function(url){ updateImgPreview('seo_og_image_preview', url); })"
                        class="flex-shrink-0 px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100 transition-colors flex items-center gap-1.5">
                    <i class="fa-solid fa-images text-blue-500 text-xs"></i> Đổi ảnh
                </button>
            </div>
            <div id="seo_og_image_preview" class="mt-2">
                @if(!empty($map['seo_og_image']))
                    <img src="{{ $map['seo_og_image'] }}" class="h-24 rounded-lg border border-gray-200 object-contain">
                @endif
            </div>
        </div>

        {{-- FB App ID --}}
        <div>
            <label class="form-label">Facebook App ID</label>
            <input type="text" name="settings[seo_fb_app_id]"
                   value="{{ $map['seo_fb_app_id'] ?? '' }}"
                   class="form-input"
                   placeholder="123456789012345">
            <p class="text-xs text-gray-400 mt-1">Lấy tại <a href="https://developers.facebook.com/apps" target="_blank" class="text-blue-500 hover:underline">developers.facebook.com/apps</a>. Giúp Facebook nhận diện website chính xác hơn.</p>
        </div>

        {{-- OG Preview --}}
        <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
            <p class="text-xs font-semibold text-gray-500 mb-3 uppercase tracking-wide">Preview khi chia sẻ Facebook</p>
            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden max-w-sm">
                <div id="og_preview_img" class="h-40 bg-gray-100 flex items-center justify-center text-gray-300 text-xs">
                    @if(!empty($map['seo_og_image']))
                        <img src="{{ $map['seo_og_image'] }}" class="w-full h-full object-cover">
                    @else
                        Chưa có ảnh OG
                    @endif
                </div>
                <div class="p-3">
                    <p class="text-xs text-gray-400 uppercase">{{ parse_url(url('/'), PHP_URL_HOST) }}</p>
                    <p class="text-sm font-semibold text-gray-800 mt-0.5 line-clamp-2">{{ $map['seo_meta_title'] ?? setting('site_name', 'VietTinMart') }}</p>
                    <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $map['seo_meta_desc'] ?? '' }}</p>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- Twitter / X Card --}}
<div class="card scroll-mt-4" id="sec-twitter">
    <div class="card-header flex items-center gap-3">
        <div class="w-8 h-8 rounded-lg bg-gray-900 flex items-center justify-center flex-shrink-0">
            <i class="fa-brands fa-x-twitter text-white text-sm"></i>
        </div>
        <div>
            <p class="text-sm font-semibold text-gray-800">Twitter / X Card</p>
            <p class="text-xs text-gray-400">Hiển thị khi chia sẻ lên Twitter/X</p>
        </div>
    </div>
    <div class="card-body space-y-5">

        {{-- Twitter Card Type --}}
        <div>
            <label class="form-label">Loại Twitter Card</label>
            <select name="settings[seo_twitter_card]" class="form-select">
                <option value="summary_large_image" {{ ($map['seo_twitter_card'] ?? 'summary_large_image') === 'summary_large_image' ? 'selected' : '' }}>summary_large_image (Ảnh lớn — khuyến nghị)</option>
                <option value="summary"             {{ ($map['seo_twitter_card'] ?? '') === 'summary'             ? 'selected' : '' }}>summary (Ảnh nhỏ)</option>
                <option value="app"                 {{ ($map['seo_twitter_card'] ?? '') === 'app'                 ? 'selected' : '' }}>app (Ứng dụng)</option>
            </select>
        </div>

        {{-- Twitter @site --}}
        <div>
            <label class="form-label">Twitter @username của website</label>
            <input type="text" name="settings[seo_twitter_site]"
                   value="{{ $map['seo_twitter_site'] ?? '' }}"
                   class="form-input"
                   placeholder="@VietTinMart">
            <p class="text-xs text-gray-400 mt-1">Tài khoản Twitter/X chính thức của website (bao gồm dấu @).</p>
        </div>

        {{-- Twitter @creator --}}
        <div>
            <label class="form-label">Twitter/label>
            <input type="text" name="settings[seo_twitter_creator]"
                   value="{{ $map['seo_twitter_creator'] ?? '' }}"
                   class="form-input"
                   placeholder="@author_handle">
            <p class="text-xs text-gray-400 mt-1">Tài khoản tác giả mặc định, dùng cho bài viết/sản phẩm không có tác giả riêng.</p>
        </div>

    </div>
</div>

{{-- Script --}}
<div class="card scroll-mt-4" id="sec-script">
    <div class="card-header flex items-center gap-3">
        <div class="w-8 h-8 rounded-lg bg-purple-50 flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-code text-purple-500 text-sm"></i>
        </div>
        <div>
            <p class="text-sm font-semibold text-gray-800">Script</p>
            <p class="text-xs text-gray-400">Chèn các thẻ script, code vào các vị trí trong trang</p>
        </div>
    </div>
    <div class="card-body space-y-5">

        @php
        $scriptFields = [
            ['key' => 'seo_script_header', 'label' => 'Script Header', 'desc' => 'Chèn các thẻ script, meta vào cuối thẻ &lt;head&gt; của trang.'],
            ['key' => 'seo_script_body',   'label' => 'Script Body',   'desc' => 'Chèn code vào ngay sau thẻ &lt;body&gt; mở đầu trang.'],
            ['key' => 'seo_script_footer', 'label' => 'Script Footer', 'desc' => 'Chèn code vào cuối trang, trước thẻ &lt;/body&gt;.'],
        ];
        @endphp

        @foreach($scriptFields as $sf)
        <div>
            <label class="form-label">{{ $sf['label'] }}</label>
            <p class="text-xs text-gray-400 mb-1.5">{!! $sf['desc'] !!}</p>
            <div class="rounded-xl overflow-hidden border border-gray-200">
                <div class="flex items-center justify-between px-3 py-1.5 bg-gray-800 border-b border-gray-700">
                    <div class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-red-400"></span>
                        <span class="w-2.5 h-2.5 rounded-full bg-yellow-400"></span>
                        <span class="w-2.5 h-2.5 rounded-full bg-green-400"></span>
                    </div>
                    <span class="text-xs text-gray-400 font-mono">{{ $sf['label'] }}</span>
                    <button type="button" onclick="copyCode('{{ $sf['key'] }}')"
                            class="text-xs text-gray-400 hover:text-white transition-colors flex items-center gap-1">
                        <i class="fa-regular fa-copy"></i> Copy
                    </button>
                </div>
                <textarea name="settings[{{ $sf['key'] }}]" id="code_{{ $sf['key'] }}"
                          rows="8"
                          class="w-full bg-gray-900 text-green-300 font-mono text-xs p-4 resize-y focus:outline-none border-0"
                          spellcheck="false"
                          placeholder="&lt;!-- Nhập code tại đây --&gt;">{{ $map[$sf['key']] ?? '' }}</textarea>
            </div>
        </div>
        @endforeach

    </div>
</div>

{{-- File Robots --}}
<div class="card scroll-mt-4" id="sec-robots">
    <div class="card-header flex items-center gap-3">
        <div class="w-8 h-8 rounded-lg bg-orange-50 flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-robot text-orange-500 text-sm"></i>
        </div>
        <div>
            <p class="text-sm font-semibold text-gray-800">File Robots</p>
            <p class="text-xs text-gray-400">Điều hướng các robot tìm kiếm cho phép hoặc không cho phép tìm kiếm file, thư mục.</p>
        </div>
    </div>
    <div class="card-body">
        <label class="form-label">Nội dung file robots</label>
        <div class="rounded-xl overflow-hidden border border-gray-200">
            <div class="flex items-center justify-between px-3 py-1.5 bg-gray-800 border-b border-gray-700">
                <div class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-red-400"></span>
                    <span class="w-2.5 h-2.5 rounded-full bg-yellow-400"></span>
                    <span class="w-2.5 h-2.5 rounded-full bg-green-400"></span>
                </div>
                <span class="text-xs text-gray-400 font-mono">robots.txt</span>
                <button type="button" onclick="copyCode('seo_robots_txt')"
                        class="text-xs text-gray-400 hover:text-white transition-colors flex items-center gap-1">
                    <i class="fa-regular fa-copy"></i> Copy
                </button>
            </div>
            <textarea name="settings[seo_robots_txt]" id="code_seo_robots_txt"
                      rows="8"
                      class="w-full bg-gray-900 text-green-300 font-mono text-xs p-4 resize-y focus:outline-none border-0"
                      spellcheck="false">{{ $map['seo_robots_txt'] ?? "User-agent: *\nDisallow: /search\nDisallow: /cart\nDisallow: /none" }}</textarea>
        </div>
    </div>
</div>

{{-- Chuyển hướng 404 --}}
<div class="card scroll-mt-4" id="sec-redirect">
    <div class="card-header flex items-center gap-3">
        <div class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-diamond-turn-right text-red-500 text-sm"></i>
        </div>
        <div>
            <p class="text-sm font-semibold text-gray-800">Chuyển hướng 404</p>
            <p class="text-xs text-gray-400">Quản lý chuyển hướng với các trang 404</p>
        </div>
    </div>
    <div class="card-body space-y-4">

        <div>
            <label class="form-label">Chuyển hướng đến</label>
            <select name="settings[seo_redirect_mode]" id="seo_redirect_mode"
                    class="form-select"
                    onchange="toggleRedirectUrl(this.value)">
                <option value="manual"   {{ ($map['seo_redirect_mode'] ?? 'manual') === 'manual'   ? 'selected' : '' }}>URL tùy chỉnh</option>
                <option value="homepage" {{ ($map['seo_redirect_mode'] ?? 'manual') === 'homepage' ? 'selected' : '' }}>Trang chủ website</option>
            </select>
        </div>

        <div class="space-y-1.5 text-xs text-gray-500 bg-gray-50 rounded-xl p-3">
            <p><span class="font-medium text-gray-700">Không chuyển hướng:</span> Để tự chuyển hướng</p>
            <p><span class="font-medium text-gray-700">Trang này website:</span> Chuyển hướng sang, đặt đầu bảng, đặt đầu bảng website</p>
            <p><span class="font-medium text-gray-700">URL tùy chỉnh:</span> Chuyển hướng đến, đặt đầu bảng, đặt đầu bảng nơi khác, v.v. thế</p>
        </div>

        <div id="redirect-url-wrap" class="{{ ($map['seo_redirect_mode'] ?? 'manual') !== 'manual' ? 'hidden' : '' }}">
            <label class="form-label">URL tùy chỉnh</label>
            <input type="text" name="settings[seo_redirect_url]"
                   value="{{ $map['seo_redirect_url'] ?? 'trang-chu' }}"
                   class="form-input"
                   placeholder="trang-chu">
            <p class="text-xs text-gray-400 mt-1">Nhập URL tùy chỉnh (không có https://) để sử dụng làm trang chuyển hướng.</p>
        </div>

        <div>
            <label class="form-label">Nhật ký 404 đổi</label>
            <input type="hidden" name="settings[seo_redirect_log_404]" value="0">
            <label class="inline-flex items-center gap-2 cursor-pointer">
                <div class="relative">
                    <input type="checkbox" name="settings[seo_redirect_log_404]" value="1"
                           {{ ($map['seo_redirect_log_404'] ?? '1') ? 'checked' : '' }}
                           class="sr-only peer">
                    <div class="w-10 h-5 bg-gray-200 rounded-full peer peer-checked:bg-blue-600 peer-checked:after:translate-x-5 after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:border after:border-gray-300 after:rounded-full after:h-4 after:w-4 after:transition-all"></div>
                </div>
                <span class="text-sm text-gray-600">Bật</span>
            </label>
            <p class="text-xs text-gray-400 mt-1">Ghi lại các trang 404 để xem xét.</p>
        </div>

    </div>
</div>


<div class="flex items-center gap-3 pt-1">
    <button type="submit" class="btn btn-primary">
        <i class="fa-solid fa-check text-xs"></i> Lưu cài đặt
    </button>
    <a href="{{ route('admin.settings.index') }}" class="btn btn-secondary">Hủy</a>
</div>

</div>
</form>

</div>
@endsection

@push('styles')
<style>
</style>
@endpush

@push('scripts')
<script>
function toggleRedirectUrl(val) {
    const wrap = document.getElementById('redirect-url-wrap');
    if (wrap) wrap.classList.toggle('hidden', val !== 'manual');
}

function copyCode(key) {
    const el = document.getElementById('code_' + key);
    if (!el) return;
    navigator.clipboard.writeText(el.value).then(() => {
        const editor = el.closest('[class*="rounded-xl"]');
        const btn = editor ? editor.querySelector('button') : null;
        if (btn) {
            const orig = btn.innerHTML;
            btn.innerHTML = '<i class="fa-solid fa-check"></i> Copied';
            setTimeout(() => btn.innerHTML = orig, 1500);
        }
    });
}

function updateImgPreview(previewId, url) {
    const wrap = document.getElementById(previewId);
    if (!wrap) return;
    wrap.innerHTML = url ? '<img src="' + url + '" class="h-16 rounded-lg border border-gray-200 object-contain">' : '';
}

document.querySelectorAll('.seo-nav-link').forEach(link => {
    link.addEventListener('click', e => {
        e.preventDefault();
        const target = document.querySelector(link.getAttribute('href'));
        if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
});
</script>
@endpush
