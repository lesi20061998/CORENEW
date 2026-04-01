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
        return view('shop.cart', compact('cart', 'total'));
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
                'image'         => $this->normalizeImagePath($variant?->image ?? $product->image),
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
        $cart  = session('cart', []);
        $total = collect($cart)->sum(fn($i) => ($i['price'] ?? 0) * ($i['qty'] ?? 1));
        return response()->json([
            'total'           => $total,
            'total_formatted' => number_format($total, 0, ',', '.') . 'đ',
        ]);
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
