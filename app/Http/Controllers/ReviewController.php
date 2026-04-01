<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id'    => 'required|exists:products,id',
            'rating'        => 'required|integer|min:1|max:5',
            'customer_name' => 'required|string|max:255',
            'customer_email'=> 'required|email|max:255',
            'comment'       => 'required|string|min:10',
            'title'         => 'nullable|string|max:255',
            'review_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        // Handle forbidden keywords
        $forbidden = setting('review_forbidden_keywords', 'tệ, kém, ghét');
        $keywords = $forbidden ? array_map('trim', explode(',', $forbidden)) : [];
        foreach ($keywords as $kw) {
            if ($kw && stripos($request->comment, $kw) !== false) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nội dung chứa từ khóa không cho phép!'
                ], 422);
            }
        }

        $review = new Review();
        $review->product_id = $request->product_id;
        $review->rating = $request->rating;
        $review->customer_name = $request->customer_name;
        $review->customer_email = $request->customer_email;
        $review->user_id = auth()->id();
        $review->comment = $request->comment;
        $review->status = 'approved';
        $review->ip_address = $request->ip();
        
        // Simple verified purchase check: if user is logged in and has a completed order with this product
        if (auth()->check()) {
            $hasOrdered = \App\Models\Order::where('user_id', auth()->id())
                ->where('status', 'completed')
                ->whereHas('items', function($q) use($request) {
                    $q->where('product_id', $request->product_id);
                })->exists();
            $review->is_verified_purchase = $hasOrdered;
        }

        $imagePaths = [];
        if ($request->hasFile('review_images')) {
            foreach ($request->file('review_images') as $file) {
                $path = $file->store('reviews', 'public');
                $imagePaths[] = 'storage/' . $path; // Store relative path
            }
        }
        $review->images = $imagePaths;
        $review->save();

        return response()->json([
            'success' => true,
            'message' => $review->status == 'approved' ? 'Cảm ơn bạn đã đánh giá!' : 'Đánh giá đã được gửi và đang chờ duyệt.'
        ]);
    }
}
