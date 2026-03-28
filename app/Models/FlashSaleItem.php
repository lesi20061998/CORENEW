<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlashSaleItem extends Model
{
    protected $fillable = [
        'campaign_id', 'product_id', 'category_id',
        'discount_type', 'discount_value', 'sale_limit', 'sold_count',
    ];

    protected $casts = [
        'discount_value' => 'decimal:0',
        'sale_limit'     => 'integer',
        'sold_count'     => 'integer',
    ];

    public function campaign()
    {
        return $this->belongsTo(FlashSaleCampaign::class, 'campaign_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Tính giá flash sale từ giá gốc
     */
    public function calcFlashPrice(float $originalPrice): float
    {
        if ($this->discount_type === 'percent') {
            return max(0, $originalPrice * (1 - $this->discount_value / 100));
        }
        return max(0, $originalPrice - $this->discount_value);
    }

    public function getIsLimitedAttribute(): bool
    {
        return $this->sale_limit !== null;
    }

    public function getRemainingAttribute(): ?int
    {
        if ($this->sale_limit === null) return null;
        return max(0, $this->sale_limit - $this->sold_count);
    }
}
