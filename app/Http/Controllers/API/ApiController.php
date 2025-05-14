<?php

namespace App\Http\Controllers\API;

use Str;
use Hash;
use App\Models\User;
use App\Models\ProductCategory;
use App\Http\Controllers\Controller;
use App\Trait\FlieUploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    use FlieUploadTrait;

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
            ], 400);
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
            ], 400);
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
            ], 400);
        }
    }

    // Get All Users
    public function getAllUsers() {
        $user = User::get();

        if (!$user) {
            return response()->json([
                'status' => 'failed',
                'message' => 'No Users Found!'
            ], 400);
        };

        return response()->json([
            'status' => 'success',
            'count' => count($user),
            'data' => $user
        ], 200);
    }

    // Get Edit Users
    public function editUser(int $userId, Request $request) {
        $user = User::find($userId);

        if (!$user) {
            return response()->json([
                'status' => 'failed',
                'message' => 'No user found!'
            ], 404);
        };

        $validator = Validator::make(data: $request->all(), rules: [
            'name' => 'required|min:4',
            'email' => 'required|email|unique:users'
        ]);

        if($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'message' => $validator->errors()
            ], 400);
        };

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'User Updated!',
            'data' => $user
        ], 200);
    }

    // Delete Users
    public function deleteUser(int $userId) {
        $user = User::find($userId);

        if (!$user) {
            return response()->json([
                'status' => 'failed',
                'message' => 'No user found!'
            ], 404);
        }

        $user->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Delete data successfully!'
        ], 200);
    }

    // New Product Category
    public function createCategory(Request $request) {
        $validator = Validator::make(data: $request->all(), rules: [
            'name' => 'required'
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 'failed',
                'message' => $validator->errors()
            ], 404);
        };

        $data['name'] = $request->name;
        $data['slug'] = Str::slug($request->name);
        $imagePath = $this->uploadImage($request, 'image');
        $data['image'] = isset($imagePath) ? $imagePath : '';

        ProductCategory::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Product Category Created Successfully!',
            'data' => $data
        ], 200);
    }
}
