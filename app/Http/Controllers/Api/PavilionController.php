<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pavilion;
use Carbon\Carbon;
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
     *         description="Search term for pavilion name or description",
     *         required=false,
     *         @OA\Schema(type="string")
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
     *                         @OA\Property(
     *                             property="offers",
     *                             type="array",
     *                             description="List of active offers from all shops in this pavilion",
     *                             @OA\Items(
     *                                 @OA\Property(property="id", type="integer", example=1),
     *                                 @OA\Property(property="title", type="string", example="Special Offer"),
     *                                 @OA\Property(property="shop_name", type="string", example="Shop Name"),
     *                                 @OA\Property(property="type", type="string", example="percent", description="Discount type: percent or fixed"),
     *                                 @OA\Property(property="value", type="number", format="float", example=20.00),
     *                                 @OA\Property(property="start_date", type="string", format="date-time", example="2025-11-01T00:00:00Z"),
     *                                 @OA\Property(property="end_date", type="string", format="date-time", example="2025-12-31T23:59:59Z")
     *                             )
     *                         ),
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

            // Apply search filter (searches by pavilion name and description)
            if ($request->has('search') && !empty($request->search)) {
                $searchTerm = $request->search;
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('name', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('description', 'LIKE', "%{$searchTerm}%");
                });
            }

            // Get paginated results
            $now = Carbon::now();
            $pavilions = $query->paginate($perPage, ['*'], 'page', $page);

            // Load shops count, offers, and ensure lat/lng are numeric (not strings)
            $pavilions->getCollection()->transform(function ($pavilion) use ($now) {
                $pavilion->shops_count = $pavilion->shops()->count();
                if ($pavilion->lat !== null) {
                    $pavilion->lat = (float) $pavilion->lat;
                }
                if ($pavilion->lng !== null) {
                    $pavilion->lng = (float) $pavilion->lng;
                }

                // Get all active offers for all shops in this pavilion
                $offers = \App\Models\Offer::whereHas('shop', function ($q) use ($pavilion) {
                    $q->where('pavilion_id', $pavilion->id);
                })
                ->where('start_at', '<=', $now)
                ->where('end_at', '>=', $now)
                ->with('shop')
                ->get()
                ->map(function ($offer) {
                    return [
                        'id' => $offer->id,
                        'title' => $offer->title,
                        'shop_name' => $offer->shop ? $offer->shop->name : null,
                        'type' => $offer->discount_type,
                        'value' => $offer->value,
                        'start_date' => $offer->start_at ? $offer->start_at->toIso8601String() : null,
                        'end_date' => $offer->end_at ? $offer->end_at->toIso8601String() : null,
                    ];
                });

                $pavilion->offers = $offers;

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
     *                 @OA\Property(
     *                     property="offers",
     *                     type="array",
     *                     description="List of active offers from all shops in this pavilion",
     *                     @OA\Items(
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="title", type="string", example="Special Offer"),
     *                         @OA\Property(property="shop_name", type="string", example="Shop Name"),
     *                         @OA\Property(property="type", type="string", example="percent", description="Discount type: percent or fixed"),
     *                         @OA\Property(property="value", type="number", format="float", example=20.00),
     *                         @OA\Property(property="start_date", type="string", format="date-time", example="2025-11-01T00:00:00Z"),
     *                         @OA\Property(property="end_date", type="string", format="date-time", example="2025-12-31T23:59:59Z")
     *                     )
     *                 ),
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

            $now = Carbon::now();

            // Add shops count and ensure numeric lat/lng
            $pavilion->shops_count = $pavilion->shops()->count();
            if ($pavilion->lat !== null) {
                $pavilion->lat = (float) $pavilion->lat;
            }
            if ($pavilion->lng !== null) {
                $pavilion->lng = (float) $pavilion->lng;
            }

            // Get all active offers for all shops in this pavilion
            $offers = \App\Models\Offer::whereHas('shop', function ($q) use ($pavilion) {
                $q->where('pavilion_id', $pavilion->id);
            })
            ->where('start_at', '<=', $now)
            ->where('end_at', '>=', $now)
            ->with('shop')
            ->get()
            ->map(function ($offer) {
                return [
                    'id' => $offer->id,
                    'title' => $offer->title,
                    'shop_name' => $offer->shop ? $offer->shop->name : null,
                    'type' => $offer->discount_type,
                    'value' => $offer->value,
                    'start_date' => $offer->start_at ? $offer->start_at->toIso8601String() : null,
                    'end_date' => $offer->end_at ? $offer->end_at->toIso8601String() : null,
                ];
            });

            $pavilion->offers = $offers;

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

}
