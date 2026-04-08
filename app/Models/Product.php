<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use App\Traits\HasTranslations;

use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, HasTranslations, SoftDeletes;

    // Fields có thể dịch đa ngôn ngữ
    public array $translatableFields = ['name', 'short_description', 'description', 'additional_info', 'meta_title', 'meta_description'];

    protected $fillable = [
        'name',
        'slug',
        'short_description',
        'description',
        'additional_info',
        'price',
        'compare_price',
        'cost_price',
        'sku',
        'stock',
        'stock_status',
        'has_variants',
        'weight',
        'status',
        'is_featured',
        'is_favorite',
        'is_best_seller',
        'sort_order',
        'image',
        'images',
        'category_id',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'canonical_url',
        'seo_focus_keyword',
        'robots_meta',
        'schema_json',
    ];

    protected $casts = [
        'price' => 'decimal:0',
        'compare_price' => 'decimal:0',
        'cost_price' => 'decimal:0',
        'images' => 'array',
        'has_variants' => 'boolean',
        'is_featured' => 'boolean',
        'is_favorite' => 'boolean',
        'is_best_seller' => 'boolean',
        'schema_json' => 'array',
        'robots_meta' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });

        static::updating(function ($product) {
            if ($product->isDirty('name') && empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'product_attributes')
            ->withPivot('attribute_value_id')
            ->withTimestamps();
    }

    public function attributeValues()
    {
        return $this->belongsToMany(AttributeValue::class, 'product_attributes')
            ->withPivot('attribute_id')
            ->withTimestamps();
    }

    public function productAttributes()
    {
        return $this->hasMany(ProductAttribute::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class)->orderBy('sort_order');
    }

    public function activeVariants()
    {
        return $this->hasMany(ProductVariant::class)->where('is_active', true)->orderBy('sort_order');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_product');
    }

    public function combos()
    {
        return $this->belongsToMany(Product::class, 'product_combos', 'product_id', 'combo_product_id')
            ->withPivot('combo_product_variant_id', 'combo_price', 'discount_type', 'discount_value', 'sort_order', 'is_active')
            ->withTimestamps();
    }

    public function activeCombos()
    {
        return $this->combos()->wherePivot('is_active', true)->orderByPivot('sort_order');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    public function scopeWithFilters($query, array $filters = [])
    {
        if (empty($filters)) {
            return $query;
        }

        return $query->whereHas('attributeValues', function ($q) use ($filters) {
            $q->whereIn('attribute_values.slug', $filters);
        });
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        if (!$this->image)
            return null;
        if (str_starts_with($this->image, 'http'))
            return $this->image;
        if (str_starts_with($this->image, 'media/'))
            return asset('storage/' . $this->image);
        return asset($this->image);
    }

    public function getFormattedPriceAttribute()
    {
        $price = $this->effective_price;
        if ($price <= 0) {
            return 'Giá liên hệ';
        }
        return number_format((float) $price, 0, ',', '.') . ' ₫';
    }

    public function getOldPriceAttribute(): ?float
    {
        // Nếu đang có flash sale, giá cũ là giá thường của sản phẩm
        if ($this->flash_price !== null) {
            return (float) $this->price;
        }

        // Nếu không có flash sale, giá cũ là compare_price (nếu lớn hơn giá hiện tại)
        if ($this->compare_price && $this->compare_price > $this->price) {
            return (float) $this->compare_price;
        }

        return null;
    }

    public function getDiscountPercentAttribute(): ?int
    {
        $currentPrice = $this->effective_price;
        $oldPrice = $this->old_price;

        if ($currentPrice <= 0 || !$oldPrice || $oldPrice <= $currentPrice) {
            return null;
        }

        return (int) round((1 - $currentPrice / $oldPrice) * 100);
    }

    public function getImagesAttribute($value)
    {
        return json_decode($value) ?: [];
    }

    public function getImagesUrlsAttribute(): array
    {
        return array_map(function ($img) {
            if (!$img)
                return null;
            if (str_starts_with($img, 'http'))
                return $img;
            if (str_starts_with($img, 'media/'))
                return asset('storage/' . $img);
            return asset($img);
        }, $this->images);
    }

    public function getHasContactPriceAttribute(): bool
    {
        return $this->price <= 0;
    }

    /**
     * Lấy giá flash sale hiện tại (nếu có chiến dịch đang chạy).
     * Trả về null nếu không có flash sale.
     */
    public function getFlashPriceAttribute(): ?float
    {
        $item = app(\App\Services\FlashSaleService::class)->getActiveItemForProduct($this);
        if (!$item)
            return null;
        return $item->calcFlashPrice((float) $this->price);
    }

    /**
     * Giá hiển thị cuối cùng: flash sale > giá thường
     */
    public function getEffectivePriceAttribute(): float
    {
        return $this->flash_price ?? (float) $this->price;
    }

    /**
     * Phần trăm giảm giá flash sale
     */
    public function getFlashDiscountPercentAttribute(): ?int
    {
        $flashPrice = $this->flash_price;
        if ($flashPrice === null)
            return null;
        return (int) round((1 - $flashPrice / $this->price) * 100);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function approvedReviews()
    {
        return $this->hasMany(Review::class)->where('status', 'approved');
    }

    public function getAverageRatingAttribute()
    {
        return round($this->approvedReviews()->avg('rating'), 1) ?: 0;
    }
}