<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart;
use App\Models\User;

class ApiCartController extends Controller
{
    // New Cart
    public function createCart(Request $request) {
        $validator = Validator::make(data: $request->all(), rules: [
            'user_id' => 'required',
            'product_id' => 'required',
            'qty' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'message' => $validator->errors()
            ], 404);
        }

        $user = User::find($request->user_id);
        $product = Product::find($request->product_id);

        if (!$user) {
            return response()->json([
                'status' => 'failed',
                'message' => 'User Id Not Found!'
            ], 404);
        }

        if (!$product) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Product Id Not Found!'
            ], 404);
        }

        $finalTotalProduct = $product->total_qty - $request->qty;
        
        if ($finalTotalProduct < 0) {
            return response()->json([
                'status' => 'failed',
                'message' => 'There Is Not Enough Remaining Product!'
            ], 404);
        }

        $data['user_id'] = $request->user_id;
        $data['product_id'] = $request->product_id;
        $data['qty'] = $request->qty;

        $product->total_qty = $finalTotalProduct;

        $product->save();
        Cart::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Cart Added Successfull',
            'data' => $data
        ], 200);
    }

    // Cart Based User
    public function getCartByUser(int $userId) {
        $user = User::find($userId);

        if (!$user) {
            return response()->json([
                'status' => 'failed',
                'message' => 'User Id Not Found!'
            ], 404);
        }

        $carts = Cart::where('user_id', $userId)->get();

        if (!$carts) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Cart Data Not Found!'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Cart Data Found',
            'count' => count($carts),
            'data' => $carts
        ], 200);
    }

    // Delete Cart
    public function deteleCart(int $cartId) {
        $cart = Cart::find($cartId);
        
        if (!$cart) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Cart Id Not Found!'
            ], 404);
        }
        
        $product = Product::find($cart->product_id);

        if (!$product) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Product Id Not Found!'
            ], 404);
        }

        $product->total_qty = $product->total_qty + $cart->qty;

        $product->save();
        $cart->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Delete Data Successfully!'
        ], 200);
    }
}
