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

    public function sendOrderPlacedNotifications(): void
    {
        try {
            // 1. Nạp cấu hình SMTP sạch
            \App\Services\MailConfigService::applySettings();
            $mailerName = setting('mail_mailer', 'smtp');
            
            // 2. Lấy dữ liệu mới nhất (Refresh)
            $order = $this->fresh(['items']);
            if (!$order) return;

            // 3. Chuẩn bị nội dung TEXT gọn nhẹ
            $itemsText = "";
            foreach($order->items as $item) {
                $price = number_format($item->price, 0, ',', '.');
                $itemTotal = number_format($item->total, 0, ',', '.');
                $itemsText .= "- {$item->product_name} | SL: {$item->quantity} | Đơn giá: {$price}₫ | Thành tiền: {$itemTotal}₫\n";
            }

            $orderTotalFormatted = number_format($order->total, 0, ',', '.');
            $subtotalFormatted = number_format($order->subtotal, 0, ',', '.');
            $shippingFormatted = number_format($order->shipping_fee, 0, ',', '.');

            $contentCustomer = "CHÀO {$order->customer_name},\n\n" .
                               "ĐƠN HÀNG #{$order->order_number} ĐÃ ĐƯỢC TIẾP NHẬN!\n\n" .
                               "CHI TIẾT ĐƠN HÀNG:\n" .
                               "-----------------------------------\n" .
                               $itemsText .
                               "-----------------------------------\n" .
                               "Tạm tính: {$subtotalFormatted}₫\n" .
                               "Phí vận chuyển: {$shippingFormatted}₫\n" .
                               "TỔNG CỘNG: {$orderTotalFormatted}₫\n\n" .
                               "Địa chỉ nhận hàng: {$order->shipping_address}\n" .
                               "Cảm ơn bạn đã tin tưởng VietTinMart!";

            $contentAdmin = "THÔNG BÁO: CÓ ĐƠN HÀNG MỚI #{$order->order_number}\n\n" .
                            "Thông tin khách hàng:\n" .
                            "Họ tên: {$order->customer_name}\n" .
                            "Điện thoại: {$order->customer_phone}\n\n" .
                            "Thông tin sản phẩm:\n" .
                            $itemsText . "\n" .
                            "Tổng tiền thanh toán: {$orderTotalFormatted}₫\n" .
                            "Vui lòng vào trang quản trị để xử lý.";

            // 4. Gửi thực tế: Dùng lại đúng mẫu số (Mail::to) đã giúp Cập nhật đơn hàng thành công
            \Illuminate\Support\Facades\Log::info("Action: Dispatching Professional Mail for #{$order->order_number} to " . ($order->customer_email ?: 'No Email'));

            // Sử dụng chính xác Mailable để Gmail tin tưởng
            $mailableCustomer = new \App\Mail\OrderPlaced($order, 'customer');
            $mailableAdmin = new \App\Mail\OrderPlaced($order, 'admin');

            // Gửi cho Khách hàng
            if ($order->customer_email && setting('notification_order_confirmed_customer', '1') == '1') {
                \Illuminate\Support\Facades\Mail::mailer($mailerName)->to($order->customer_email)->send($mailableCustomer);
            }

            // Gửi cho Admin
            if (setting('notification_order_confirmed_admin', '1') == '1') {
                $adminEmail = setting('contact_email');
                if ($adminEmail) {
                    \Illuminate\Support\Facades\Mail::mailer($mailerName)->to($adminEmail)->send($mailableAdmin);
                }
            }
            
            \Illuminate\Support\Facades\Log::info("SUCCESS: Pro-Mail dispatched for #{$order->order_number}");

        } catch (\Throwable $t) {
            \Illuminate\Support\Facades\Log::error("Order #{$this->order_number} Placement Email Error: " . $t->getMessage());
        }
    }
}
