<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class ApiPaymentMethodController extends Controller
{
    // Get All Payment Method
    public function getAllPaymentMethod() {
        $paymentMethod = PaymentMethod::get();

        if (!$paymentMethod) {
            return response()->json([
                'status' => 'failed',
                'message' => 'No Payment Method Found!'
            ], 400);
        };

        return response()->json([
            'status' => 'success',
            'count' => count($paymentMethod),
            'data' => $paymentMethod
        ], 200);
    }

    // New Payment Method
    public function createPaymentMethod(Request $request) {
        $validator = Validator::make(data: $request->all(), rules: [
            'name' => 'required',
            'method_code' => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 'failed',
                'message' => $validator->errors()
            ], 404);
        };

        $data['name'] = $request->name;
        $data['method_code'] = $request->method_code;

        PaymentMethod::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Payment Method Created Successfully!',
            'data' => $data
        ], 200);
    }

    // Edit Payment Method
    public function editPaymentMethod(int $paymentMethodId, Request $request) {
        $paymentMethod = PaymentMethod::find($paymentMethodId);

        if (!$paymentMethod) {
            return response()->json([
                'status' => 'failed',
                'message' => 'No Payment Method Found!'
            ], 404);
        };

        $validator = Validator::make(data: $request->all(), rules: [
            'name' => 'required',
            'method_code' => 'required',
        ]);

        if($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'message' => $validator->errors()
            ], 400);
        };

        $paymentMethod->name = $request->name;
        $paymentMethod->method_code = $request->method_code;

        $paymentMethod->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Payment Method Updated!',
            'data' => $paymentMethod
        ], 200);
    }

    // Delete Payment Method
    public function deletePaymentMethod(int $paymentMethodId) {
        $paymentMethod = PaymentMethod::find($paymentMethodId);

        if(!$paymentMethod) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Payment Method Id Not Found!'
            ], 404);
        }

        $paymentMethod->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Delete Data Successfully!'
        ], 200);
    }

    // Update Payment Status Method
    public function updatePaymentStatusMethod(int $paymentMethodId) {
        $paymentMethod = PaymentMethod::find($paymentMethodId);

        if(!$paymentMethod) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Payment Method Id Not Found!'
            ], 404);
        }

        $paymentMethod->status = $paymentMethod->status === 1 ? 0 : 1;
        $paymentMethod->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Payment Method Status Successfully Updated!'
        ], 200);
    }
}
