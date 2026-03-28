<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Widget extends Model
{
    protected $fillable = ['name', 'type', 'area', 'config', 'sort_order', 'is_active'];

    protected $casts = [
        'config'    => 'array',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForArea($query, string $area)
    {
        return $query->where('area', $area)->orderBy('sort_order');
    }

    /**
     * Returns registered widget types with their config field definitions.
     * Add new widget types here — no other code changes needed.
     */
    public static function registeredTypes(): array
    {
        return [
            'slider' => [
                'label'  => 'Hero Slider',
                'fields' => [
                    ['key' => 'title',    'label' => 'Title',       'type' => 'text'],
                    ['key' => 'subtitle', 'label' => 'Subtitle',    'type' => 'text'],
                    ['key' => 'slides',   'label' => 'Slides (JSON)','type' => 'json'],
                ],
            ],
            'about' => [
                'label'  => 'About Section',
                'fields' => [
                    ['key' => 'title',       'label' => 'Title',       'type' => 'text'],
                    ['key' => 'description', 'label' => 'Description', 'type' => 'textarea'],
                    ['key' => 'image',       'label' => 'Image URL',   'type' => 'text'],
                ],
            ],
            'featured_products' => [
                'label'  => 'Featured Products',
                'fields' => [
                    ['key' => 'title', 'label' => 'Section Title', 'type' => 'text'],
                    ['key' => 'limit', 'label' => 'Number of Products', 'type' => 'number'],
                ],
            ],
            'banner' => [
                'label'  => 'Banner',
                'fields' => [
                    ['key' => 'image',    'label' => 'Image URL', 'type' => 'text'],
                    ['key' => 'link',     'label' => 'Link URL',  'type' => 'text'],
                    ['key' => 'alt_text', 'label' => 'Alt Text',  'type' => 'text'],
                ],
            ],
            'latest_posts' => [
                'label'  => 'Latest Posts',
                'fields' => [
                    ['key' => 'title', 'label' => 'Section Title', 'type' => 'text'],
                    ['key' => 'limit', 'label' => 'Number of Posts', 'type' => 'number'],
                ],
            ],
        ];
    }

    /** Get the blade view path for this widget type */
    public function getViewPath(): string
    {
        return 'widgets.' . $this->type;
    }
}
