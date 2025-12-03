<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Shop;
use App\Models\Product;
use App\Models\Food;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Tag(
 *     name="Reviews",
 *     description="Review management endpoints"
 * )
 */
class ReviewController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/reviews",
     *     summary="Create a new review",
     *     description="Create a review for a shop, product, or food item",
     *     operationId="createReview",
     *     tags={"Reviews"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"rating"},
     *             @OA\Property(property="shop_id", type="integer", nullable=true, example=1, description="Shop ID (required if product_id and food_id are not provided)"),
     *             @OA\Property(property="product_id", type="integer", nullable=true, example=null, description="Product ID (optional)"),
     *             @OA\Property(property="food_id", type="integer", nullable=true, example=null, description="Food ID (optional)"),
     *             @OA\Property(property="rating", type="integer", minimum=1, maximum=5, example=5, description="Rating from 1 to 5"),
     *             @OA\Property(property="comment", type="string", nullable=true, maxLength=1000, example="Great food! Highly recommended.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Review created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Review created successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="user_id", type="integer", example=1),
     *                 @OA\Property(property="shop_id", type="integer", nullable=true, example=1),
     *                 @OA\Property(property="product_id", type="integer", nullable=true, example=null),
     *                 @OA\Property(property="food_id", type="integer", nullable=true, example=null),
     *                 @OA\Property(property="rating", type="integer", example=5),
     *                 @OA\Property(property="comment", type="string", nullable=true, example="Great food! Highly recommended."),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time"),
     *                 @OA\Property(property="user", type="object"),
     *                 @OA\Property(property="shop", type="object", nullable=true),
     *                 @OA\Property(property="product", type="object", nullable=true),
     *                 @OA\Property(property="food", type="object", nullable=true)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation failed"),
     *             @OA\Property(property="errors", type="object")
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
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'shop_id' => 'required_without_all:product_id,food_id|nullable|exists:shops,id',
                'product_id' => 'nullable|exists:products,id',
                'food_id' => 'nullable|exists:foods,id',
                'rating' => 'required|integer|min:1|max:5',
                'comment' => 'nullable|string|max:1000',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $user = auth()->user();

            // Create review
            $review = Review::create([
                'user_id' => $user->id,
                'shop_id' => $request->shop_id,
                'product_id' => $request->product_id,
                'food_id' => $request->food_id,
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);

            // Load relationships
            $review->load(['user', 'shop', 'product', 'food']);

            return response()->json([
                'success' => true,
                'message' => 'Review created successfully',
                'data' => $review,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create review',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/reviews",
     *     summary="Get all reviews",
     *     description="Retrieve a list of reviews with optional filtering",
     *     operationId="getReviews",
     *     tags={"Reviews"},
     *     @OA\Parameter(
     *         name="shop_id",
     *         in="query",
     *         description="Filter by shop ID",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="product_id",
     *         in="query",
     *         description="Filter by product ID",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="food_id",
     *         in="query",
     *         description="Filter by food ID",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="rating",
     *         in="query",
     *         description="Filter by rating (1-5)",
     *         required=false,
     *         @OA\Schema(type="integer", minimum=1, maximum=5)
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of items per page",
     *         required=false,
     *         @OA\Schema(type="integer", default=15)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Reviews retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Reviews retrieved successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="items",
     *                     type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="user_id", type="integer", example=1),
     *                         @OA\Property(property="shop_id", type="integer", nullable=true, example=1),
     *                         @OA\Property(property="product_id", type="integer", nullable=true, example=null),
     *                         @OA\Property(property="food_id", type="integer", nullable=true, example=null),
     *                         @OA\Property(property="rating", type="integer", example=5),
     *                         @OA\Property(property="comment", type="string", nullable=true, example="Great food!"),
     *                         @OA\Property(property="created_at", type="string", format="date-time"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time"),
     *                         @OA\Property(property="user", type="object"),
     *                         @OA\Property(property="shop", type="object", nullable=true),
     *                         @OA\Property(property="product", type="object", nullable=true),
     *                         @OA\Property(property="food", type="object", nullable=true)
     *                     )
     *                 ),
     *                 @OA\Property(property="pagination", type="object")
     *             )
     *         )
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->get('per_page', 15);
            $shopId = $request->get('shop_id');
            $productId = $request->get('product_id');
            $foodId = $request->get('food_id');
            $rating = $request->get('rating');

            $query = Review::with(['user', 'shop', 'product', 'food']);

            if ($shopId) {
                $query->where('shop_id', $shopId);
            }

            if ($productId) {
                $query->where('product_id', $productId);
            }

            if ($foodId) {
                $query->where('food_id', $foodId);
            }

            if ($rating) {
                $query->where('rating', $rating);
            }

            $reviews = $query->latest()->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Reviews retrieved successfully',
                'data' => [
                    'items' => $reviews->items(),
                    'pagination' => [
                        'current_page' => $reviews->currentPage(),
                        'per_page' => $reviews->perPage(),
                        'total' => $reviews->total(),
                        'last_page' => $reviews->lastPage(),
                        'from' => $reviews->firstItem(),
                        'to' => $reviews->lastItem(),
                        'has_more_pages' => $reviews->hasMorePages(),
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve reviews',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/reviews/{id}",
     *     summary="Get review by ID",
     *     description="Retrieve a specific review by its ID",
     *     operationId="getReviewById",
     *     tags={"Reviews"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Review ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Review retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Review retrieved successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="user_id", type="integer", example=1),
     *                 @OA\Property(property="shop_id", type="integer", nullable=true, example=1),
     *                 @OA\Property(property="product_id", type="integer", nullable=true, example=null),
     *                 @OA\Property(property="food_id", type="integer", nullable=true, example=null),
     *                 @OA\Property(property="rating", type="integer", example=5),
     *                 @OA\Property(property="comment", type="string", nullable=true, example="Great food!"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time"),
     *                 @OA\Property(property="user", type="object"),
     *                 @OA\Property(property="shop", type="object", nullable=true),
     *                 @OA\Property(property="product", type="object", nullable=true),
     *                 @OA\Property(property="food", type="object", nullable=true)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Review not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Review not found")
     *         )
     *     )
     * )
     */
    public function show(int $id): JsonResponse
    {
        try {
            $review = Review::with(['user', 'shop', 'product', 'food'])->find($id);

            if (!$review) {
                return response()->json([
                    'success' => false,
                    'message' => 'Review not found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Review retrieved successfully',
                'data' => $review,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve review',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/reviews/{id}",
     *     summary="Update a review",
     *     description="Update an existing review (only by the review owner)",
     *     operationId="updateReview",
     *     tags={"Reviews"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Review ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="rating", type="integer", minimum=1, maximum=5, example=4),
     *             @OA\Property(property="comment", type="string", nullable=true, maxLength=1000, example="Updated comment")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Review updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Review updated successfully"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden - Not the review owner",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="You can only update your own reviews")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Review not found"
     *     )
     * )
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $review = Review::find($id);

            if (!$review) {
                return response()->json([
                    'success' => false,
                    'message' => 'Review not found',
                ], 404);
            }

            $user = auth()->user();

            // Check if user owns the review
            if ($review->user_id !== $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'You can only update your own reviews',
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'rating' => 'sometimes|required|integer|min:1|max:5',
                'comment' => 'nullable|string|max:1000',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $review->update($request->only(['rating', 'comment']));
            $review->load(['user', 'shop', 'product', 'food']);

            return response()->json([
                'success' => true,
                'message' => 'Review updated successfully',
                'data' => $review,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update review',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/reviews/{id}",
     *     summary="Delete a review",
     *     description="Delete an existing review (only by the review owner)",
     *     operationId="deleteReview",
     *     tags={"Reviews"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Review ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Review deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Review deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden - Not the review owner",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="You can only delete your own reviews")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Review not found"
     *     )
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $review = Review::find($id);

            if (!$review) {
                return response()->json([
                    'success' => false,
                    'message' => 'Review not found',
                ], 404);
            }

            $user = auth()->user();

            // Check if user owns the review
            if ($review->user_id !== $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'You can only delete your own reviews',
                ], 403);
            }

            $review->delete();

            return response()->json([
                'success' => true,
                'message' => 'Review deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete review',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

