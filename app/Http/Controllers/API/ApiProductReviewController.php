<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductReview;
use App\Models\Product;
use App\Models\User;

class ApiProductReviewController extends Controller
{
    // Create Review
    public function createProductReview(Request $request) {
        $userId = User::find($request->user_id);
        $productId = Product::find($request->product_id);

        if (!$userId) {
            return response()->json([
                'status' => 'failed',
                'message' => "User Id Not Found!"
            ], 404);
        }

        if (!$productId) {
            return response()->json([
                'status' => 'failed',
                'message' => "Product Id Not Found!"
            ], 404);
        }

        $validator = Validator::make(data: $request->all(), rules: [
            'user_id' => 'required',
            'product_id' => 'required',
            'rating' => 'required',
            'review' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'message' => $validator->errors()
            ], 404);
        }

        $data['user_id'] = $request->user_id;
        $data['product_id'] = $request->product_id;
        $data['rating'] = $request->rating;
        $data['review'] = $request->review;

        ProductReview::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Product Review Created Successfully!',
            'data' => $data
        ], 200);
    }

    // Get Review By Product
    public function getReviewByProduct(int $productId) {
        $product = Product::find($productId);

        if (!$product) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Product Id Not Found!'
            ], 404);
        }

        $reviews = ProductReview::where('product_id', $productId)->get();

        if (!$product) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Product Reviews Not Found!'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Product Reviews Found!',
            'count' => count($reviews),
            'data' => $reviews
        ], 200);
    }
}
