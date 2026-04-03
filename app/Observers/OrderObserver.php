<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
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
    }

    /**
     * Handle the Order "deleted" event (Hoàn kho khi xóa đơn hàng).
     */
    public function deleted(Order $order): void
    {
        // Nếu là xóa vĩnh viễn (forceDelete) hoặc tùy chính sách hoàn hàng.
        // Ở đây mình xử lý cho trường hợp muốn hoàn kho khi hủy/xóa đơn.
        foreach ($order->items as $item) {
            if ($item->variant_id) {
                $variant = ProductVariant::find($item->variant_id);
                if ($variant) {
                    $variant->increment('stock', $item->quantity);
                }
            } else {
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->increment('stock', $item->quantity);
                }
            }
        }
    }
}
