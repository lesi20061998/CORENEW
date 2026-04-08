<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SettingService;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    // Định nghĩa các module settings hiển thị trên trang chủ settings
    protected array $modules = [
        [
            'group'       => 'appearance',
            'title'       => 'Giao diện',
            'description' => 'Màu sắc, Header, Footer, Topbar, Mobile Layout',
            'icon'        => 'fa-solid fa-palette',
            'hidden'      => true,
        ],
        [
            'group'       => 'contact',
            'title'       => 'Thông tin liên hệ',
            'description' => 'Quản lý thông tin liên hệ email, số điện thoại, địa chỉ',
            'icon'        => 'fa-solid fa-address-card',
        ],
        [
            'group'       => 'notification',
            'title'       => 'Thông báo',
            'description' => 'Quản lý thông báo, nội dung email, thông tin smtp',
            'icon'        => 'fa-solid fa-envelope',
        ],
        [
            'group'       => 'fonts',
            'title'       => 'Fonts',
            'description' => 'Quản lý danh sách fonts chữ được sử dụng',
            'icon'        => 'fa-solid fa-font',
        ],
        [
            'group'       => 'toc',
            'title'       => 'TOC',
            'description' => 'Cấu hình mục lục tự động cho bài viết',
            'icon'        => 'fa-solid fa-list-ul',
        ],
        [
            'group'       => 'social',
            'title'       => 'Mạng xã hội',
            'description' => 'Quản lý liên kết mạng xã hội zalo, facebook, tiktok...',
            'icon'        => 'fa-solid fa-share-nodes',
        ],
        [
            'group'       => 'payment',
            'title'       => 'Phương thức thanh toán',
            'description' => 'Quản lý, cấu hình phương thức thanh toán của website.',
            'icon'        => 'fa-solid fa-credit-card',
        ],
        [
            'group'       => 'shipping',
            'title'       => 'Vận chuyển',
            'description' => 'Cấu hình phương thức vận chuyển của website.',
            'icon'        => 'fa-solid fa-truck',
        ],
        [
            'group'       => 'review',
            'title'       => 'Đánh giá sao',
            'description' => 'Quản lý cấu hình đánh giá bài viết, sản phẩm',
            'icon'        => 'fa-solid fa-star',
        ],
        [
            'group'       => 'button',
            'title'       => 'Button liên hệ',
            'description' => 'Quản lý button mạng xã hội, liên hệ, hotline',
            'icon'        => 'fa-solid fa-share-alt',
        ],
        [
            'group'       => 'redirect',
            'title'       => '404 Redirect',
            'description' => 'Quản lý log chuyển hướng link 404',
            'icon'        => 'fa-solid fa-diamond-turn-right',
        ],
        [
            'group'       => 'seo',
            'title'       => 'SEO',
            'description' => 'Quản lý thông tin hỗ trợ seo website',
            'icon'        => 'fa-solid fa-magnifying-glass-chart',
        ],
        [
            'group'       => 'ghost_notification',
            'title'       => 'Thông báo ảo',
            'description' => 'Quản lý thông báo đơn hàng ảo',
            'icon'        => 'fa-solid fa-bell',
        ],
        [
            'group'       => 'general',
            'title'       => 'Cài đặt chung',
            'description' => 'Tên site, logo, favicon, tiền tệ',
            'icon'        => 'fa-solid fa-gear',
        ],
        [
            'group'       => 'tracking',
            'title'       => 'Tracking & Analytics',
            'description' => 'Google Analytics, Facebook Pixel',
            'icon'        => 'fa-solid fa-chart-line',
        ],
        [
            'group'       => 'shop',
            'title'       => 'Cửa hàng',
            'description' => 'Cấu hình bộ lọc giá, hiển thị sản phẩm',
            'icon'        => 'fa-solid fa-store',
        ],
    ];

    public function __construct(
        protected SettingService $settingService
    ) {}

    public function index()
    {
        $modules = collect($this->modules)->where('hidden', '!=', true)->all();
        return view('admin.settings.index', compact('modules'));
    }

    public function show(string $group)
    {
        $module = collect($this->modules)->firstWhere('group', $group);

        if (!$module) {
            abort(404);
        }

        $settings = $this->settingService->getSettingsByGroup($group);

        // Payment group gets its own dedicated view
        if ($group === 'payment') {
            $settingsMap = $settings->pluck('value', 'key');
            return view('admin.settings.payment', compact('module', 'settings', 'settingsMap'));
        }

        // SEO group gets its own dedicated view
        if ($group === 'seo') {
            return view('admin.settings.seo', compact('module', 'settings'));
        }

        // TOC group gets its own dedicated view
        if ($group === 'toc') {
            $settingsMap = $settings->pluck('value', 'key');
            return view('admin.settings.toc', compact('module', 'settings', 'settingsMap'));
        }

        // Review group gets its own dedicated view
        if ($group === 'review') {
            $settingsMap = $settings->pluck('value', 'key');
            return view('admin.settings.review', compact('module', 'settings', 'settingsMap'));
        }

        // Appearance group gets its own dedicated view
        if ($group === 'appearance') {
            $settingsMap = $settings->pluck('value', 'key');
            return view('admin.settings.appearance', compact('module', 'settings', 'settingsMap'));
        }

        return view('admin.settings.group', compact('module', 'settings'));
    }

    public function update(Request $request, string $group)
    {
        $module = collect($this->modules)->firstWhere('group', $group);

        if (!$module) {
            abort(404);
        }

        $settings = $request->input('settings', []);

        // Handle price_presets textarea — store raw JSON string
        if ($group === 'shop' && isset($settings['price_presets'])) {
            // Validate it's valid JSON
            $decoded = json_decode($settings['price_presets'], true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return back()->withErrors(['price_presets' => 'Mốc giá không hợp lệ (JSON không đúng định dạng).'])->withInput();
            }
            // Re-encode to normalize
            $settings['price_presets'] = json_encode($decoded, JSON_UNESCAPED_UNICODE);
        }

        // Handle order_cancellation_reasons textarea — store raw JSON string
        if ($group === 'notification' && isset($settings['order_cancellation_reasons'])) {
            $decoded = json_decode($settings['order_cancellation_reasons'], true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return back()->withErrors(['order_cancellation_reasons' => 'Lý do hủy đơn không hợp lệ (JSON không đúng định dạng).'])->withInput();
            }
            $settings['order_cancellation_reasons'] = json_encode($decoded, JSON_UNESCAPED_UNICODE);
        }

        // Auto-generate font_import_urls from selected fonts (appearance group)
        if ($group === 'appearance' && empty($settings['font_import_urls'])) {
            $settings['font_import_urls'] = $this->buildFontImportUrls(
                $settings['font_main'] ?? null,
                $settings['font_heading'] ?? null,
                $settings['nav_font'] ?? null,
            );
        }

        $this->settingService->updateSettings($settings, $group);

        return redirect()->route('admin.settings.group', $group)
                         ->with('success', 'Đã lưu cài đặt "' . $module['title'] . '" thành công.');
    }

    /**
     * Map font CSS value → Google Fonts family name + weights
     */
    protected array $googleFontMap = [
        "'Be Vietnam Pro', sans-serif"  => 'Be+Vietnam+Pro',
        "'Roboto', sans-serif"          => 'Roboto',
        "'Outfit', sans-serif"          => 'Outfit',
        "'Montserrat', sans-serif"      => 'Montserrat',
        "'Barlow', sans-serif"          => 'Barlow',
        "'Playfair Display', serif"     => 'Playfair+Display',
        "'Open Sans', sans-serif"       => 'Open+Sans',
        // Inter is a system font / already loaded by default — skip
    ];

    protected function buildFontImportUrls(?string ...$fonts): string
    {
        $families = [];
        foreach ($fonts as $font) {
            if (!$font) continue;
            $font = trim($font);
            if (isset($this->googleFontMap[$font])) {
                $families[] = $this->googleFontMap[$font] . ':wght@300;400;500;600;700;800;900';
            }
        }

        if (empty($families)) {
            // Default fallback
            return '<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">';
        }

        $unique = array_unique($families);
        $query  = implode('&family=', $unique);
        return '<link href="https://fonts.googleapis.com/css2?family=' . $query . '&display=swap" rel="stylesheet">';
    }

    public function testMail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        try {
            // Apply current settings from DB
            \App\Services\MailConfigService::applySettings();
            
            \Illuminate\Support\Facades\Mail::raw('Email này được gửi để kiểm tra cấu hình SMTP trên website của bạn.', function($message) use ($request) {
                $message->to($request->email)
                        ->subject('Kiểm tra cấu hình SMTP - ' . config('app.name'));
            });

            return response()->json([
                'success' => true,
                'message' => 'Email thử nghiệm đã được gửi đến ' . $request->email
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi gửi email: ' . $e->getMessage()
            ], 500);
        }
    }
}
