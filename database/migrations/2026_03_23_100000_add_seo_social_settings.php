<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Setting;

return new class extends Migration
{
    public function up(): void
    {
        $defaults = [
            // Open Graph
            ['key' => 'seo_og_image',         'value' => '',                    'group' => 'seo', 'type' => 'image',    'label' => 'OG Image (1200×630)'],
            ['key' => 'seo_fb_app_id',         'value' => '',                    'group' => 'seo', 'type' => 'text',     'label' => 'Facebook App ID'],
            // Twitter / X
            ['key' => 'seo_twitter_card',      'value' => 'summary_large_image', 'group' => 'seo', 'type' => 'text',     'label' => 'Twitter Card Type'],
            ['key' => 'seo_twitter_site',      'value' => '',                    'group' => 'seo', 'type' => 'text',     'label' => 'Twitter @site'],
            ['key' => 'seo_twitter_creator',   'value' => '',                    'group' => 'seo', 'type' => 'text',     'label' => 'Twitter @creator'],
            // Robots
            ['key' => 'seo_meta_robots',       'value' => 'index, follow',       'group' => 'seo', 'type' => 'text',     'label' => 'Meta Robots'],
        ];

        foreach ($defaults as $s) {
            Setting::firstOrCreate(
                ['key' => $s['key']],
                ['value' => $s['value'], 'group' => $s['group'], 'type' => $s['type'], 'label' => $s['label']]
            );
        }
    }

    public function down(): void
    {
        $keys = ['seo_og_image','seo_fb_app_id','seo_twitter_card','seo_twitter_site','seo_twitter_creator','seo_meta_robots'];
        \App\Models\Setting::whereIn('key', $keys)->delete();
    }
};
