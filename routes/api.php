<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ApiController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Test Route
Route::get('/test', action: [ApiController::class, 'test'])->name(name: 'test');

// Auth Route
Route::post('/register', action: [ApiController::class, 'register'])->name(name: 'register');
Route::post('/login', action: [ApiController::class, 'login'])->name(name: 'login');

// Users Route
Route::get('/all-users', action: [ApiController::class, 'getAllUsers'])->name(name: 'getAllUsers');
Route::put('/user/{userId}', action: [ApiController::class, 'editUser'])->name(name: 'editUser');
