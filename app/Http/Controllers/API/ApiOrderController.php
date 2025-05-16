<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\ShippingMethod;
use App\Models\PaymentMethod;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;

class ApiOrderController extends Controller
{
    public function createOrder(Request $request) {
        $paymentMethodId = PaymentMethod::find($request->payment_method_id);
        $shippingMethodId = ShippingMethod::find($request->shipping_method_id);

        if (!$paymentMethodId) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Payment Method Id Not Found!'
            ], 400);
        }

        if (!$shippingMethodId) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Shipping Method Id Not Found!'
            ], 400);
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
            ], 400);
        }

        // Validate all product_ids exist
        foreach ($request->cart_items as $item) {
            $product = Product::find($item['product_id']);
            if (!$product) {
                return response()->json([
                    'status' => 'failed',
                    'message' => "Product with ID {$item['product_id']} not found."
                ], 400);
            }
        }

        $data['user_id'] = $request->user_id;
        $data['shipping_method_id'] = $request->shipping_method_id;
        $data['payment_method_id'] = $request->payment_method_id;
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
            ], 400);
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

            ], 400);
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
}
