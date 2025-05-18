<?php

namespace App\Http\Controllers\API;

use Hash;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    public function test() {
        return response()->json([
            'status' => 'success',
            'message' => 'This is out first api test route'
        ], 200);
    }

    // New User Register
    public function register(Request $request) {
        $validator = Validator::make(data: $request->all(), rules: [
            'name' => 'required|min:4',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8'
        ]);

        if($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'message' => $validator->errors()
            ], 404);
        };

        $data = $request->all();
        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        $response['name'] = $user->name;
        $response['email'] = $user->email;
        $response['accessToken'] = $user->createToken('UUIKDsQ1w9RERjrSdV4y')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'User Registered Successfully!',
            'data' => $response
        ], 200);
    }

    // New User Login
    public function login(Request $request) {
        $validator = Validator::make(data: $request->all(), rules: [
            'email' => 'required',
            'password' => 'required',
        ]);

        if($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'message' => $validator->errors()
            ], 404);
        };

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();

            $response['name'] = $user->name;
            $response['email'] = $user->email;
            $response['role'] = $user->role;
            $response['accessToken'] = $user->createToken('UUIKDsQ1w9RERjrSdV4y')->plainTextToken;

            return response()->json([
                'status' => 'success',
                'message' => 'Logged Successfully!',
                'data' => $response
            ], 200);
        } else {
            return response()->json([
                'status' => 'fail',
                'message' => 'Invalid Credentials!',
            ], 404);
        }
    }

    //User Logout
    public function logout(Request $request) {
        $request->user()->tokens()->delete();

        return response()->json([
            "status" => 'success',
            'message' => 'Logout Successfully!'
        ]);
    }
}
