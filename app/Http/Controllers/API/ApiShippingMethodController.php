<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\ShippingMethod;
use Illuminate\Http\Request;

class ApiShippingMethodController extends Controller
{
    // Get All Shipping Method
    public function getAllShippingMethod() {
        $shippingMethod = ShippingMethod::get();

        if (!$shippingMethod) {
            return response()->json([
                'status' => 'failed',
                'message' => 'No Shipping Method Found!'
            ], 404);
        };

        return response()->json([
            'status' => 'success',
            'count' => count($shippingMethod),
            'data' => $shippingMethod
        ], 200);
    }

    // New Shipping Method
    public function createShippingMethod(Request $request) {
        $validator = Validator::make(data: $request->all(), rules: [
            'name' => 'required',
            'method_code' => 'required',
            'shipping_price' => 'required'
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 'failed',
                'message' => $validator->errors()
            ], 404);
        };

        $data['name'] = $request->name;
        $data['method_code'] = $request->method_code;
        $data['shipping_price'] = $request->shipping_price;

        ShippingMethod::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Shipping Method Created Successfully!',
            'data' => $data
        ], 200);
    }

    // Edit Shipping Method
    public function editShippingMethod(int $shippingMethodId, Request $request) {
        $shippingMethod = ShippingMethod::find($shippingMethodId);

        if(!$shippingMethod) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Shipping Method Id Not Found!'
            ], 404);
        }

        $validator = Validator::make(data: $request->all(), rules: [
            'name' => 'required',
            'method_code' => 'required',
            'shipping_price' => 'required'
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 'failed',
                'message' => $validator->errors()
            ], 404);
        };

        $shippingMethod->name = $request->name;
        $shippingMethod->method_code = $request->method_code;
        $shippingMethod->shipping_price = $request->shipping_price;

        $shippingMethod->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Shipping Method Updated Successfully!',
            'data' => $shippingMethod
        ], 200);
    }

    // Delete Shipping Method
    public function deleteShippingMethod(int $shippingMethodId) {
        $shippingMethod = ShippingMethod::find($shippingMethodId);

        if(!$shippingMethod) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Shipping Method Id Not Found!'
            ], 404);
        }

        $shippingMethod->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Delete Data Successfully!'
        ], 200);
    }
}
