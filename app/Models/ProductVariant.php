<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'sku',
        'price',
        'compare_price',
        'cost_price',
        'stock',
        'image',
        'description',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'price' => 'decimal:0',
        'compare_price' => 'decimal:0',
        'cost_price' => 'decimal:0',
        'is_active' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variantAttributes()
    {
        return $this->hasMany(ProductVariantAttribute::class, 'variant_id');
    }

    public function attributeValues()
    {
        return $this->belongsToMany(AttributeValue::class, 'product_variant_attributes', 'variant_id', 'attribute_value_id')
            ->withPivot('attribute_id');
    }

    /** "Size: M / Màu: Đỏ" */
    public function getLabelAttribute(): string
    {
        return $this->variantAttributes
            ->map(fn($va) => $va->attribute?->name . ': ' . $va->attributeValue?->value)
            ->filter()
            ->implode(' / ');
    }

    public function getEffectivePriceAttribute()
    {
        return $this->price ?? $this->product?->price;
    }
}
