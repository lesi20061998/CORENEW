<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CustomerActionController extends Controller
{
    /**
     * Add product to Wishlist (Session based)
     */
    public function addToWishlist(Request $request)
    {
        $productId = $request->input('product_id');
        $wishlist = session()->get('wishlist', []);

        if (!in_array($productId, $wishlist)) {
            $wishlist[] = $productId;
            session()->put('wishlist', $wishlist);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Đã thêm vào danh sách yêu thích',
            'count' => count($wishlist)
        ]);
    }

    /**
     * Display Wishlist Page
     */
    public function wishlistIndex()
    {
        $wishlistIds = session()->get('wishlist', []);
        $products = Product::with('categories')->whereIn('id', $wishlistIds)->get();

        return view('pages.wishlist', compact('products'));
    }

    /**
     * Remove from Wishlist
     */
    public function removeFromWishlist(Request $request)
    {
        $productId = $request->input('product_id');
        $wishlist = session()->get('wishlist', []);

        if (($key = array_search($productId, $wishlist)) !== false) {
            unset($wishlist[$key]);
            session()->put('wishlist', array_values($wishlist));
        }

        return redirect()->back()->with('success', 'Đã xóa sản phẩm khỏi danh sách yêu thích.');
    }

    /**
     * Add product to Compare (Session based)
     */
    public function addToCompare(Request $request)
    {
        $productId = $request->input('product_id');
        $compare = session()->get('compare', []);

        if (!in_array($productId, $compare)) {
            if (count($compare) >= 4) {
                array_shift($compare); // Limit to 4 items
            }
            $compare[] = $productId;
            session()->put('compare', $compare);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Đã thêm vào danh sách so sánh',
            'count' => count($compare)
        ]);
    }

    /**
     * Display Comparison Page
     */
    public function compareIndex()
    {
        $compareIds = session()->get('compare', []);
        
        if (empty($compareIds)) {
            return redirect()->route('shop.index')->with('info', 'Danh sách so sánh đang trống. Hãy thêm ít nhất 2 sản phẩm để so sánh.');
        }

        $products = Product::with('categories')->whereIn('id', $compareIds)->get();

        return view('shop.compare', compact('products'));
    }

    /**
     * Remove from Compare
     */
    public function removeFromCompare(Request $request)
    {
        $productId = $request->input('product_id');
        $compare = session()->get('compare', []);

        if (($key = array_search($productId, $compare)) !== false) {
            unset($compare[$key]);
            session()->put('compare', array_values($compare));
        }

        return redirect()->back()->with('success', 'Đã xóa sản phẩm khỏi danh sách so sánh.');
    }

    /**
     * Get Product Quick View Content
     */
    public function getQuickView($id)
    {
        $product = Product::with('categories')->find($id);
        
        if (!$product) {
            return response()->json(['error' => 'Sản phẩm không tồn tại'], 404);
        }

        // Return a partial view for the modal content
        $html = view('shop.partials.quick_view', compact('product'))->render();

        return response()->json([
            'html' => $html
        ]);
    }
}
