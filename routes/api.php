<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\PavilionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/**
 * @OA\Get(
 *     path="/api/user",
 *     summary="Get authenticated user",
 *     description="Get the currently authenticated user information",
 *     tags={"User"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="User information retrieved successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="id", type="integer", example=1),
 *             @OA\Property(property="email", type="string", nullable=true, example=null),
 *             @OA\Property(property="phone", type="string", nullable=true, example="+1234567890"),
 *             @OA\Property(property="name", type="string", example=""),
 *             @OA\Property(property="locale", type="string", example="en"),
 *             @OA\Property(property="is_active", type="boolean", example=true),
 *             @OA\Property(property="is_staff", type="boolean", example=false),
 *             @OA\Property(property="email_verified_at", type="string", nullable=true, example=null),
 *             @OA\Property(property="created_at", type="string", format="date-time", example="2025-09-23T23:01:56.000000Z"),
 *             @OA\Property(property="updated_at", type="string", format="date-time", example="2025-09-23T23:01:56.000000Z")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Unauthenticated.")
 *         )
 *     )
 * )
 */
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Authentication routes
Route::prefix('auth')->group(function () {
    Route::post('request-otp', [AuthController::class, 'requestOtp']);
    Route::post('verify-otp', [AuthController::class, 'verifyOtp']);
    Route::post('google', [AuthController::class, 'googleLogin']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
    Route::get('profile', [AuthController::class, 'getProfile'])->middleware('auth:api');
    Route::put('profile', [AuthController::class, 'updateProfile'])->middleware('auth:api');
});

// Protected routes
Route::middleware('auth:api')->group(function () {
    /**
     * @OA\Get(
     *     path="/api/profile",
     *     summary="Get user profile",
     *     description="Get the authenticated user's profile information",
     *     tags={"User"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Profile information retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="user", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="email", type="string", nullable=true, example=null),
     *                 @OA\Property(property="phone", type="string", nullable=true, example="+1234567890"),
     *                 @OA\Property(property="name", type="string", example=""),
     *                 @OA\Property(property="locale", type="string", example="en"),
     *                 @OA\Property(property="is_active", type="boolean", example=true),
     *                 @OA\Property(property="is_staff", type="boolean", example=false),
     *                 @OA\Property(property="email_verified_at", type="string", nullable=true, example=null),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-09-23T23:01:56.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-09-23T23:01:56.000000Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     )
     * )
     */
    Route::get('profile', function (Request $request) {
        return response()->json([
            'user' => $request->user(),
        ]);
    });
});

// Pavilion routes
Route::get('pavilions', [PavilionController::class, 'index']);
Route::get('pavilions/{id}', [PavilionController::class, 'show']);

// Banner routes
Route::get('banners', [BannerController::class, 'index']);
Route::get('banners/{id}', [BannerController::class, 'show']);
