<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/shops/{shop}/products",
     *     summary="Get products for a shop",
     *     description="Retrieve a list of products belonging to a specific shop with optional pagination",
     *     operationId="getShopProducts",
     *     tags={"Shop"},
     *     @OA\Parameter(
     *         name="shop",
     *         in="path",
     *         required=true,
     *         description="Shop ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         required=false,
     *         description="Number of items per page (omit for full list)",
     *         @OA\Schema(type="integer", minimum=1, maximum=100, default=15)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Products retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Products retrieved successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="items",
     *                     type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="name", type="string", example="Traditional Coffee"),
     *                         @OA\Property(property="description", type="string", nullable=true, example="Freshly brewed coffee"),
     *                         @OA\Property(property="price", type="number", format="float", example=4.99),
     *                         @OA\Property(property="is_food", type="boolean", example=true),
     *                         @OA\Property(property="image_url", type="string", nullable=true, example="https://example.com/storage/products/coffee.png"),
     *                         @OA\Property(
     *                             property="tags",
     *                             type="array",
     *                             @OA\Items(
     *                                 @OA\Property(property="id", type="integer", example=1),
     *                                 @OA\Property(property="name", type="string", example="Hot Drinks")
     *                             )
     *                         )
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="pagination",
     *                     type="object",
     *                     nullable=true,
     *                     @OA\Property(property="current_page", type="integer", example=1),
     *                     @OA\Property(property="per_page", type="integer", example=15),
     *                     @OA\Property(property="total", type="integer", example=30),
     *                     @OA\Property(property="last_page", type="integer", example=2),
     *                     @OA\Property(property="from", type="integer", example=1),
     *                     @OA\Property(property="to", type="integer", example=15),
     *                     @OA\Property(property="has_more_pages", type="boolean", example=true)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Shop not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Shop not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation failed"),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="per_page",
     *                     type="array",
     *                     @OA\Items(type="string", example="The per page field must be between 1 and 100.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Failed to retrieve products")
     *         )
     *     )
     * )
     */
    public function products(Request $request, int $shopId): JsonResponse
    {
        try {
            $validator = \Validator::make($request->all(), [
                'per_page' => 'nullable|integer|min:1|max:100',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $shop = Shop::find($shopId);

            if (!$shop) {
                return response()->json([
                    'success' => false,
                    'message' => 'Shop not found',
                ], 404);
            }

            $query = $shop->products()->with('tags');

            $perPage = $request->get('per_page');
            if ($perPage) {
                $products = $query->paginate($perPage);

                return response()->json([
                    'success' => true,
                    'message' => 'Products retrieved successfully',
                    'data' => [
                        'items' => $products->items(),
                        'pagination' => [
                            'current_page' => $products->currentPage(),
                            'per_page' => $products->perPage(),
                            'total' => $products->total(),
                            'last_page' => $products->lastPage(),
                            'from' => $products->firstItem(),
                            'to' => $products->lastItem(),
                            'has_more_pages' => $products->hasMorePages(),
                        ],
                    ],
                ]);
            }

            $products = $query->get();

            return response()->json([
                'success' => true,
                'message' => 'Products retrieved successfully',
                'data' => [
                    'items' => $products,
                    'pagination' => null,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve products',
            ], 500);
        }
    }
}

