<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('name', 'like', "%$s%")
                ->orWhere('email', 'like', "%$s%")
                ->orWhere('phone', 'like', "%$s%"));
        }
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->withCount('orders')->paginate(20)->withQueryString();
        return view('admin.users.index', compact('users'));
    }

    public function edit(User $user)
    {
        $user->load(['orders' => fn($q) => $q->latest()->limit(5)]);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone'   => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'role'    => 'required|in:admin,user',
        ]);

        $user->update($data);

        if ($request->filled('password')) {
            $request->validate(['password' => ['confirmed', \Illuminate\Validation\Rules\Password::min(8)->letters()->numbers()->symbols()]]);
            $user->update(['password' => $request->password]);
        }

        return back()->with('success', 'Đã cập nhật tài khoản.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Không thể xóa tài khoản đang đăng nhập.');
        }
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Đã xóa tài khoản.');
    }

    public function account()
    {
        $user = auth()->user();
        return view('admin.users.account', compact('user'));
    }

    public function updateAccount(Request $request)
    {
        $user = auth()->user();
        $data = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
        ]);

        $user->update($data);

        if ($request->filled('password')) {
            $request->validate(['password' => ['confirmed', \Illuminate\Validation\Rules\Password::min(8)->letters()->numbers()->symbols()]]);
            $user->update(['password' => $request->password]);
        }

        return back()->with('success', 'Đã cập nhật tài khoản.');
    }
}
