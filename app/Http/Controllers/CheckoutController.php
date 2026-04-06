<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.page')->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        // Lấy tồn kho thực tế cho từng sản phẩm trong giỏ hàng
        foreach ($cart as $key => $item) {
            if (!empty($item['variant_id'])) {
                $variant = \App\Models\ProductVariant::find($item['variant_id']);
                $cart[$key]['current_stock'] = $variant ? $variant->stock : 0;
            } else {
                $product = \App\Models\Product::find($item['id']);
                $cart[$key]['current_stock'] = $product ? $product->stock : 0;
            }
        }

        $subtotal = collect($cart)->sum(fn($i) => ($i['price'] ?? 0) * ($i['qty'] ?? 1));
        
        $discountableSubtotal = collect($cart)
            ->filter(fn($i) => empty($i['is_combo']))
            ->sum(fn($i) => ($i['price'] ?? 0) * ($i['qty'] ?? 1));

        $appliedCoupons = session('applied_coupons', []);
        $couponList = [];
        $totalDiscount = 0;

        foreach ($appliedCoupons as $couponId) {
            $coupon = \App\Models\Coupon::find($couponId);
            if ($coupon && $coupon->isValid($discountableSubtotal)) {
                $discount = $coupon->calculateDiscount($discountableSubtotal);
                $totalDiscount += $discount;
                $couponList[] = [
                    'code' => $coupon->code,
                    'discount' => $discount
                ];
            }
        }

        $total = max(0, $subtotal - $totalDiscount);

        $availableCoupons = \App\Models\Coupon::where('is_active', true)
            ->where(function($q) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', now());
            })
            ->orderBy('min_order_value', 'asc')
            ->get();

        return view('shop.checkout', compact('cart', 'subtotal', 'totalDiscount', 'total', 'couponList', 'availableCoupons'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name'      => 'required|string|max:100',
            'last_name'       => 'required|string|max:100',
            'email'           => 'required|email',
            'phone'           => 'required|string|max:20',
            'street_address'  => 'required|string',
            'province_name'   => 'required|string',
            'district_name'   => 'required|string',
            'ward_name'       => 'required|string',
        ]);

        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.page')->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        $fullName    = trim($request->first_name . ' ' . $request->last_name);
        $total       = collect($cart)->sum(fn($i) => ($i['price'] ?? 0) * ($i['qty'] ?? 1));

        // Kiểm tra tồn kho trước khi tạo đơn
        foreach ($cart as $item) {
            if (!empty($item['variant_id'])) {
                $variant = \App\Models\ProductVariant::find($item['variant_id']);
                if (!$variant || $variant->stock < $item['qty']) {
                    return back()->with('error', "Sản phẩm '{$item['name']}' ({$item['variant_label']}) đã hết hàng hoặc không đủ số lượng.")->withInput();
                }
            } else {
                $product = \App\Models\Product::find($item['id']);
                if (!$product || $product->stock < $item['qty']) {
                    return back()->with('error', "Sản phẩm '{$item['name']}' đã hết hàng hoặc không đủ số lượng.")->withInput();
                }
            }
        }

        $orderNumber = Order::generateOrderNumber();
        
        // Cấu hình phí vận chuyển từ hệ thống
        $threshold = (float)setting('free_shipping_threshold', 500000);
        $defaultFee = (float)setting('default_shipping_fee', 30000);
        $shippingFee = $total >= $threshold ? 0 : $defaultFee;

        $fullAddress = implode(', ', array_filter([
            $request->street_address,
            $request->ward_name,
            $request->district_name,
            $request->province_name,
        ]));

        $user = auth()->user();
        if ($user) {
            // Update basic profile if empty
            $user->update([
                'phone' => $user->phone ?: $request->phone,
                'email' => $user->email ?: $request->email,
            ]);

            // Save this address as a UserAddress if it doesn't exist
            $exists = $user->addresses()->where('province_code', $request->province_code)
                                       ->where('ward_code', $request->ward_code)
                                       ->where('address_detail', $request->street_address)
                                       ->exists();
            if (!$exists) {
                $user->addresses()->create([
                    'receiver_name' => $fullName,
                    'receiver_phone' => $request->phone,
                    'province_code' => $request->province_code,
                    'ward_code' => $request->ward_code,
                    'province_name' => $request->province_name,
                    'district_name' => $request->district_name,
                    'ward_name' => $request->ward_name,
                    'address_detail' => $request->street_address,
                    'full_address' => $fullAddress,
                    'is_default' => $user->addresses()->count() === 0
                ]);
            }
        }

        $totalDiscount = 0;
        $appliedCouponCodes = [];
        $appliedCoupons = session('applied_coupons', []);

        $discountableSubtotal = collect($cart)
            ->filter(fn($i) => empty($i['is_combo']))
            ->sum(fn($i) => ($i['price'] ?? 0) * ($i['qty'] ?? 1));

        foreach ($appliedCoupons as $couponId) {
            $coupon = \App\Models\Coupon::find($couponId);
            $totalForCoupon = collect($cart)->sum(fn($i) => ($i['price'] ?? 0) * ($i['qty'] ?? 1));
            // We use the same filtering logic as index()
            if ($coupon && $coupon->isValid($discountableSubtotal)) {
                $discount = $coupon->calculateDiscount($discountableSubtotal);
                $totalDiscount += $discount;
                $appliedCouponCodes[] = $coupon->code;
                $coupon->increment('usage_count');
            }
        }

        $order = Order::create([
            'order_number'      => $orderNumber,
            'user_id'           => $user?->id,
            'customer_name'     => $fullName,
            'customer_email'    => $request->email,
            'customer_phone'    => $request->phone,
            'shipping_address'   => $fullAddress,
            'shipping_province'  => $request->province_name,
            'shipping_district'  => $request->district_name,
            'shipping_ward'      => $request->ward_name,
            'subtotal'           => $total,
            'discount'           => $totalDiscount,
            'shipping_fee'       => $shippingFee,
            'total'              => max(0, $total - $totalDiscount + $shippingFee),
            'status'             => 'pending',
            'payment_status'     => 'unpaid',
            'payment_method'     => $request->payment_method ?? 'cod',
            'customer_note'      => $request->notes,
            'coupon_code'        => implode(', ', $appliedCouponCodes),
        ]);

        foreach ($cart as $item) {
            OrderItem::create([
                'order_id'      => $order->id,
                'product_id'    => $item['id'],
                'variant_id'    => $item['variant_id'] ?? null,
                'product_name'  => $item['name'],
                'variant_label' => $item['variant_label'] ?? null,
                'price'         => $item['price'],
                'quantity'      => $item['qty'],
                'total'         => $item['price'] * $item['qty'],
                'image'         => $item['image'] ?? null,
                'sku'           => $item['sku'] ?? null,
            ]);
        }

        session()->forget(['cart', 'applied_coupons']);

        // Gửi Email thông báo NGAY LẬP TỨC tại đây để đảm bảo không bị bỏ sót
        try {
            \Illuminate\Support\Facades\Log::info("CLIENT CHECKOUT: Triggering mail for #{$orderNumber} to raw email: " . $request->email);
            $order->sendOrderPlacedNotifications();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Direct Mail Send Error for #' . $orderNumber . ': ' . $e->getMessage());
        }

        return redirect()->route('checkout.success', $orderNumber);
    }

    public function success(string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)->firstOrFail();
        return view('shop.success', compact('order'));
    }

    public function trackOrder(Request $request)
    {
        $order = null;
        $orderNumber = $request->get('order_id');
        $email = $request->get('email');

        if ($orderNumber && $email) {
            $order = Order::where('order_number', $orderNumber)
                ->where('customer_email', $email)
                ->with(['items'])
                ->first();
            
            if (!$order) {
                return view('pages.order-track')->with('error', 'Không tìm thấy đơn hàng với thông tin đã cung cấp. Vui lòng kiểm tra lại Mã đơn hàng và Email.');
            }
        }

        return view('pages.order-track', compact('order'));
    }
}
