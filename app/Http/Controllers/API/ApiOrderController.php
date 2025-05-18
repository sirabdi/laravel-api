<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\ShippingMethod;
use App\Models\PaymentMethod;
use App\Models\UserAddress;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class ApiOrderController extends Controller
{
    public function createOrder(Request $request) {
        $userId = User::find($request->user_id);
        $userEmail = User::where('email', $request->email)->get();
        $userAddressId = UserAddress::find($request->user_address_id);
        $paymentMethodId = PaymentMethod::find($request->payment_method_id);
        $shippingMethodId = ShippingMethod::find($request->shipping_method_id);

        Log::info($userId);
        Log::info($userEmail);

        if (!$userId || !$userEmail) {
            return response()->json([
                'status' => 'failed',
                'message' => 'User Data Not Found!'
            ], 404);
        }

        if (!$paymentMethodId) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Payment Method Id Not Found!'
            ], 404);
        }

        if (!$shippingMethodId) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Shipping Method Id Not Found!'
            ], 404);
        }

        if (!$userAddressId) {
            return response()->json([
                'status' => 'failed',
                'message' => 'User Address Id Not Found!'
            ], 404);
        }

        $validator = Validator::make(data: $request->all(), rules: [
            'user_id' => 'required',
            'shipping_method_id' => 'required',
            'payment_method_id' => 'required',
            'email' => 'required|email',
            'tax' => 'required',
            'grand_total' => 'required',
            'qty' => 'required',
            'cart_items' => 'required'
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 'failed',
                'message' => $validator->errors()
            ], 404);
        }

        // Validate all product_ids exist
        foreach ($request->cart_items as $item) {
            $product = Product::find($item['product_id']);
            if (!$product) {
                return response()->json([
                    'status' => 'failed',
                    'message' => "Product with ID {$item['product_id']} not found."
                ], 404);
            }
        }

        $data['user_id'] = $request->user_id;
        $data['shipping_method_id'] = $request->shipping_method_id;
        $data['payment_method_id'] = $request->payment_method_id;
        $data['user_address_id'] = $request->user_address_id;
        $data['email'] = $request->email;
        $data['shipping_price'] = $request->shipping_price;
        $data['tax'] = $request->tax;
        $data['grand_total'] = $request->grand_total;
        $data['qty'] = $request->qty;

        $order = Order::create($data);

        $orderId = $order->id;
        $cartItems = $request->cart_items;

        foreach($cartItems as $cartItem) {
            $orderData = [
                'order_id' => $orderId,
                'product_id' => $cartItem['product_id'],
                'price' => $cartItem['price'],
                'qty' => $cartItem['qty'],
            ];
            $orderItems[] = OrderItem::create($orderData);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'New Order Created Successfull',
            'data' => [
                'order' => $order,
                'orderItems' => $orderItems,
            ]
        ], 200);
    }

    public function getAllOrders() {
        $orders = Order::with('orderItems')->get();

        if (!$orders) {
            return response()->json([
                'status' => 'failed',
                'message' => 'No Order Found!'
            ], 404);
        };

        return response()->json([
            'status' => 'success',
            'count' => count($orders),
            'data' => $orders,
        ], 200);
    }

    public function editOrderStatus(int $orderId, Request $request) {
        $order = Order::find($orderId);

        if (!$order) {
            return response()->json([
                'status' => 'failed',
                'message' => 'No Order Found!'

            ], 404);
        };

        $validator = Validator::make(data: $request->all(), rules: [
            'type' => 'required',
            'status' => 'required'
        ]);

        if($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'message' => $validator->errors()
            ]);
        };

        if ($request->type === 'payment') {
            $order->payment_status = $request->status;
        } else {
            $order->order_status = $request->status;
        }
        $order->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Edit Order Status Successfully!'
        ]);
    }

    public function getOrderStatus(Request $request) {
        $orders = Order::where('order_status', $request->order_status)->where('payment_status', $request->payment_status)->with('orderItems')->get();

        if(!$orders) {
            return response()->json([
                'status' => 'failed',
                'message' => 'No Order Found!'
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Order Data Found!',
            'count' => count($orders),
            'data' => $orders
        ]);
    }

    public function getOrderByUser(int $userId) {
        $user = User::find($userId);

        if (!$user) {
            return response()->json([
                'status' => 'success',
                'message' => 'User Not Found!'
            ]);
        }

        $orders = Order::where('user_id', $userId)->with('orderItems')->get();

        if (!$user) {
            return response()->json([
                'status' => 'success',
                'message' => 'Order Not Found!'
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Order Data Found!',
            'count' => count($orders),
            'order' => $orders
        ]);
    }

    public function getDetailOrder(int $orderId) {
        $order = Order::where('id', $orderId)->with('orderItems')->with('userAddress')->with('paymentMethod')->with('shippingMethod')->get();

        if (!$order) {
            return response()->json([
                'status' => 'success',
                'message' => 'Order Not Found!'
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Order Detail Found!',
            'data' => $order
        ]);
    }
}
