<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\UserAddress;
use App\Models\User;
use Illuminate\Http\Request;

class ApiUserAddressController extends Controller
{
    //User Address
    public function getUserAddress(int $userId) {
        $user = User::find($userId);

        if (!$user) {
            return response()->json([
                "status" => 'failed',
                "message" => 'User Id Not Found!'
            ], 404);
        }

        $userAddresses = UserAddress::where('user_id', $userId)->get();

        if (!$userAddresses) {
            return response()->json([
                "status" => 'failed',
                "message" => 'User Address Not Found!'
            ], 404);
        }

        return response()->json([
            "status" => 'failed',
            "message" => 'User Addressess Found!',
            "count" => count($userAddresses),
            "data" => $userAddresses,
        ], 200);
    }

    // New User Address
    public function createUserAddress(Request $request) {
        $validator = Validator::make(data: $request->all(), rules: [
            'user_id' => 'required',
            'address_line_one' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'country' => 'required'
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 'failed',
                'message' => $validator->errors()
            ], 404);
        };
        
        $user = User::find($request->user_id);

        if (!$user) {
            return response()->json([
                'status' => 'failed',
                'message' => 'No User Found!'
            ]);
        }

        $data['user_id'] = $request->user_id;
        $data['address_line_one'] = $request->address_line_one;
        $data['address_line_two'] = $request->address_line_two;
        $data['city'] = $request->city;
        $data['state'] = $request->state;
        $data['zip'] = $request->zip;
        $data['country'] = $request->country;

        UserAddress::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Add User Address Succesfully!'
        ]);
    }
}
