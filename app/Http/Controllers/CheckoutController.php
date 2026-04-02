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
        $subtotal = collect($cart)->sum(fn($i) => ($i['price'] ?? 0) * ($i['qty'] ?? 1));
        
        $appliedCoupons = session('applied_coupons', []);
        $couponList = [];
        $totalDiscount = 0;

        foreach ($appliedCoupons as $couponId) {
            $coupon = \App\Models\Coupon::find($couponId);
            if ($coupon && $coupon->isValid($subtotal)) {
                $discount = $coupon->calculateDiscount($subtotal);
                $totalDiscount += $discount;
                $couponList[] = [
                    'code' => $coupon->code,
                    'discount' => $discount
                ];
            }
        }

        $total = max(0, $subtotal - $totalDiscount);

        return view('shop.checkout', compact('cart', 'subtotal', 'totalDiscount', 'total', 'couponList'));
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
        $orderNumber = Order::generateOrderNumber();
        $shippingFee = $total >= 500000 ? 0 : 30000;

        $fullAddress = implode(', ', array_filter([
            $request->street_address,
            $request->ward_name,
            $request->district_name,
            $request->province_name,
        ]));

        $user = auth()->user();
        if ($user) {
            $user->update([
                'phone' => $user->phone ?: $request->phone,
                'email' => $user->email ?: $request->email,
            ]);
        }

        $totalDiscount = 0;
        $appliedCouponCodes = [];
        $appliedCoupons = session('applied_coupons', []);

        foreach ($appliedCoupons as $couponId) {
            $coupon = \App\Models\Coupon::find($couponId);
            if ($coupon && $coupon->isValid($total)) {
                $totalDiscount += $coupon->calculateDiscount($total);
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
