<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code', 'name', 'type', 'value', 'min_order_value', 'max_discount_value',
        'start_date', 'end_date', 'usage_limit', 'usage_limit_per_user', 'usage_count', 'is_active'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date'   => 'datetime',
        'is_active'  => 'boolean',
    ];

    public function isValid($total = null): bool
    {
        return empty($this->getInvalidReason($total));
    }

    public function getInvalidReason($total = null): ?string
    {
        if (!$this->is_active) {
            return 'Tạm dừng';
        }

        $now = now();
        if ($this->start_date && $now->lt($this->start_date)) {
            return 'Chưa đến ngày';
        }
        if ($this->end_date && $now->gt($this->end_date)) {
            return 'Hết hạn';
        }

        if ($this->usage_limit && $this->usage_count >= $this->usage_limit) {
            return 'Hết lượt dùng';
        }

        if (!is_null($total) && $total < $this->min_order_value) {
            return 'Chưa đạt tối thiểu';
        }

        return null;
    }

    public function calculateDiscount($total): float
    {
        $discount = 0;
        if ($this->type === 'percentage') {
            $discount = ($total * $this->value) / 100;
            if ($this->max_discount_value && $discount > $this->max_discount_value) {
                $discount = (float) $this->max_discount_value;
            }
        } else {
            $discount = (float) $this->value;
        }

        return (float) min($discount, $total);
    }
}
