<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use App\Models\Pavilion;
use App\Models\Review;
use App\Models\Shop;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    /**
     * Get products for a shop
     * Note: This method is deprecated. Use ProductController::shopProducts() instead.
     * The route /api/shops/{shop}/products is now handled by ProductController.
     */
    public function products(Request $request, int $shopId): JsonResponse
    {
        try {
            $shop = Shop::find($shopId);

            if (!$shop) {
                return response()->json([
                    'success' => false,
                    'message' => 'Shop not found',
                ], 404);
            }

            $products = $shop->products()->with('tags')->get();

            return response()->json([
                'success' => true,
                'message' => 'Products retrieved successfully',
                'data' => $products,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve products',
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/pavilions/{pavilion}/shops",
     *     summary="Get shops for a pavilion",
     *     description="Retrieve all shops for a specific pavilion with the last 2 reviews embedded (no pagination)",
     *     operationId="getPavilionShops",
     *     tags={"Shop"},
     *     @OA\Parameter(
     *         name="pavilion",
     *         in="path",
     *         required=true,
     *         description="Pavilion ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Shops retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Shops retrieved successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Cultural Shop"),
     *                     @OA\Property(property="description", type="string", example="Traditional crafts and souvenirs"),
     *                     @OA\Property(property="type", type="string", example="shop"),
     *                     @OA\Property(property="pavilion_id", type="integer", example=1),
     *                     @OA\Property(property="average_rating", type="number", format="float", nullable=true, example=4.5),
     *                     @OA\Property(property="rating_count", type="integer", example=10),
     *                     @OA\Property(property="rating_distribution", type="object", example={"5": 6, "4": 3, "3": 1}),
     *                     @OA\Property(
     *                         property="offers",
     *                         type="array",
     *                         description="List of active offers for this shop",
     *                         @OA\Items(
     *                             @OA\Property(property="id", type="integer", example=1),
     *                             @OA\Property(property="title", type="string", example="Special Offer"),
     *                             @OA\Property(property="shop_name", type="string", example="Shop Name"),
     *                             @OA\Property(property="type", type="string", example="percent", description="Discount type: percent or fixed"),
     *                             @OA\Property(property="value", type="number", format="float", example=20.00),
     *                             @OA\Property(property="start_date", type="string", format="date-time", example="2025-11-01T00:00:00Z"),
     *                             @OA\Property(property="end_date", type="string", format="date-time", example="2025-12-31T23:59:59Z")
     *                         )
     *                     ),
     *                     @OA\Property(
     *                         property="reviews",
     *                         type="array",
     *                         @OA\Items(
     *                             @OA\Property(property="id", type="integer", example=1),
     *                             @OA\Property(property="user_id", type="integer", example=1),
     *                             @OA\Property(property="shop_id", type="integer", example=1),
     *                             @OA\Property(property="product_id", type="integer", nullable=true, example=null),
     *                             @OA\Property(property="rating", type="integer", example=5),
     *                             @OA\Property(property="comment", type="string", nullable=true, example="Great products!"),
     *                             @OA\Property(property="created_at", type="string", format="date-time"),
     *                             @OA\Property(property="updated_at", type="string", format="date-time"),
     *                             @OA\Property(
     *                                 property="user",
     *                                 type="object",
     *                                 @OA\Property(property="id", type="integer", example=1),
     *                                 @OA\Property(property="first_name", type="string", nullable=true, example="John"),
     *                                 @OA\Property(property="last_name", type="string", nullable=true, example="Doe"),
     *                                 @OA\Property(property="name", type="string", example=""),
     *                                 @OA\Property(property="email", type="string", nullable=true, example="john@example.com"),
     *                                 @OA\Property(property="phone", type="string", nullable=true, example="+1234567890")
     *                             )
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Pavilion not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Pavilion not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Failed to retrieve shops")
     *         )
     *     )
     * )
     */
    public function pavilionShops(int $pavilionId): JsonResponse
    {
        try {
            $pavilion = Pavilion::find($pavilionId);

            if (!$pavilion) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pavilion not found',
                ], 404);
            }

            $shops = $pavilion->shops()->get();

            $shopIds = $shops->pluck('id');
            $now = Carbon::now();

            if ($shopIds->isNotEmpty()) {
                $allReviews = Review::whereIn('shop_id', $shopIds)
                    ->with('user:id,first_name,last_name,email,phone')
                    ->orderBy('created_at', 'desc')
                    ->get()
                    ->groupBy('shop_id')
                    ->map(function ($shopReviews) {
                        return $shopReviews->take(2)->values();
                    });

                // Get all active offers for all shops
                $allOffers = Offer::whereIn('shop_id', $shopIds)
                    ->where('start_at', '<=', $now)
                    ->where('end_at', '>=', $now)
                    ->with('shop')
                    ->get()
                    ->groupBy('shop_id');

                $shops->each(function ($shop) use ($allReviews, $allOffers) {
                    $reviews = $allReviews->get($shop->id, collect());
                    $shop->setRelation('reviews', $reviews);
                    $reviewCount = $reviews->count();
                    if ($reviewCount > 0) {
                        $shop->average_rating = round($reviews->avg('rating'), 2);
                        $shop->rating_count = $reviewCount;

                        $distribution = $reviews->groupBy('rating')
                            ->map(function ($items) {
                                return $items->count();
                            })
                            ->sortKeysDesc();
                        $shop->rating_distribution = $distribution;
                    } else {
                        $shop->average_rating = null;
                        $shop->rating_count = 0;
                        $shop->rating_distribution = (object)[];
                    }

                    // Add offers for this shop
                    $shopOffers = $allOffers->get($shop->id, collect())->map(function ($offer) use ($shop) {
                        return [
                            'id' => $offer->id,
                            'title' => $offer->title,
                            'type' => $offer->discount_type,
                            'value' => $offer->value,
                            'start_date' => $offer->start_at ? $offer->start_at->toIso8601String() : null,
                            'end_date' => $offer->end_at ? $offer->end_at->toIso8601String() : null,
                        ];
                    });
                    $shop->offers = $shopOffers;
                });
            } else {
                $shops->each(function ($shop) {
                    $shop->setRelation('reviews', collect());
                    $shop->average_rating = null;
                    $shop->rating_count = 0;
                    $shop->rating_distribution = (object)[];
                    $shop->offers = collect([]);
                });
            }

            return response()->json([
                'success' => true,
                'message' => 'Shops retrieved successfully',
                'data' => $shops,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve shops',
            ], 500);
        }
    }
}

