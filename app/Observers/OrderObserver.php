<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\MailConfigService;
use App\Mail\OrderPlaced;
use App\Mail\OrderStatusUpdated;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        // 1. Decrement stock
        foreach ($order->items as $item) {
            if ($item->variant_id) {
                $variant = ProductVariant::find($item->variant_id);
                if ($variant) {
                    $variant->decrement('stock', $item->quantity);
                }
            } else {
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->decrement('stock', $item->quantity);
                }
            }
        }

        // 2. Send emails: MOVED TO CONTROLLER TO AVOID EMPTY ITEMS IN EMAIL
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        // 1. Notify for Order Status Change
        if ($order->wasChanged('status')) {
            $statusLabel = Order::$statuses[$order->status]['label'] ?? $order->status;
            $this->sendNotification($order, "Trạng thái đơn hàng: $statusLabel");
        }

        // 2. Notify for Payment Status Change
        if ($order->wasChanged('payment_status')) {
            $paymentLabel = Order::$paymentStatuses[$order->payment_status]['label'] ?? $order->payment_status;
            $this->sendNotification($order, "Trạng thái thanh toán: $paymentLabel");
        }
    }

    /**
     * Helper to send notification email.
     */
    protected function sendNotification(Order $order, string $label): void
    {
        if ($order->customer_email) {
            try {
                if (!MailConfigService::applySettings() && empty(config('mail.mailers.smtp.host'))) {
                    return;
                }

                $latestHistory = $order->statusHistories()->latest()->first();
                $note = $latestHistory ? $latestHistory->note : null;

                Mail::to($order->customer_email)->send(new OrderStatusUpdated($order, $label, $note));
            } catch (\Exception $e) {
                Log::error('Order Notification Error: ' . $e->getMessage());
            }
        }
    }

    public function deleted(Order $order): void
    {
        // Nếu là xóa vĩnh viễn (forceDelete) hoặc tùy chính sách hoàn hàng.
        // Ở đây mình xử lý cho trường hợp muốn hoàn kho khi hủy/xóa đơn.
        foreach ($order->items as $item) {
            if ($item->variant_id) {
                $variant = \App\Models\ProductVariant::find($item->variant_id);
                if ($variant) {
                    $variant->increment('stock', $item->quantity);
                }
            } else {
                $product = \App\Models\Product::find($item->product_id);
                if ($product) {
                    $product->increment('stock', $item->quantity);
                }
            }
        }
    }
}
