<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(
 *     name="Banner",
 *     description="Home banner management endpoints"
 * )
 */
class BannerController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/banners",
     *     summary="Get all active home banners",
     *     description="Retrieve a list of all active home banners ordered by display order",
     *     operationId="getBanners",
     *     tags={"Banner"},
     *     @OA\Response(
     *         response=200,
     *         description="Banners retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Banners retrieved successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="items",
     *                     type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="title", type="string", nullable=true, example="Special Offer"),
     *                         @OA\Property(property="description", type="string", nullable=true, example="Get amazing discounts on selected items"),
     *                         @OA\Property(property="image", type="string", example="https://example.com/storage/banners/banner.jpg"),
     *                         @OA\Property(property="link", type="string", nullable=true, example="https://example.com/promotions/special-offer"),
     *                         @OA\Property(property="order", type="integer", example=1),
     *                         @OA\Property(property="created_at", type="string", format="date-time"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Failed to retrieve banners")
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        try {
            // Get active banners ordered by display order
            $banners = Banner::active()
                ->ordered()
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Banners retrieved successfully',
                'data' => [
                    'items' => $banners
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve banners',
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/banners/{id}",
     *     summary="Get banner by ID",
     *     description="Retrieve a specific banner by its ID",
     *     operationId="getBannerById",
     *     tags={"Banner"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Banner ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Banner retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Banner retrieved successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", nullable=true, example="Special Offer"),
     *                 @OA\Property(property="description", type="string", nullable=true, example="Get amazing discounts on selected items"),
     *                 @OA\Property(property="image", type="string", example="https://example.com/storage/banners/banner.jpg"),
     *                 @OA\Property(property="link", type="string", nullable=true, example="https://example.com/promotions/special-offer"),
     *                 @OA\Property(property="order", type="integer", example=1),
     *                 @OA\Property(property="is_active", type="boolean", example=true),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Banner not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Banner not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Failed to retrieve banner")
     *         )
     *     )
     * )
     */
    public function show(int $id): JsonResponse
    {
        try {
            $banner = Banner::find($id);

            if (!$banner) {
                return response()->json([
                    'success' => false,
                    'message' => 'Banner not found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Banner retrieved successfully',
                'data' => $banner,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve banner',
            ], 500);
        }
    }
}

