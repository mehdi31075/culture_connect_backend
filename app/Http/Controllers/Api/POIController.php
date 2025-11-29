<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\POI;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(
 *     name="POI",
 *     description="Points of Interest management endpoints"
 * )
 */
class POIController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/pois",
     *     summary="Get all POIs",
     *     description="Retrieve a list of all Points of Interest with optional filtering",
     *     operationId="getPOIs",
     *     tags={"POI"},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search term for name",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="type",
     *         in="query",
     *         description="Filter by POI type",
     *         required=false,
     *         @OA\Schema(type="string", enum={"pavilion", "stage", "food_truck", "photo_spot", "restroom", "other"})
     *     ),
     *     @OA\Parameter(
     *         name="pavilion_id",
     *         in="query",
     *         description="Filter by pavilion ID",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="shop_id",
     *         in="query",
     *         description="Filter by shop ID",
     *         required=false,
     *         @OA\Schema(type="integer")
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
     *         description="POIs retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="POIs retrieved successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="type", type="string", example="photo_spot"),
     *                     @OA\Property(property="name", type="string", example="Main Photo Spot"),
     *                     @OA\Property(property="lat", type="number", format="float", example=25.2048),
     *                     @OA\Property(property="lng", type="number", format="float", example=55.2708),
     *                     @OA\Property(property="shop_id", type="integer", nullable=true, example=null),
     *                     @OA\Property(property="pavilion_id", type="integer", nullable=true, example=1),
     *                     @OA\Property(property="pavilion", type="object", nullable=true),
     *                     @OA\Property(property="shop", type="object", nullable=true),
     *                     @OA\Property(property="created_at", type="string", format="date-time"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Failed to retrieve POIs")
     *         )
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $search = $request->get('search');
            $type = $request->get('type');
            $pavilionId = $request->get('pavilion_id');
            $shopId = $request->get('shop_id');
            $lat = $request->get('lat');
            $lng = $request->get('lng');
            $radius = $request->get('radius', 10); // Default 10km radius

            $query = POI::with(['pavilion', 'shop']);

            // Search filter
            if ($search) {
                $query->where('name', 'like', "%{$search}%");
            }

            // Type filter
            if ($type) {
                $query->where('type', $type);
            }

            // Pavilion filter
            if ($pavilionId) {
                $query->where('pavilion_id', $pavilionId);
            }

            // Shop filter
            if ($shopId) {
                $query->where('shop_id', $shopId);
            }

            // Location-based filtering (if lat, lng, and radius are provided)
            if ($lat && $lng) {
                $radius = $radius ?? 10; // Default 10km radius

                // Calculate distance using Haversine formula
                $query->selectRaw("*,
                    (6371 * acos(cos(radians(?))
                    * cos(radians(lat))
                    * cos(radians(lng) - radians(?))
                    + sin(radians(?))
                    * sin(radians(lat)))) AS distance", [$lat, $lng, $lat])
                    ->having('distance', '<=', $radius)
                    ->orderBy('distance');
            } else {
                $query->orderBy('name', 'asc');
            }

            $pois = $query->get();

            // Ensure lat and lng are returned as floats
            $pois->transform(function ($poi) {
                $poi->lat = $poi->lat !== null ? (float) $poi->lat : null;
                $poi->lng = $poi->lng !== null ? (float) $poi->lng : null;
                return $poi;
            });

            return response()->json([
                'success' => true,
                'message' => 'POIs retrieved successfully',
                'data' => $pois,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve POIs',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/pois/{id}",
     *     summary="Get POI by ID",
     *     description="Retrieve a specific Point of Interest by its ID",
     *     operationId="getPOIById",
     *     tags={"POI"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="POI ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="POI retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="POI retrieved successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="type", type="string", example="photo_spot"),
     *                 @OA\Property(property="name", type="string", example="Main Photo Spot"),
     *                 @OA\Property(property="lat", type="number", format="float", example=25.2048),
     *                 @OA\Property(property="lng", type="number", format="float", example=55.2708),
     *                 @OA\Property(property="shop_id", type="integer", nullable=true, example=null),
     *                 @OA\Property(property="pavilion_id", type="integer", nullable=true, example=1),
     *                 @OA\Property(property="pavilion", type="object", nullable=true),
     *                 @OA\Property(property="shop", type="object", nullable=true),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="POI not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="POI not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Failed to retrieve POI")
     *         )
     *     )
     * )
     */
    public function show(int $id): JsonResponse
    {
        try {
            $poi = POI::with(['pavilion', 'shop'])->find($id);

            if (!$poi) {
                return response()->json([
                    'success' => false,
                    'message' => 'POI not found',
                ], 404);
            }

            // Ensure lat and lng are returned as floats
            $poi->lat = $poi->lat !== null ? (float) $poi->lat : null;
            $poi->lng = $poi->lng !== null ? (float) $poi->lng : null;

            return response()->json([
                'success' => true,
                'message' => 'POI retrieved successfully',
                'data' => $poi,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve POI',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

