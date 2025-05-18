<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ApiController;
use App\Http\Controllers\API\ApiProductGallery;
use App\Http\Controllers\API\ApiUserController;
use App\Http\Controllers\API\ApiCartController;
use App\Http\Controllers\API\ApiOrderController;
use App\Http\Controllers\API\ApiProductController;
use App\Http\Controllers\API\ApiUserAddressController;
use App\Http\Controllers\API\ApiPaymentMethodController;
use App\Http\Controllers\API\ApiProductReviewController;
use App\Http\Controllers\API\ApiShippingMethodController;
use App\Http\Controllers\API\ApiProductCategoryController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Test Route
Route::get('/test', action: [ApiController::class, 'test'])->name(name: 'test');

// Auth Route
Route::post('/register', action: [ApiController::class, 'register'])->name(name: 'register');
Route::post('/logout', action: [ApiController::class, 'logout'])->name(name: 'logout')->middleware('auth:sanctum')->name('logout');
Route::post('/login', action: [ApiController::class, 'login'])->name(name: 'login');

// Users Route
Route::get('/all-users', action: [ApiUserController::class, 'getAllUsers'])->name(name: 'getAllUsers')->middleware(['auth:sanctum', 'admin']);
Route::put('/user/{userId}', action: [ApiUserController::class, 'editUser'])->name(name: 'editUser')->middleware(['auth:sanctum', 'admin']);
Route::delete('/user/{userId}', action: [ApiUserController::class, 'deleteUser'])->name(name: 'deleteUser')->middleware(['auth:sanctum', 'admin']);

// Product Category Route
Route::get('/all-categories', action: [ApiProductCategoryController::class, 'getAllCategories'])->name(name: 'getAllCategories');
Route::post('/create-category', action: [ApiProductCategoryController::class, 'createCategory'])->name(name: 'createCategory')->middleware('auth:sanctum');
Route::put('/edit-category/{categoryId}', action: [ApiProductCategoryController::class, 'editCategory'])->name(name: 'editCategory')->middleware('auth:sanctum');
Route::delete('/category/{categoryId}', action: [ApiProductCategoryController::class, 'deleteCategory'])->name(name: 'deleteCategory')->middleware('auth:sanctum');

// Product Route
Route::get('/all-products', action: [ApiProductController::class, 'getAllProducts'])->name(name: 'getAllProducts');
Route::post('/create-product', action: [ApiProductController::class, 'createProduct'])->name(name: 'createProduct')->middleware('auth:sanctum');
Route::put('/edit-product/{productId}', action: [ApiProductController::class, 'editProduct'])->name(name: 'editProduct')->middleware('auth:sanctum');
Route::delete('/product/{productId}', action: [ApiProductController::class, 'deleteProduct'])->name(name: 'deleteProduct')->middleware('auth:sanctum');

// Shipping Method Route
Route::get('/all-shipping-methods', action: [ApiShippingMethodController::class, 'getAllShippingMethod'])->name(name: 'getAllShippingMethod');
Route::post('/create-shipping-method', action: [ApiShippingMethodController::class, 'createShippingMethod'])->name(name: 'createShippingMethod')->middleware('auth:sanctum');
Route::put('/shipping-method/{shippingMethodId}', action: [ApiShippingMethodController::class, 'editShippingMethod'])->name(name: 'editShippingMethod')->middleware('auth:sanctum');
Route::delete('/shipping-method/{shippingMethodId}', action: [ApiShippingMethodController::class, 'deleteShippingMethod'])->name(name: 'deleteShippingMethod')->middleware('auth:sanctum');

// Payment Method Route
Route::get('/all-payment-methods', action: [ApiPaymentMethodController::class, 'getAllPaymentMethod'])->name(name: 'getAllPaymentMethod');
Route::post('/create-payment-method', action: [ApiPaymentMethodController::class, 'createPaymentMethod'])->name(name: 'createPaymentMethod')->middleware('auth:sanctum');
Route::put('/payment-method/{paymentMethodId}', action: [ApiPaymentMethodController::class, 'editPaymentMethod'])->name(name: 'editPaymentMethod')->middleware('auth:sanctum');
Route::delete('/payment-method/{paymentMethodId}', action: [ApiPaymentMethodController::class, 'deletePaymentMethod'])->name(name: 'deletePaymentMethod')->middleware('auth:sanctum');
Route::put('/payment-method/status/{paymentMethodId}', action: [ApiPaymentMethodController::class, 'updatePaymentStatusMethod'])->name(name: 'updatePaymentStatusMethod')->middleware('auth:sanctum');

// Order Route
Route::get('/all-orders', action: [ApiOrderController::class, 'getAllOrders'])->name(name: 'getAllOrders')->middleware('auth:sanctum');
Route::post('/create-order', action: [ApiOrderController::class, 'createOrder'])->name(name: 'createOrder')->middleware('auth:sanctum');
Route::post('/order/status', action: [ApiOrderController::class, 'getOrderStatus'])->name(name: 'getOrderStatus')->middleware('auth:sanctum');
Route::get('/order/{userId}', action: [ApiOrderController::class, 'getDetailOrder'])->name(name: 'getDetailOrder')->middleware('auth:sanctum');
Route::post('/order/user/{userId}', action: [ApiOrderController::class, 'getOrderByUser'])->name(name: 'getOrderByUser')->middleware('auth:sanctum');
Route::put('/order/status/{orderId}', action: [ApiOrderController::class, 'editOrderStatus'])->name(name: 'editOrderStatus')->middleware('auth:sanctum');

// User Address Route
Route::post('/create-user-address', action: [ApiUserAddressController::class, 'createUserAddress'])->name(name: 'createUserAddress')->middleware('auth:sanctum');
Route::put('/user-address/{userId}', action: [ApiUserAddressController::class, 'editUserAddress'])->name(name: 'editUserAddress')->middleware('auth:sanctum');
Route::get('/user-address/{userId}', action: [ApiUserAddressController::class, 'getUserAddress'])->name(name: 'getUserAddress')->middleware('auth:sanctum');
Route::delete('/user-address/{userId}', action: [ApiUserAddressController::class, 'deleteUserAddress'])->name(name: 'deleteUserAddress')->middleware('auth:sanctum');

// Product Review Route
Route::post('/create-product-review', action: [ApiProductReviewController::class, 'createProductReview'])->name(name: 'createProductReview')->middleware('auth:sanctum');
Route::get('/product-review/{productId}', action: [ApiProductReviewController::class, 'getReviewByProduct'])->name(name: 'getReviewByProduct');

// Product Gallery Route
Route::post('/create-product-gellery/{productId}', action: [ApiProductGallery::class, 'createGallery'])->name(name: 'createGallery');

// Cart Route
Route::post('/create-cart', action: [ApiCartController::class, 'createCart'])->name(name: 'createCart');
Route::get('/cart/{userId}', action: [ApiCartController::class, 'getCartByUser'])->name(name: 'getCartByUser');
Route::delete('/delete-cart/{cartId}', action: [ApiCartController::class, 'deteleCart'])->name(name: 'deteleCart');
