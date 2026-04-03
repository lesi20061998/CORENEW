<?php

namespace App\Http\Controllers;

use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'receiver_name'  => 'required|string|max:100',
            'receiver_phone' => 'required|string|max:20',
            'province_code'  => 'required',
            'ward_code'      => 'required',
            'province_name'  => 'required',
            'district_name'  => 'required',
            'ward_name'      => 'required',
            'address_detail' => 'required',
            'full_address'   => 'nullable|string',
        ]);

        $user = Auth::user();

        // Construct full_address if missing
        $fullAddress = $request->full_address;
        if (empty($fullAddress)) {
            $fullAddress = implode(', ', array_filter([
                $request->address_detail,
                $request->ward_name,
                $request->district_name,
                $request->province_name
            ]));
        }

        // If this is the first address, make it default
        $isDefault = $user->addresses()->count() === 0 || $request->has('is_default');

        if ($isDefault) {
            $user->addresses()->update(['is_default' => false]);
        }

        $user->addresses()->create([
            'receiver_name'  => $request->receiver_name,
            'receiver_phone' => $request->receiver_phone,
            'province_code'  => $request->province_code,
            'ward_code'      => $request->ward_code,
            'province_name'  => $request->province_name,
            'district_name'  => $request->district_name,
            'ward_name'      => $request->ward_name,
            'address_detail' => $request->address_detail,
            'full_address'   => $fullAddress,
            'is_default'     => $isDefault,
        ]);

        return back()->with('success', 'Đã thêm địa chỉ mới thành công.');
    }

    public function setDefault($id)
    {
        $user = Auth::user();
        $address = $user->addresses()->findOrFail($id);

        $user->addresses()->update(['is_default' => false]);
        $address->update(['is_default' => true]);

        return back()->with('success', 'Đã đặt địa chỉ mặc định.');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $address = $user->addresses()->findOrFail($id);

        if ($address->is_default && $user->addresses()->count() > 1) {
            $address->delete();
            // Set another one as default
            $user->addresses()->first()->update(['is_default' => true]);
        } else {
            $address->delete();
        }

        return back()->with('success', 'Đã xóa địa chỉ.');
    }
}
