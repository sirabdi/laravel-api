<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function test() {
        return response()->json([
            'status' => 'success',
            'message' => 'This is out first api test route'
        ], 200);
    }

    // New User Register

}
