<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function page()
    {
        $cart  = session('cart', []);
        $total = collect($cart)->sum(fn($i) => ($i['price'] ?? 0) * ($i['qty'] ?? 1));
        return view('cart.index', compact('cart', 'total'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'nullable|exists:product_variants,id',
            'qty'        => 'integer|min:1'
        ]);

        $product = Product::findOrFail($request->product_id);
        $variant = null;
        if ($request->filled('variant_id')) {
            $variant = \App\Models\ProductVariant::find($request->variant_id);
        }

        $cart = session('cart', []);
        $key  = $variant ? ($product->id . '-' . $variant->id) : (string)$product->id;

        if (isset($cart[$key])) {
            $cart[$key]['qty'] += $request->qty ?? 1;
        } else {
            $cart[$key] = [
                'id'            => $product->id,
                'variant_id'    => $variant?->id,
                'name'          => $product->name,
                'variant_label' => $variant?->label,
                'price'         => $variant ? ($variant->price ?? $product->price) : $product->price,
                'image'         => $variant->image ?? $product->image,
                'slug'          => $product->slug,
                'qty'           => $request->qty ?? 1,
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
        if (isset($cart[$request->rowId])) {
            $cart[$request->rowId]['qty'] = max(1, (int) $request->qty);
        }
        session(['cart' => $cart]);

        return response()->json(['success' => true]);
    }

    public function count()
    {
        $cart = session('cart', []);
        return response()->json(['count' => array_sum(array_column($cart, 'qty'))]);
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
}
