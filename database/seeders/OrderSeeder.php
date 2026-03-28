<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Order::truncate();
        OrderItem::truncate();
        OrderStatusHistory::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $users = User::where('role', 'user')->get();
        if ($users->isEmpty()) {
            $users = User::all();
        }
        $products = Product::where('status', 'active')->get();

        $statuses = ['pending', 'processing', 'confirmed', 'shipping', 'delivered', 'cancelled'];
        $paymentMethods = ['cod', 'bank_transfer', 'vnpay'];

        for ($i = 0; $i < 20; $i++) {
            $user = $users->random();
            $status = $statuses[array_rand($statuses)];
            $subtotal = 0;

            $order = Order::create([
                'user_id'          => $user->id,
                'order_number'     => Order::generateOrderNumber() . '-' . ($i + 1),
                'status'           => $status,
                'customer_name'    => $user->name,
                'customer_email'   => $user->email,
                'customer_phone'   => $user->phone ?? '0123456789',
                'shipping_address' => $user->address ?? '123 Đường ABC, Hà Nội',
                'payment_method'   => $paymentMethods[array_rand($paymentMethods)],
                'payment_status'   => ($status === 'delivered') ? 'paid' : 'unpaid',
                'subtotal'         => 0,
                'shipping_fee'     => 30000,
                'total'            => 0,
                'customer_note'    => 'Đơn hàng mẫu số ' . ($i + 1),
                'created_at'       => now()->subDays(rand(1, 30)),
            ]);

            // Add 1-3 items
            $itemCount = rand(1, 4);
            $orderItems = [];
            for ($j = 0; $j < $itemCount; $j++) {
                $product = $products->random();
                $variant = $product->variants->isNotEmpty() ? $product->variants->random() : null;
                
                $price = $variant ? ($variant->price ?? $product->price) : $product->price;
                $quantity = rand(1, 3);
                $subtotal += ($price * $quantity);

                OrderItem::create([
                    'order_id'      => $order->id,
                    'product_id'    => $product->id,
                    'variant_id'    => $variant?->id,
                    'product_name'  => $product->name,
                    'variant_label' => $variant?->label,
                    'sku'           => $variant?->sku ?? $product->sku,
                    'price'         => $price,
                    'quantity'      => $quantity,
                    'total'         => ($price * $quantity),
                ]);
            }

            $order->update([
                'subtotal' => $subtotal,
                'total'    => $subtotal + 30000,
            ]);

            // Add some history
            OrderStatusHistory::create([
                'order_id'   => $order->id,
                'status'     => 'pending',
                'note'       => 'Đã đặt hàng thành công.',
                'created_by' => $user->id,
                'created_at' => $order->created_at,
            ]);

            if ($status !== 'pending') {
                OrderStatusHistory::create([
                    'order_id'   => $order->id,
                    'status'     => $status,
                    'note'       => 'Cập nhật trạng thái tự động bởi seeder.',
                    'created_by' => null,
                    'created_at' => $order->created_at->addHours(rand(1, 24)),
                ]);
            }
        }

        $this->command->info('✅ Đã seed ' . Order::count() . ' đơn hàng');
    }
}
