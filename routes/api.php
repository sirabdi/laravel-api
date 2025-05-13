<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ApiController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Test Route
Route::get('/test', action: [ApiController::class, 'test'])->name(name: 'test');

// Registration Route
Route::post('/register', action: [ApiController::class, 'register'])->name(name: 'register');
