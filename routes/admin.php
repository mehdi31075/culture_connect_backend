<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminPavilionController;
use App\Http\Controllers\Admin\AdminBannerController;
use App\Http\Controllers\Admin\AdminShopController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminProductTagController;
use App\Http\Controllers\Admin\AdminReviewController;
use App\Http\Controllers\Admin\AdminEventController;
use App\Http\Controllers\Admin\AdminEventTagController;

/*
|--------------------------------------------------------------------------
| Admin API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "admin" middleware group. Make something great!
|
*/

// Admin authentication routes (no middleware required)
Route::prefix('admin')->group(function () {
    Route::post('login', [AdminAuthController::class, 'login']);
    Route::post('logout', [AdminAuthController::class, 'logout'])->middleware('auth:api');
    Route::get('me', [AdminAuthController::class, 'me'])->middleware('auth:api');
});

// Admin routes with authentication and staff middleware
Route::middleware(['auth:api', 'admin'])->prefix('admin')->group(function () {

    // Dashboard routes
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/analytics/users', [DashboardController::class, 'userAnalytics']);
    Route::get('/analytics/orders', [DashboardController::class, 'orderAnalytics']);
    Route::get('/analytics/events', [DashboardController::class, 'eventAnalytics']);
    Route::get('/system/health', [DashboardController::class, 'systemHealth']);

    // User management
    Route::get('/users', [AdminController::class, 'users']);
    Route::get('/users/{id}', [AdminController::class, 'getUser']);
    Route::put('/users/{id}', [AdminController::class, 'updateUser']);
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser']);

    // Event management
    Route::get('/events', [AdminEventController::class, 'index']);
    Route::post('/events', [AdminEventController::class, 'store']);
    Route::put('/events/{id}', [AdminEventController::class, 'update']);
    Route::delete('/events/{id}', [AdminEventController::class, 'destroy']);
    Route::post('/upload-event-banner', [AdminEventController::class, 'uploadBanner']);

    // Event tag management
    Route::get('/event-tags', [AdminEventTagController::class, 'index']);
    Route::post('/event-tags', [AdminEventTagController::class, 'store']);
    Route::put('/event-tags/{id}', [AdminEventTagController::class, 'update']);
    Route::delete('/event-tags/{id}', [AdminEventTagController::class, 'destroy']);

    // Pavilion management
    Route::get('/pavilions', [AdminController::class, 'pavilions']);
    Route::post('/pavilions', [AdminPavilionController::class, 'store']);
    Route::match(['put', 'post'], '/pavilions/{id}', [AdminPavilionController::class, 'update']);
    Route::delete('/pavilions/{id}', [AdminPavilionController::class, 'destroy']);

    // Banner management
    Route::get('/banners', [AdminBannerController::class, 'index']);
    Route::post('/banners', [AdminBannerController::class, 'store']);
    Route::put('/banners/{id}', [AdminBannerController::class, 'update']);
    Route::delete('/banners/{id}', [AdminBannerController::class, 'destroy']);

    // Shop management
    Route::get('/shops', [AdminController::class, 'shops']);
    Route::post('/shops', [AdminShopController::class, 'store']);
    Route::put('/shops/{id}', [AdminShopController::class, 'update']);
    Route::delete('/shops/{id}', [AdminShopController::class, 'destroy']);

    // Product management
    Route::get('/products', [AdminProductController::class, 'index']);
    Route::post('/products', [AdminProductController::class, 'store']);
    Route::put('/products/{id}', [AdminProductController::class, 'update']);
    Route::delete('/products/{id}', [AdminProductController::class, 'destroy']);

    // Product tag management
    Route::get('/product-tags', [AdminProductTagController::class, 'index']);
    Route::post('/product-tags', [AdminProductTagController::class, 'store']);
    Route::put('/product-tags/{id}', [AdminProductTagController::class, 'update']);
    Route::delete('/product-tags/{id}', [AdminProductTagController::class, 'destroy']);

    // Order management
    Route::get('/orders', [AdminController::class, 'orders']);
    Route::put('/orders/{id}/status', [AdminController::class, 'updateOrderStatus']);

    // Review management
    Route::get('/reviews', [AdminController::class, 'reviews']);
    Route::post('/reviews', [AdminReviewController::class, 'store']);
    Route::put('/reviews/{id}', [AdminReviewController::class, 'update']);
    Route::delete('/reviews/{id}', [AdminReviewController::class, 'destroy']);

    // Notification management
    Route::get('/notifications', [AdminController::class, 'notifications']);
    Route::post('/notifications', [AdminController::class, 'sendNotification']);
});
