@php
    $schema = is_array($model?->schema_json) ? $model->schema_json : [];
    $mode   = $schema['mode'] ?? 'auto';
    $jsonld = '';

    $formatUrl = function($path, $isStorage = true) {
        if (!$path) return null;
        if (Str::startsWith($path, ['http://', 'https://'])) return $path;
        return $isStorage ? asset('storage/' . $path) : asset($path);
    };

    if ($mode === 'manual' && !empty($schema['raw'])) {
        $jsonld = $schema['raw'];
    } elseif ($mode === 'auto') {
        if ($context === 'product') {
            $siteLogo = setting('site_logo') ?: setting('seo_og_image') ?: 'theme/images/fav.png';
            $siteName = setting('site_name', 'VietTinMart');
            $brand = $schema['brand'] ?? $siteName;
            $condition = $schema['condition'] ?? 'NewCondition';
            $availability = $schema['availability'] ?? 'InStock';

            $data = [
                '@context' => 'https://schema.org/',
                '@type' => 'Product',
                'name' => $model->name,
                'image' => $formatUrl($model->image, false),
                'description' => $model->meta_description ?: Str::limit(strip_tags($model->description), 160),
                'sku' => $schema['mpn'] ?? $model->sku,
                'brand' => [
                    '@type' => 'Brand',
                    'name' => $brand
                ],
                'offers' => [
                    '@type' => 'Offer',
                    'url' => url()->current(),
                    'priceCurrency' => 'VND',
                    'price' => (float)$model->price,
                    'availability' => 'https://schema.org/' . $availability,
                    'itemCondition' => 'https://schema.org/' . $condition,
                ],
                'mainEntityOfPage' => [
                    '@type' => 'WebPage',
                    '@id' => url()->current()
                ]
            ];

            // Add Breadcrumb
            $breadcrumbs = [
                '@context' => 'https://schema.org',
                '@type' => 'BreadcrumbList',
                'itemListElement' => [
                    [
                        '@type' => 'ListItem',
                        'position' => 1,
                        'name' => $siteName,
                        'item' => url('/')
                    ],
                    [
                        '@type' => 'ListItem',
                        'position' => 2,
                        'name' => $model->name,
                        'item' => url()->current()
                    ]
                ]
            ];

            $jsonld = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n" . 
                      '</script>' . "\n" . 
                      '<script type="application/ld+json">' . "\n" . 
                      json_encode($breadcrumbs, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        } elseif ($context === 'post') {
            $type = $schema['type'] ?? 'Article';
            $author = $schema['author'] ?? ($model->author?->name ?? 'Admin');
            $siteLogo = setting('site_logo') ?: setting('seo_og_image') ?: 'theme/images/fav.png';
            $siteName = setting('site_name', 'VietTinMart');
            $publisher = $schema['publisher'] ?? $siteName;

            $data = [
                '@context' => 'https://schema.org',
                '@type' => $type,
                'headline' => $model->title,
                'image' => $model->thumbnail ? [$formatUrl($model->thumbnail, true)] : [],
                'datePublished' => $model->published_at?->toIso8601String() ?? $model->created_at->toIso8601String(),
                'dateModified' => $model->updated_at->toIso8601String(),
                'author' => [
                    '@type' => 'Person',
                    'name' => $author
                ],
                'publisher' => [
                    '@type' => 'Organization',
                    'name' => $publisher,
                    'logo' => [
                        '@type' => 'ImageObject',
                        'url' => $formatUrl($siteLogo, false)
                    ]
                ],
                'mainEntityOfPage' => [
                    '@type' => 'WebPage',
                    '@id' => url()->current()
                ]
            ];

            // Add Breadcrumb
            $breadcrumbs = [
                '@context' => 'https://schema.org',
                '@type' => 'BreadcrumbList',
                'itemListElement' => [
                    [
                        '@type' => 'ListItem',
                        'position' => 1,
                        'name' => $siteName,
                        'item' => url('/')
                    ],
                    [
                        '@type' => 'ListItem',
                        'position' => 2,
                        'name' => $model->title,
                        'item' => url()->current()
                    ]
                ]
            ];

            $jsonld = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n" . 
                      '</script>' . "\n" . 
                      '<script type="application/ld+json">' . "\n" . 
                      json_encode($breadcrumbs, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        }
    }
@endphp

@if($jsonld)
@push('schema_json')
<script type="application/ld+json">
{!! $jsonld !!}
</script>
@endpush
@endif
