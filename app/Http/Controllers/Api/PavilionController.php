<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pavilion;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(
 *     name="Pavilion",
 *     description="Pavilion management endpoints"
 * )
 */
class PavilionController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/pavilions",
     *     summary="Get all pavilions with pagination",
     *     description="Retrieve a paginated list of all pavilions with optional filtering",
     *     operationId="getPavilions",
     *     tags={"Pavilion"},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number for pagination",
     *         required=false,
     *         @OA\Schema(type="integer", minimum=1, default=1)
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of items per page",
     *         required=false,
     *         @OA\Schema(type="integer", minimum=1, maximum=100, default=15)
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search term for name or description",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="lat",
     *         in="query",
     *         description="Latitude for location-based filtering",
     *         required=false,
     *         @OA\Schema(type="number", format="float")
     *     ),
     *     @OA\Parameter(
     *         name="lng",
     *         in="query",
     *         description="Longitude for location-based filtering",
     *         required=false,
     *         @OA\Schema(type="number", format="float")
     *     ),
     *     @OA\Parameter(
     *         name="radius",
     *         in="query",
     *         description="Radius in kilometers for location-based filtering",
     *         required=false,
     *         @OA\Schema(type="number", format="float", minimum=0.1, maximum=100)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Pavilions retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Pavilions retrieved successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="items",
     *                     type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="name", type="string", example="Main Pavilion"),
     *                         @OA\Property(property="description", type="string", example="The main cultural pavilion"),
     *                         @OA\Property(property="icon", type="string", nullable=true, example="https://example.com/icon.png"),
     *                         @OA\Property(property="country", type="string", example="UAE"),
     *                         @OA\Property(property="lat", type="number", format="float", example=25.2048),
     *                         @OA\Property(property="lng", type="number", format="float", example=55.2708),
     *                         @OA\Property(property="open_hours", type="string", example="9:00 AM - 10:00 PM"),
     *                         @OA\Property(property="shops_count", type="integer", example=15),
     *                         @OA\Property(property="created_at", type="string", format="date-time"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time")
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="pagination",
     *                     type="object",
     *                     @OA\Property(property="current_page", type="integer", example=1),
     *                     @OA\Property(property="per_page", type="integer", example=15),
     *                     @OA\Property(property="total", type="integer", example=25),
     *                     @OA\Property(property="last_page", type="integer", example=2),
     *                     @OA\Property(property="from", type="integer", example=1),
     *                     @OA\Property(property="to", type="integer", example=15),
     *                     @OA\Property(property="has_more_pages", type="boolean", example=true)
     *                 )
     *             )
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
     *             @OA\Property(property="message", type="string", example="Failed to retrieve pavilions")
     *         )
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        try {
            // Validate request parameters
            $validator = \Validator::make($request->all(), [
                'page' => 'integer|min:1',
                'per_page' => 'integer|min:1|max:100',
                'search' => 'string|max:255',
                'lat' => 'numeric|between:-90,90',
                'lng' => 'numeric|between:-180,180',
                'radius' => 'numeric|min:0.1|max:100',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            // Get pagination parameters
            $perPage = $request->get('per_page', 15);
            $page = $request->get('page', 1);

            // Start building query
            $query = Pavilion::query();

            // Apply search filter
            if ($request->has('search') && !empty($request->search)) {
                $searchTerm = $request->search;
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('name', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('description', 'LIKE', "%{$searchTerm}%");
                });
            }

            // Apply location-based filtering
            if ($request->has('lat') && $request->has('lng')) {
                $lat = $request->lat;
                $lng = $request->lng;
                $radius = $request->get('radius', 10); // Default 10km radius

                // Calculate distance using Haversine formula
                $query->selectRaw("*,
                    (6371 * acos(cos(radians(?))
                    * cos(radians(lat))
                    * cos(radians(lng) - radians(?))
                    + sin(radians(?))
                    * sin(radians(lat)))) AS distance", [$lat, $lng, $lat])
                    ->having('distance', '<=', $radius)
                    ->orderBy('distance');
            }

            // Get paginated results
            $pavilions = $query->paginate($perPage, ['*'], 'page', $page);

            // Load shops count and ensure lat/lng are numeric (not strings)
            $pavilions->getCollection()->transform(function ($pavilion) {
                $pavilion->shops_count = $pavilion->shops()->count();
                if ($pavilion->lat !== null) {
                    $pavilion->lat = (float) $pavilion->lat;
                }
                if ($pavilion->lng !== null) {
                    $pavilion->lng = (float) $pavilion->lng;
                }
                return $pavilion;
            });

            // Format response
            $response = [
                'success' => true,
                'message' => 'Pavilions retrieved successfully',
                'data' => [
                    'items' => $pavilions->items(),
                    'pagination' => [
                        'current_page' => $pavilions->currentPage(),
                        'per_page' => $pavilions->perPage(),
                        'total' => $pavilions->total(),
                        'last_page' => $pavilions->lastPage(),
                        'from' => $pavilions->firstItem(),
                        'to' => $pavilions->lastItem(),
                        'has_more_pages' => $pavilions->hasMorePages(),
                    ]
                ]
            ];

            return response()->json($response);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve pavilions',
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/pavilions/{id}",
     *     summary="Get pavilion by ID",
     *     description="Retrieve a specific pavilion by its ID",
     *     operationId="getPavilionById",
     *     tags={"Pavilion"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Pavilion ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Pavilion retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Pavilion retrieved successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Main Pavilion"),
     *                 @OA\Property(property="description", type="string", example="The main cultural pavilion"),
     *                 @OA\Property(property="icon", type="string", nullable=true, example="https://example.com/icon.png"),
     *                 @OA\Property(property="country", type="string", example="UAE"),
     *                 @OA\Property(property="lat", type="number", format="float", example=25.2048),
     *                 @OA\Property(property="lng", type="number", format="float", example=55.2708),
     *                 @OA\Property(property="open_hours", type="string", example="9:00 AM - 10:00 PM"),
     *                 @OA\Property(property="shops_count", type="integer", example=15),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
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
     *             @OA\Property(property="message", type="string", example="Failed to retrieve pavilion")
     *         )
     *     )
     * )
     */
    public function show(int $id): JsonResponse
    {
        try {
            $pavilion = Pavilion::find($id);

            if (!$pavilion) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pavilion not found',
                ], 404);
            }

            // Add shops count and ensure numeric lat/lng
            $pavilion->shops_count = $pavilion->shops()->count();
            if ($pavilion->lat !== null) {
                $pavilion->lat = (float) $pavilion->lat;
            }
            if ($pavilion->lng !== null) {
                $pavilion->lng = (float) $pavilion->lng;
            }

            return response()->json([
                'success' => true,
                'message' => 'Pavilion retrieved successfully',
                'data' => $pavilion,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve pavilion',
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/pavilions/{id}/shops",
     *     summary="Get shops for a pavilion",
     *     description="Retrieve all shops for a specific pavilion with the last 2 reviews embedded (no pagination)",
     *     operationId="getPavilionShops",
     *     tags={"Pavilion"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Pavilion ID",
     *         required=true,
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
     *                     @OA\Property(property="created_at", type="string", format="date-time"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time"),
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
    public function getShops(int $id): JsonResponse
    {
        try {
            $pavilion = Pavilion::find($id);

            if (!$pavilion) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pavilion not found',
                ], 404);
            }

            // Get all shops for this pavilion
            $shops = $pavilion->shops()->get();

            // Load the last 2 reviews for each shop with user info
            // Note: We need to load reviews separately per shop since limit() doesn't work per parent in eager loading
            $shopIds = $shops->pluck('id');

            if ($shopIds->isNotEmpty()) {
                // Get all reviews for these shops, ordered by creation date (descending)
                $allReviews = Review::whereIn('shop_id', $shopIds)
                    ->with('user:id,first_name,last_name,name,email,phone')
                    ->orderBy('created_at', 'desc')
                    ->get()
                    ->groupBy('shop_id')
                    ->map(function ($shopReviews) {
                        // Take only the last 2 reviews per shop (already sorted by created_at desc)
                        return $shopReviews->take(2)->values();
                    });

                // Attach reviews to each shop
                $shops->each(function ($shop) use ($allReviews) {
                    $shop->setRelation('reviews', $allReviews->get($shop->id, collect()));
                });
            } else {
                // No shops, set empty reviews collection for each shop
                $shops->each(function ($shop) {
                    $shop->setRelation('reviews', collect());
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
