<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ApiController;
use App\Http\Controllers\API\ApiUserController;
use App\Http\Controllers\API\ApiOrderController;
use App\Http\Controllers\API\ApiProductController;
use App\Http\Controllers\API\ApiUserAddressController;
use App\Http\Controllers\API\ApiPaymentMethodController;
use App\Http\Controllers\API\ApiShippingMethodController;
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
Route::put('/edit-category/{categoryId}', action: [ApiProductCategoryController::class, 'editCategory'])->name(name: 'editCategory');
Route::delete('/category/{categoryId}', action: [ApiProductCategoryController::class, 'deleteCategory'])->name(name: 'deleteCategory');

// Product Route
Route::get('/all-products', action: [ApiProductController::class, 'getAllProducts'])->name(name: 'getAllProducts');
Route::post('/create-product', action: [ApiProductController::class, 'createProduct'])->name(name: 'createProduct');
Route::put('/edit-product/{productId}', action: [ApiProductController::class, 'editProduct'])->name(name: 'editProduct');
Route::delete('/product/{productId}', action: [ApiProductController::class, 'deleteProduct'])->name(name: 'deleteProduct');

// Shipping Method Route
Route::get('/all-shipping-methods', action: [ApiShippingMethodController::class, 'getAllShippingMethod'])->name(name: 'getAllShippingMethod');
Route::post('/create-shipping-method', action: [ApiShippingMethodController::class, 'createShippingMethod'])->name(name: 'createShippingMethod');
Route::put('/shipping-method/{shippingMethodId}', action: [ApiShippingMethodController::class, 'editShippingMethod'])->name(name: 'editShippingMethod');
Route::delete('/shipping-method/{shippingMethodId}', action: [ApiShippingMethodController::class, 'deleteShippingMethod'])->name(name: 'deleteShippingMethod');

// Payment Method Route
Route::get('/all-payment-methods', action: [ApiPaymentMethodController::class, 'getAllPaymentMethod'])->name(name: 'getAllPaymentMethod');
Route::post('/create-payment-method', action: [ApiPaymentMethodController::class, 'createPaymentMethod'])->name(name: 'createPaymentMethod');
Route::put('/payment-method/{paymentMethodId}', action: [ApiPaymentMethodController::class, 'editPaymentMethod'])->name(name: 'editPaymentMethod');
Route::delete('/payment-method/{paymentMethodId}', action: [ApiPaymentMethodController::class, 'deletePaymentMethod'])->name(name: 'deletePaymentMethod');
Route::put('/payment-method/status/{paymentMethodId}', action: [ApiPaymentMethodController::class, 'updatePaymentStatusMethod'])->name(name: 'updatePaymentStatusMethod');

// Order Route
Route::get('/all-orders', action: [ApiOrderController::class, 'getAllOrders'])->name(name: 'getAllOrders');
Route::post('/create-order', action: [ApiOrderController::class, 'createOrder'])->name(name: 'createOrder');
Route::post('/order/status', action: [ApiOrderController::class, 'getOrderStatus'])->name(name: 'getOrderStatus');
Route::post('/order/user/{userId}', action: [ApiOrderController::class, 'getOrderByUser'])->name(name: 'getOrderByUser');
Route::put('/order/status/{orderId}', action: [ApiOrderController::class, 'editOrderStatus'])->name(name: 'editOrderStatus');

// User Address Route
Route::post('/create-user-address', action: [ApiUserAddressController::class, 'createUserAddress'])->name(name: 'createUserAddress');
Route::get('/user-address/{userId}', action: [ApiUserAddressController::class, 'getUserAddress'])->name(name: 'getUserAddress');