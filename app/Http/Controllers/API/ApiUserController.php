<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApiUserController extends Controller
{
    // Get All Users
    public function getAllUsers() {
        $user = User::get();

        if (!$user) {
            return response()->json([
                'status' => 'failed',
                'message' => 'No Users Found!'
            ], 404);
        };

        return response()->json([
            'status' => 'success',
            'count' => count($user),
            'data' => $user
        ], 200);
    }

    // Edit Users
    public function editUser(int $userId, Request $request) {
        $user = User::find($userId);

        if (!$user) {
            return response()->json([
                'status' => 'failed',
                'message' => 'No User found!'
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
            ], 404);
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
}
