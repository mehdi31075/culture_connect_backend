<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Food;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(
 *     name="Food",
 *     description="Food items management endpoints"
 * )
 */
class FoodController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/foods",
     *     summary="Get all foods",
     *     description="Retrieve a list of all food items with optional filtering",
     *     operationId="getFoods",
     *     tags={"Food"},
     *     @OA\Parameter(
     *         name="shop_id",
     *         in="query",
     *         description="Filter by shop ID",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="tag",
     *         in="query",
     *         description="Filter by tag ID",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="trending",
     *         in="query",
     *         description="Filter trending foods only",
     *         required=false,
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Parameter(
     *         name="available",
     *         in="query",
     *         description="Filter available foods only",
     *         required=false,
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search term for name or description",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Foods retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Foods retrieved successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="shop_id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Viral TikTok Shawarma"),
     *                     @OA\Property(property="description", type="string", example="The shawarma that broke the internet!"),
     *                     @OA\Property(property="price", type="number", format="float", example=18.00),
     *                     @OA\Property(
     *                         property="images",
     *                         type="array",
     *                         @OA\Items(type="string", example="https://example.com/storage/foods/food1.jpg")
     *                     ),
     *                     @OA\Property(property="views_count", type="integer", example=89300),
     *                     @OA\Property(property="likes_count", type="integer", example=15400),
     *                     @OA\Property(property="comments_count", type="integer", example=1234),
     *                     @OA\Property(property="is_trending", type="boolean", example=true),
     *                     @OA\Property(property="trending_position", type="integer", nullable=true, example=1),
     *                     @OA\Property(property="trending_score", type="number", format="float", nullable=true, example=98.00),
     *                     @OA\Property(property="preparation_time", type="integer", nullable=true, example=8),
     *                     @OA\Property(property="is_available", type="boolean", example=true),
     *                     @OA\Property(property="average_rating", type="number", format="float", example=4.5),
     *                     @OA\Property(property="reviews_count", type="integer", example=2847),
     *                     @OA\Property(property="shop", type="object"),
     *                     @OA\Property(property="tags", type="array", @OA\Items(type="object"))
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $shopId = $request->get('shop_id');
            $tagId = $request->get('tag');
            $trending = $request->get('trending');
            $available = $request->get('available');
            $search = $request->get('search');

            $query = Food::with(['shop', 'tags']);

            // Filter by shop
            if ($shopId) {
                $query->where('shop_id', $shopId);
            }

            // Filter by tag
            if ($tagId) {
                $query->whereHas('tags', function ($q) use ($tagId) {
                    $q->where('food_tags.id', $tagId);
                });
            }

            // Filter trending
            if ($trending !== null) {
                $query->where('is_trending', filter_var($trending, FILTER_VALIDATE_BOOLEAN));
            }

            // Filter available
            if ($available !== null) {
                $query->where('is_available', filter_var($available, FILTER_VALIDATE_BOOLEAN));
            }

            // Search filter
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            }

            // Order by trending position if trending, otherwise by name
            if ($trending) {
                $query->orderBy('trending_position', 'asc')
                      ->orderBy('trending_score', 'desc');
            } else {
                $query->orderBy('name', 'asc');
            }

            $foods = $query->get();

            // Increment views for each food and add calculated fields
            $foods->each(function ($food) {
                $food->incrementViews(); // Auto-increment views on fetch
                $food->average_rating = $food->average_rating;
                $food->reviews_count = $food->reviews_count;
                $food->likes_count = $food->likes_count; // Calculated from food_likes
                $food->comments_count = $food->comments_count; // Calculated from reviews
                $food->is_liked = $food->is_liked; // Check if user liked this food
            });

            return response()->json([
                'success' => true,
                'message' => 'Foods retrieved successfully',
                'data' => $foods,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve foods',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/shops/{shop}/foods",
     *     summary="Get all foods for a shop",
     *     description="Retrieve all food items for a specific shop",
     *     operationId="getShopFoods",
     *     tags={"Food"},
     *     @OA\Parameter(
     *         name="shop",
     *         in="path",
     *         description="Shop ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Foods retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Foods retrieved successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="shop_id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Viral TikTok Shawarma"),
     *                     @OA\Property(property="description", type="string", example="The shawarma that broke the internet!"),
     *                     @OA\Property(property="price", type="number", format="float", example=18.00),
     *                     @OA\Property(
     *                         property="images",
     *                         type="array",
     *                         @OA\Items(type="string", example="https://example.com/storage/foods/food1.jpg")
     *                     ),
     *                     @OA\Property(property="views_count", type="integer", example=89300),
     *                     @OA\Property(property="likes_count", type="integer", example=15400),
     *                     @OA\Property(property="comments_count", type="integer", example=1234),
     *                     @OA\Property(property="is_trending", type="boolean", example=true),
     *                     @OA\Property(property="trending_position", type="integer", nullable=true, example=1),
     *                     @OA\Property(property="trending_score", type="number", format="float", nullable=true, example=98.00),
     *                     @OA\Property(property="preparation_time", type="integer", nullable=true, example=8),
     *                     @OA\Property(property="is_available", type="boolean", example=true),
     *                     @OA\Property(property="average_rating", type="number", format="float", example=4.5),
     *                     @OA\Property(property="reviews_count", type="integer", example=2847),
     *                     @OA\Property(property="shop", type="object"),
     *                     @OA\Property(property="tags", type="array", @OA\Items(type="object"))
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Shop not found"
     *     )
     * )
     */
    public function shopFoods(Request $request, int $shopId): JsonResponse
    {
        try {
            $shop = Shop::find($shopId);

            if (!$shop) {
                return response()->json([
                    'success' => false,
                    'message' => 'Shop not found',
                ], 404);
            }

            $foods = Food::where('shop_id', $shopId)
                ->with(['tags'])
                ->orderBy('is_trending', 'desc')
                ->orderBy('trending_position', 'asc')
                ->orderBy('name', 'asc')
                ->get();

            // Increment views for each food and add calculated fields
            $foods->each(function ($food) {
                $food->incrementViews(); // Auto-increment views on fetch
                $food->average_rating = $food->average_rating;
                $food->reviews_count = $food->reviews_count;
                $food->likes_count = $food->likes_count; // Calculated from food_likes
                $food->comments_count = $food->comments_count; // Calculated from reviews
                $food->is_liked = $food->is_liked; // Check if user liked this food
            });

            return response()->json([
                'success' => true,
                'message' => 'Foods retrieved successfully',
                'data' => $foods,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve foods',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/foods/{id}",
     *     summary="Get food by ID",
     *     description="Retrieve a specific food item by its ID",
     *     operationId="getFoodById",
     *     tags={"Food"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Food ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Food retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Food retrieved successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="shop_id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Viral TikTok Shawarma"),
     *                 @OA\Property(property="description", type="string", example="The shawarma that broke the internet!"),
     *                 @OA\Property(property="price", type="number", format="float", example=18.00),
     *                 @OA\Property(
     *                     property="images",
     *                     type="array",
     *                     @OA\Items(type="string", example="https://example.com/storage/foods/food1.jpg")
     *                 ),
     *                 @OA\Property(property="views_count", type="integer", example=89300),
     *                 @OA\Property(property="likes_count", type="integer", example=15400),
     *                 @OA\Property(property="comments_count", type="integer", example=1234),
     *                 @OA\Property(property="is_trending", type="boolean", example=true),
     *                 @OA\Property(property="trending_position", type="integer", nullable=true, example=1),
     *                 @OA\Property(property="trending_score", type="number", format="float", nullable=true, example=98.00),
     *                 @OA\Property(property="preparation_time", type="integer", nullable=true, example=8),
     *                 @OA\Property(property="is_available", type="boolean", example=true),
     *                 @OA\Property(property="average_rating", type="number", format="float", example=4.5),
     *                 @OA\Property(property="reviews_count", type="integer", example=2847),
     *                 @OA\Property(property="shop", type="object"),
     *                 @OA\Property(property="tags", type="array", @OA\Items(type="object"))
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Food not found"
     *     )
     * )
     */
    public function show(int $id): JsonResponse
    {
        try {
            $food = Food::with(['shop', 'tags'])->find($id);

            if (!$food) {
                return response()->json([
                    'success' => false,
                    'message' => 'Food not found',
                ], 404);
            }

            // Increment views and add calculated fields
            $food->incrementViews(); // Auto-increment views on fetch
            $food->average_rating = $food->average_rating;
            $food->reviews_count = $food->reviews_count;
            $food->likes_count = $food->likes_count; // Calculated from food_likes
            $food->comments_count = $food->comments_count; // Calculated from reviews
            $food->is_liked = $food->is_liked; // Check if user liked this food

            return response()->json([
                'success' => true,
                'message' => 'Food retrieved successfully',
                'data' => $food,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve food',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/foods/{id}/like",
     *     summary="Like or unlike a food",
     *     description="Toggle like status for a food item. If already liked, it will be unliked.",
     *     operationId="toggleFoodLike",
     *     tags={"Food"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Food ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Like status toggled successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Food liked successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="is_liked", type="boolean", example=true),
     *                 @OA\Property(property="likes_count", type="integer", example=15400)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Food not found"
     *     )
     * )
     */
    public function toggleLike(Request $request, int $id): JsonResponse
    {
        try {
            $food = Food::find($id);

            if (!$food) {
                return response()->json([
                    'success' => false,
                    'message' => 'Food not found',
                ], 404);
            }

            $user = auth()->user();
            $like = \App\Models\FoodLike::where('user_id', $user->id)
                ->where('food_id', $food->id)
                ->first();

            if ($like) {
                // Unlike
                $like->delete();
                $isLiked = false;
                $message = 'Food unliked successfully';
            } else {
                // Like
                \App\Models\FoodLike::create([
                    'user_id' => $user->id,
                    'food_id' => $food->id,
                ]);
                $isLiked = true;
                $message = 'Food liked successfully';
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => [
                    'is_liked' => $isLiked,
                    'likes_count' => $food->fresh()->likes_count,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to toggle like',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

