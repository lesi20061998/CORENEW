<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class AppearanceSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $group = 'appearance';

        $settings = [
            // Core Colors
            'color_primary' => '#629D23',
            'color_secondary' => '#1F1F25',
            'color_body' => '#6E777D',
            'color_heading_1' => '#2C3C28',
            'color_success' => '#3EB75E',
            'color_danger' => '#DC2626',
            'color_warning' => '#FF8F3C',
            'color_info' => '#1BA2DB',

            // Social Colors
            'color_facebook' => '#3B5997',
            'color_twitter' => '#1BA1F2',
            'color_youtube' => '#ED4141',
            'color_linkedin' => '#0077B5',
            'color_pinterest' => '#E60022',
            'color_instagram' => '#C231A1',
            'color_vimeo' => '#00ADEF',
            'color_twitch' => '#6441A3',
            'color_discord' => '#7289da',

            // Font Weights
            'p_light' => '300',
            'p_regular' => '400',
            'p_medium' => '500',
            'p_semi_bold' => '600',
            'p_bold' => '700',
            'p_extra_bold' => '800',
            'p_black' => '900',

            // Font Sizes
            'font_size_b1' => '16px',
            'font_size_b2' => '16px',
            'font_size_b3' => '14px',
            'line_height_b1' => '1.3',
            'line_height_b2' => '1.3',
            'line_height_b3' => '1.3',

            // Headings
            'h1' => '60px',
            'h2' => '30px',
            'h3' => '26px',
            'h4' => '18px',
            'h5' => '16px',
            'h6' => '15px',

            // Fonts configuration
            'font_main' => "Inter, sans-serif",
            'font_heading' => "Inter, sans-serif",
            'nav_font' => "Inter, sans-serif",
            'font_import_urls' => '',

            // Website Background
            'site_bg_color' => '#ffffff',
            
            // Header Top Bar
            'topbar_show' => '1',
            'topbar_bg_color' => '#f8f9fa',
            'topbar_text_color' => '#629D23',
            'topbar_welcome_text' => 'Chào mừng bạn đến với VietTinMart!',

            // Appearance Extras
            'theme_color' => '#629D23',
            'theme_link_color' => '#1F1F25',
        ];

        foreach ($settings as $key => $value) {
            Setting::set($key, $value, $group);
        }

        $this->command->info('Appearance settings seeded successfully from original design system.');
    }
}
