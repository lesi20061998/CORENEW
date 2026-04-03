<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function page()
    {
        $cart = session('cart', []);
        $subtotal = collect($cart)->sum(fn($i) => ($i['price'] ?? 0) * ($i['qty'] ?? 1));
        
        $appliedCoupons = session('applied_coupons', []);
        $validCoupons = [];
        $totalDiscount = 0;

        foreach ($appliedCoupons as $couponId) {
            $coupon = \App\Models\Coupon::find($couponId);
            if ($coupon && $coupon->isValid($subtotal)) {
                $discount = $coupon->calculateDiscount($subtotal);
                $totalDiscount += $discount;
                $validCoupons[] = [
                    'id' => $coupon->id,
                    'code' => $coupon->code,
                    'discount' => $discount
                ];
            }
        }

        // Keep only valid ones in session
        session(['applied_coupons' => collect($validCoupons)->pluck('id')->toArray()]);

        return view('shop.cart', compact('cart', 'subtotal', 'validCoupons', 'totalDiscount'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id'      => 'required|exists:products,id',
            'variant_id'      => 'nullable|exists:product_variants,id',
            'qty'             => 'integer|min:1',
            'main_product_id' => 'nullable|exists:products,id',
        ]);

        $product = Product::findOrFail($request->product_id);
        $variant = null;
        if ($request->filled('variant_id')) {
            $variant = \App\Models\ProductVariant::find($request->variant_id);
        }

        $price = $variant ? ($variant->price ?? $product->price) : $product->price;
        $isCombo = false;

        // Xử lý giá giảm khi mua cùng (Frequently Bought Together)
        if ($request->filled('main_product_id') && $request->main_product_id != $product->id) {
            $mainProduct = Product::find($request->main_product_id);
            if ($mainProduct) {
                $combo = $mainProduct->activeCombos()->where('combo_product_id', $product->id)->first();
                if ($combo) {
                    $isCombo = true;
                    $discountType = $combo->pivot->discount_type;
                    $discountValue = $combo->pivot->discount_value;
                    
                    if ($discountType === 'percent') {
                        $price = $price * (1 - ($discountValue / 100));
                    } else {
                        $price = max(0, $price - $discountValue);
                    }
                }
            }
        }

        $cart = session('cart', []);
        // Nếu là hàng mua cùng, dùng key khác để tránh gộp với hàng thường (ví dụ: product_id-variant_id-combo)
        $key = $variant ? ($product->id . '-' . $variant->id) : (string)$product->id;
        if ($isCombo) {
            $key .= '-combo';
        }

        if (isset($cart[$key])) {
            $cart[$key]['qty'] += $request->qty ?? 1;
        } else {
            $cart[$key] = [
                'id'            => $product->id,
                'variant_id'    => $variant?->id,
                'name'          => $product->name,
                'variant_label' => $variant?->label,
                'price'         => (float)$price,
                'image'         => $this->normalizeImagePath($variant?->image ?? $product->image),
                'slug'          => $product->slug,
                'qty'           => $request->qty ?? 1,
                'is_combo'      => $isCombo
            ];
        }

        session(['cart' => $cart]);

        return response()->json([
            'success' => true,
            'count'   => array_sum(array_column($cart, 'qty')),
            'cart'    => $cart
        ]);
    }

    public function remove(Request $request)
    {
        $cart = session('cart', []);
        unset($cart[$request->rowId]);
        session(['cart' => $cart]);

        return response()->json(['success' => true]);
    }

    public function update(Request $request)
    {
        $cart = session('cart', []);
        $itemSubtotalFormatted = '';
        
        if (isset($cart[$request->rowId])) {
            $cart[$request->rowId]['qty'] = max(1, (int) $request->qty);
            $itemSubtotalFormatted = number_format($cart[$request->rowId]['price'] * $cart[$request->rowId]['qty'], 0, ',', '.') . 'đ';
            session(['cart' => $cart]);
        }

        return response()->json([
            'success'                => true,
            'item_subtotal_formatted' => $itemSubtotalFormatted,
            'count'                  => array_sum(array_column($cart, 'qty'))
        ]);
    }

    public function count()
    {
        $cart = session('cart', []);
        return response()->json(['count' => array_sum(array_column($cart, 'qty'))]);
    }

    public function total()
    {
        $cart = session('cart', []);
        $subtotal = collect($cart)->sum(fn($i) => ($i['price'] ?? 0) * ($i['qty'] ?? 1));
        
        // Tính tổng tiền các sản phẩm ĐƯỢC PHÉP giảm giá (không phải combo)
        $discountableSubtotal = collect($cart)
            ->filter(fn($i) => empty($i['is_combo']))
            ->sum(fn($i) => ($i['price'] ?? 0) * ($i['qty'] ?? 1));

        $appliedCoupons = session('applied_coupons', []);
        $totalDiscount = 0;
        $validCouponIds = [];
        $validCoupons = [];

        foreach ($appliedCoupons as $couponId) {
            $coupon = \App\Models\Coupon::find($couponId);
            // Coupon chỉ áp dụng trên giá trị các sản phẩm KHÔNG PHẢI combo
            if ($coupon && $coupon->isValid($discountableSubtotal)) {
                $discount = $coupon->calculateDiscount($discountableSubtotal);
                $totalDiscount += $discount;
                $validCouponIds[] = $coupon->id;
                $validCoupons[] = [
                    'code' => $coupon->code,
                    'discount_formatted' => '-' . number_format($discount, 0, ',', '.') . 'đ'
                ];
            }
        }
        
        session(['applied_coupons' => $validCouponIds]);

        $totalValue = max(0, $subtotal - $totalDiscount);

        return response()->json([
            'subtotal'           => $subtotal,
            'subtotal_formatted' => number_format($subtotal, 0, ',', '.') . 'đ',
            'discount'           => $totalDiscount,
            'discount_formatted' => number_format($totalDiscount, 0, ',', '.') . 'đ',
            'total'              => $totalValue,
            'total_formatted'    => number_format($totalValue, 0, ',', '.') . 'đ',
            'coupons'            => $validCoupons
        ]);
    }

    public function applyCoupon(Request $request)
    {
        $request->validate(['code' => 'required']);
        
        $coupon = \App\Models\Coupon::where('code', $request->code)->first();
        
        if (!$coupon) {
            return response()->json(['success' => false, 'message' => 'Mã giảm giá không tồn tại.']);
        }

        $appliedCoupons = session('applied_coupons', []);
        if (in_array($coupon->id, $appliedCoupons)) {
            return response()->json(['success' => false, 'message' => 'Mã giảm giá này đã được áp dụng.']);
        }

        $cart = session('cart', []);
        // Tính tổng tiền các sản phẩm ĐƯỢC PHÉP giảm giá (không phải combo)
        $discountableSubtotal = collect($cart)
            ->filter(fn($i) => empty($i['is_combo']))
            ->sum(fn($i) => ($i['price'] ?? 0) * ($i['qty'] ?? 1));

        $reason = $coupon->getInvalidReason($discountableSubtotal);
        if ($reason) {
            $msg = match($reason) {
                'Tạm dừng' => 'Mã giảm giá này đã bị tạm dừng.',
                'Chưa đến ngày' => 'Mã giảm giá chưa đến ngày sử dụng.',
                'Hết hạn' => 'Mã giảm giá đã hết hạn.',
                'Hết lượt dùng' => 'Mã giảm giá đã hết lượt sử dụng.',
                'Chưa đạt tối thiểu' => 'Đơn hàng chưa đạt giá trị tối thiểu ' . number_format($coupon->min_order_value, 0, ',', '.') . 'đ để áp dụng mã này.',
                default => 'Mã giảm giá không hợp lệ.'
            };
            return response()->json(['success' => false, 'message' => $msg]);
        }

        $appliedCoupons[] = $coupon->id;
        session(['applied_coupons' => $appliedCoupons]);

        return response()->json([
            'success' => true,
            'message' => 'Áp dụng mã giảm giá thành công!',
            'coupon' => $coupon
        ]);
    }

    public function removeCoupon(Request $request)
    {
        $couponId = $request->coupon_id;
        $appliedCoupons = session('applied_coupons', []);
        
        if ($couponId) {
            $appliedCoupons = array_filter($appliedCoupons, fn($id) => $id != $couponId);
            session(['applied_coupons' => array_values($appliedCoupons)]);
            return response()->json(['success' => true, 'message' => 'Đã gỡ mã giảm giá.']);
        }

        session()->forget('applied_coupons');
        return response()->json(['success' => true, 'message' => 'Đã gỡ tất cả mã giảm giá.']);
    }

    public function clear()
    {
        session()->forget('cart');
        return response()->json(['success' => true]);
    }

    public function dropdown()
    {
        return response(view('layouts.partials.cart-dropdown')->render());
    }

    private function normalizeImagePath(?string $image): ?string
    {
        if (!$image) return null;
        
        // If it's already a full URL, return as is
        if (str_starts_with($image, 'http')) {
            // But if it contains /storage/, we might want to normalize it to a relative path for consistency,
            // or just leave it. Let's keep /storage/ if it's there.
            if (str_contains($image, '/storage/')) {
                return 'storage/' . preg_replace('#^.*/storage/#', '', $image);
            }
            return $image;
        }

        // If it already starts with storage/, just return it
        if (str_starts_with($image, 'storage/')) {
            return $image;
        }

        // If it's a media path but missing storage/, add it
        if (str_starts_with($image, 'media/')) {
            return 'storage/' . $image;
        }

        return $image;
    }
}
