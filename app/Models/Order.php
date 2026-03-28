<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'order_number','user_id','status','payment_status','payment_method',
        'subtotal','discount','shipping_fee','total',
        'customer_name','customer_email','customer_phone',
        'shipping_address','shipping_province','shipping_district','shipping_ward',
        'customer_note','admin_note','coupon_code',
    ];

    protected $casts = [
        'subtotal'     => 'decimal:0',
        'discount'     => 'decimal:0',
        'shipping_fee' => 'decimal:0',
        'total'        => 'decimal:0',
    ];

    public static array $statuses = [
        'pending'    => ['label' => 'Chờ xác nhận', 'color' => 'yellow'],
        'processing' => ['label' => 'Đang xử lý',   'color' => 'blue'],
        'confirmed'  => ['label' => 'Đã xác nhận',  'color' => 'blue'],
        'shipping'   => ['label' => 'Đang giao',     'color' => 'purple'],
        'delivered'  => ['label' => 'Đã giao',       'color' => 'green'],
        'completed'  => ['label' => 'Hoàn thành',    'color' => 'green'],
        'cancelled'  => ['label' => 'Đã hủy',        'color' => 'red'],
        'refunded'   => ['label' => 'Hoàn tiền',     'color' => 'gray'],
    ];

    public static array $paymentStatuses = [
        'unpaid'   => ['label' => 'Chưa thanh toán', 'color' => 'red'],
        'paid'     => ['label' => 'Đã thanh toán',   'color' => 'green'],
        'partial'  => ['label' => 'Thanh toán 1 phần','color' => 'yellow'],
        'refunded' => ['label' => 'Đã hoàn tiền',    'color' => 'gray'],
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function statusHistories()
    {
        return $this->hasMany(OrderStatusHistory::class)->latest();
    }

    public function getStatusLabelAttribute(): string
    {
        return self::$statuses[$this->status]['label'] ?? $this->status;
    }

    public function getStatusColorAttribute(): string
    {
        return self::$statuses[$this->status]['color'] ?? 'gray';
    }

    public function getPaymentStatusLabelAttribute(): string
    {
        return self::$paymentStatuses[$this->payment_status]['label'] ?? $this->payment_status;
    }

    public static function generateOrderNumber(): string
    {
        $date = now()->format('Ymd');
        $last = static::whereDate('created_at', today())->count() + 1;
        return 'ORD-' . $date . '-' . str_pad($last, 4, '0', STR_PAD_LEFT);
    }
}
