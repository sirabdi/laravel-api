<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ApiController;
use App\Http\Controllers\API\ApiUserController;
use App\Http\Controllers\API\ApiProductCategoryController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Test Route
Route::get('/test', action: [ApiController::class, 'test'])->name(name: 'test');

// Auth Route
Route::post('/register', action: [ApiController::class, 'register'])->name(name: 'register');
Route::post('/login', action: [ApiController::class, 'login'])->name(name: 'login');

// Users Route
Route::get('/all-users', action: [ApiUserController::class, 'getAllUsers'])->name(name: 'getAllUsers');
Route::put('/user/{userId}', action: [ApiUserController::class, 'editUser'])->name(name: 'editUser');
Route::delete('/user/{userId}', action: [ApiUserController::class, 'deleteUser'])->name(name: 'deleteUser');

// Product Category Route
Route::get('/all-categories', action: [ApiProductCategoryController::class, 'getAllCategories'])->name(name: 'getAllCategories');
Route::post('/create-category', action: [ApiProductCategoryController::class, 'createCategory'])->name(name: 'createCategory');
Route::post('/edit-category/{categoryId}', action: [ApiProductCategoryController::class, 'editCategory'])->name(name: 'editCategory');
