<?php
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PopupController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SlideController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\DeliveryCityController;

// Routes publiques
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{product}', [ProductController::class, 'show']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/popup', [PopupController::class, 'index']);
Route::post('/orders', [OrderController::class, 'store']);
Route::get('/settings', [SettingController::class, 'index']);
Route::get('/reviews', [ReviewController::class, 'index']);
Route::post('/reviews', [ReviewController::class, 'store']);
Route::get('/slides', [SlideController::class, 'index']);
Route::get('/banners', [BannerController::class, 'index']);
Route::get('/delivery-cities', [DeliveryCityController::class, 'index']);

// Routes protégées
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/products/reorder', [ProductController::class, 'reorder']);
    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products/{product}', [ProductController::class, 'update']);
    Route::post('/products/{product}', [ProductController::class, 'update']);
    Route::delete('/products/{product}', [ProductController::class, 'destroy']);

    Route::post('/categories', [CategoryController::class, 'store']);
    Route::put('/categories/{category}', [CategoryController::class, 'update']);
    Route::post('/categories/{category}', [CategoryController::class, 'update']);
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);
    Route::patch('/categories/{category}/visibility', [CategoryController::class, 'toggleVisibility']);

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::put('/popup', [PopupController::class, 'update']);

    Route::get('/orders', [OrderController::class, 'index']);
    Route::patch('/orders/{order}/confirm', [OrderController::class, 'confirm']);
    Route::patch('/orders/{order}/cancel', [OrderController::class, 'cancel']);
    Route::patch('/orders/{id}/deliver', [OrderController::class, 'deliver']);
    Route::patch('/orders/{id}/undeliver', [OrderController::class, 'undeliver']);

    Route::get('/admin/slides', [SlideController::class, 'adminIndex']);
    Route::post('/slides', [SlideController::class, 'store']);
    Route::post('/slides/{slide}', [SlideController::class, 'update']);
    Route::put('/slides/{slide}', [SlideController::class, 'update']);
    Route::patch('/slides/{slide}', [SlideController::class, 'update']);
    Route::delete('/slides/{slide}', [SlideController::class, 'destroy']);

    Route::get('/admin/banners', [BannerController::class, 'adminIndex']);
    Route::post('/banners', [BannerController::class, 'store']);
    Route::post('/banners/{banner}', [BannerController::class, 'update']);
    Route::delete('/banners/{banner}', [BannerController::class, 'destroy']);

    Route::post('/settings', [SettingController::class, 'update']);

    Route::get('/admin/reviews', [ReviewController::class, 'adminIndex']);
    Route::patch('/reviews/{review}/approve', [ReviewController::class, 'approve']);
    Route::patch('/reviews/{review}/reject', [ReviewController::class, 'reject']);
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy']);

    Route::get('/activity-logs', [ActivityLogController::class, 'index']);
    Route::delete('/activity-logs', [ActivityLogController::class, 'clear']);

    Route::get('/me', [UserController::class, 'me']);
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::put('/users/{user}', [UserController::class, 'update']);
    Route::delete('/users/{user}', [UserController::class, 'destroy']);

    Route::get('/admin/delivery-cities', [DeliveryCityController::class, 'adminIndex']);
    Route::post('/admin/delivery-cities', [DeliveryCityController::class, 'store']);
    Route::put('/admin/delivery-cities/{deliveryCity}', [DeliveryCityController::class, 'update']);
    Route::delete('/admin/delivery-cities/{deliveryCity}', [DeliveryCityController::class, 'destroy']);
});