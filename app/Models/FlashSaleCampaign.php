<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class FlashSaleCampaign extends Model
{
    protected $fillable = [
        'name', 'description', 'starts_at', 'ends_at', 'status', 'apply_to_all',
    ];

    protected $casts = [
        'starts_at'    => 'datetime',
        'ends_at'      => 'datetime',
        'apply_to_all' => 'boolean',
    ];

    public static array $statuses = [
        'draft'  => ['label' => 'Nháp',        'color' => 'gray'],
        'active' => ['label' => 'Đang chạy',   'color' => 'green'],
        'ended'  => ['label' => 'Đã kết thúc', 'color' => 'red'],
    ];

    public function items()
    {
        return $this->hasMany(FlashSaleItem::class, 'campaign_id');
    }

    public function productItems()
    {
        return $this->hasMany(FlashSaleItem::class, 'campaign_id')->whereNotNull('product_id');
    }

    public function categoryItems()
    {
        return $this->hasMany(FlashSaleItem::class, 'campaign_id')->whereNotNull('category_id');
    }

    /** Chiến dịch đang hoạt động tại thời điểm hiện tại */
    public function scopeRunning($query)
    {
        $now = Carbon::now();
        return $query->where('status', 'active')
                     ->where('starts_at', '<=', $now)
                     ->where('ends_at', '>=', $now);
    }

    public function getIsRunningAttribute(): bool
    {
        return $this->status === 'active'
            && $this->starts_at <= now()
            && $this->ends_at >= now();
    }

    public function getStatusLabelAttribute(): string
    {
        return self::$statuses[$this->status]['label'] ?? $this->status;
    }

    public function getStatusColorAttribute(): string
    {
        return self::$statuses[$this->status]['color'] ?? 'gray';
    }
}
