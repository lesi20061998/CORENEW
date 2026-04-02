<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $request->session()->regenerate();

            if ($request->expectsJson()) {
                return response()->json(['success' => true, 'redirect' => session()->pull('url.intended', route('home'))]);
            }

            return redirect()->intended(route('home'));
        }

        if ($request->expectsJson()) {
            return response()->json(['success' => false, 'message' => 'Email hoặc mật khẩu không đúng.'], 422);
        }

        return back()->withErrors(['email' => 'Email hoặc mật khẩu không đúng.'])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users',
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::min(8)->letters()->numbers()->symbols()],
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => $request->password, // Laravel 11/12 'hashed' cast will handle this automatically
        ]);

        Auth::login($user);

        return redirect()->route('home');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }

    public function profile()
    {
        $user = Auth::user();
        $orders = $user->orders()->latest()->take(10)->get();
        return view('account.profile', compact('user', 'orders'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'name'  => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        $user->update($request->only('name', 'email', 'phone', 'address'));

        return back()->with('success', 'Cập nhật thông tin thành công!');
    }

    public function orders()
    {
        $orders = Order::where('user_id', Auth::id())->latest()->paginate(10);
        return view('account.orders', compact('orders'));
    }

    public function orderDetail(Order $order)
    {
        abort_if($order->user_id !== Auth::id(), 403);
        $order->load('items');
        return view('account.order-detail', compact('order'));
    }
}
