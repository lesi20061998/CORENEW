<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::latest()->paginate(10);
        return view('admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('admin.coupons.create');
    }

    public function store(Request $request)
    {
        // Sanitize currency fields (remove thousand separators)
        $data = $request->all();
        foreach(['value', 'min_order_value', 'max_discount_value'] as $field) {
            if (isset($data[$field]) && !is_null($data[$field])) {
                $data[$field] = str_replace('.', '', $data[$field]);
            }
        }
        $request->merge($data);

        $request->validate([
            'code'                => 'required|unique:coupons,code',
            'type'                => 'required|in:fixed,percentage',
            'value'               => 'required|numeric|min:0',
            'min_order_value'     => 'required|numeric|min:0',
            'max_discount_value'  => 'nullable|numeric|min:0',
            'start_date'          => 'nullable|date',
            'end_date'            => 'nullable|date|after_or_equal:start_date',
            'usage_limit'         => 'nullable|integer|min:1',
            'usage_limit_per_user'=> 'required|integer|min:1',
        ]);

        Coupon::create($request->all());

        return redirect()->route('admin.coupons.index')->with('success', 'Mã giảm giá đã được tạo thành công.');
    }

    public function edit(Coupon $coupon)
    {
        return view('admin.coupons.edit', compact('coupon'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        // Sanitize currency fields (remove thousand separators)
        $data = $request->all();
        foreach(['value', 'min_order_value', 'max_discount_value'] as $field) {
            if (isset($data[$field]) && !is_null($data[$field])) {
                $data[$field] = str_replace('.', '', $data[$field]);
            }
        }
        $request->merge($data);

        $request->validate([
            'code'                => 'required|unique:coupons,code,' . $coupon->id,
            'type'                => 'required|in:fixed,percentage',
            'value'               => 'required|numeric|min:0',
            'min_order_value'     => 'required|numeric|min:0',
            'max_discount_value'  => 'nullable|numeric|min:0',
            'start_date'          => 'nullable|date',
            'end_date'            => 'nullable|date|after_or_equal:start_date',
            'usage_limit'         => 'nullable|integer|min:0',
            'usage_limit_per_user'=> 'required|integer|min:1',
        ]);

        $coupon->update($request->all());

        return redirect()->route('admin.coupons.index')->with('success', 'Mã giảm giá đã được cập nhật.');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return redirect()->route('admin.coupons.index')->with('success', 'Mã giảm giá đã được xóa.');
    }
}
