<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pavilion;
use App\Models\Shop;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

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
     *     description="Retrieve a list of all Points of Interest (pavilions, shops, and events) with lat, long, and location",
     *     operationId="getPOIs",
     *     tags={"POI"},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search term for name or title",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="type",
     *         in="query",
     *         description="Filter by POI type",
     *         required=false,
     *         @OA\Schema(type="string", enum={"pavilion", "shop", "event"})
     *     ),
     *     @OA\Parameter(
     *         name="pavilion_id",
     *         in="query",
     *         description="Filter by pavilion ID",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="POIs retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="POIs retrieved successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="type", type="string", example="pavilion", enum={"pavilion", "shop", "event"}),
     *                     @OA\Property(property="name", type="string", example="Main Pavilion"),
     *                     @OA\Property(property="lat", type="number", format="float", example=25.2048),
     *                     @OA\Property(property="long", type="number", format="float", example=55.2708),
     *                     @OA\Property(property="location", type="string", example="Main Pavilion")
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

            $pois = collect();

            // Get Pavilions
            if (!$type || $type === 'pavilion') {
                $pavilionsQuery = Pavilion::query();

                if ($search) {
                    $pavilionsQuery->where('name', 'like', "%{$search}%");
                }

                if ($pavilionId) {
                    $pavilionsQuery->where('id', $pavilionId);
                }

                $pavilions = $pavilionsQuery->get();

                $pavilions->each(function ($pavilion) use ($pois) {
                    $pois->push([
                        'id' => $pavilion->id,
                        'type' => 'pavilion',
                        'name' => $pavilion->name,
                        'lat' => $pavilion->lat !== null ? (float) $pavilion->lat : null,
                        'long' => $pavilion->lng !== null ? (float) $pavilion->lng : null,
                        'location' => $pavilion->name,
                    ]);
                });
            }

            // Get Shops
            if (!$type || $type === 'shop') {
                $shopsQuery = Shop::query();

                if ($search) {
                    $shopsQuery->where('name', 'like', "%{$search}%");
                }

                if ($pavilionId) {
                    $shopsQuery->where('pavilion_id', $pavilionId);
                }

                $shops = $shopsQuery->get();

                $shops->each(function ($shop) use ($pois) {
                    $pois->push([
                        'id' => $shop->id,
                        'type' => 'shop',
                        'name' => $shop->name,
                        'lat' => $shop->lat !== null ? (float) $shop->lat : null,
                        'long' => $shop->lng !== null ? (float) $shop->lng : null,
                        'location' => $shop->location_name ?? $shop->name,
                    ]);
                });
            }

            // Get Events (upcoming and ongoing only)
            if (!$type || $type === 'event') {
                $now = Carbon::now();
                $eventsQuery = Event::where(function ($q) use ($now) {
                    $q->where('start_time', '>=', $now)
                      ->orWhere(function ($subQ) use ($now) {
                          $subQ->where('start_time', '<=', $now)
                               ->where('end_time', '>=', $now);
                      });
                })->with('pavilion');

                if ($search) {
                    $eventsQuery->where('title', 'like', "%{$search}%");
                }

                if ($pavilionId) {
                    $eventsQuery->where('pavilion_id', $pavilionId);
                }

                $events = $eventsQuery->orderBy('start_time', 'asc')->get();

                $events->each(function ($event) use ($pois) {
                    // Use event's own lat/lng/location if available, otherwise fallback to pavilion
                    $lat = $event->lat !== null ? (float) $event->lat : null;
                    $long = $event->lng !== null ? (float) $event->lng : null;
                    $location = $event->location;

                    // Fallback to pavilion if event doesn't have location data
                    if (($lat === null || $long === null || !$location) && $event->pavilion) {
                        $pavilion = $event->pavilion;
                        if ($lat === null) {
                            $lat = $pavilion->lat !== null ? (float) $pavilion->lat : null;
                        }
                        if ($long === null) {
                            $long = $pavilion->lng !== null ? (float) $pavilion->lng : null;
                        }
                        if (!$location) {
                            $location = $event->stage ?? $pavilion->name;
                        }
                    } elseif (!$location) {
                        $location = $event->stage;
                    }

                    $pois->push([
                        'id' => $event->id,
                        'type' => 'event',
                        'name' => $event->title,
                        'lat' => $lat,
                        'long' => $long,
                        'location' => $location,
                    ]);
                });
            }

            // Sort by name
            $pois = $pois->sortBy('name')->values();

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
     *     description="Retrieve a specific Point of Interest by its ID and type. Type should be specified as query parameter: pavilion, shop, or event",
     *     operationId="getPOIById",
     *     tags={"POI"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="POI ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="type",
     *         in="query",
     *         description="Type of POI",
     *         required=true,
     *         @OA\Schema(type="string", enum={"pavilion", "shop", "event"})
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
     *                 @OA\Property(property="type", type="string", example="pavilion", enum={"pavilion", "shop", "event"}),
     *                 @OA\Property(property="name", type="string", example="Main Pavilion"),
     *                 @OA\Property(property="lat", type="number", format="float", example=25.2048),
     *                 @OA\Property(property="long", type="number", format="float", example=55.2708),
     *                 @OA\Property(property="location", type="string", example="Main Pavilion")
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
    public function show(Request $request, int $id): JsonResponse
    {
        try {
            $type = $request->get('type');

            if (!$type || !in_array($type, ['pavilion', 'shop', 'event'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Type parameter is required and must be one of: pavilion, shop, event',
                ], 400);
            }

            $poi = null;

            if ($type === 'pavilion') {
                $pavilion = Pavilion::find($id);
                if ($pavilion) {
                    $poi = [
                        'id' => $pavilion->id,
                        'type' => 'pavilion',
                        'name' => $pavilion->name,
                        'lat' => $pavilion->lat !== null ? (float) $pavilion->lat : null,
                        'long' => $pavilion->lng !== null ? (float) $pavilion->lng : null,
                        'location' => $pavilion->name,
                    ];
                }
            } elseif ($type === 'shop') {
                $shop = Shop::find($id);
                if ($shop) {
                    $poi = [
                        'id' => $shop->id,
                        'type' => 'shop',
                        'name' => $shop->name,
                        'lat' => $shop->lat !== null ? (float) $shop->lat : null,
                        'long' => $shop->lng !== null ? (float) $shop->lng : null,
                        'location' => $shop->location_name ?? $shop->name,
                    ];
                }
            } elseif ($type === 'event') {
                $event = Event::with('pavilion')->find($id);
                if ($event) {
                    // Use event's own lat/lng/location if available, otherwise fallback to pavilion
                    $lat = $event->lat !== null ? (float) $event->lat : null;
                    $long = $event->lng !== null ? (float) $event->lng : null;
                    $location = $event->location;

                    // Fallback to pavilion if event doesn't have location data
                    if (($lat === null || $long === null || !$location) && $event->pavilion) {
                        $pavilion = $event->pavilion;
                        if ($lat === null) {
                            $lat = $pavilion->lat !== null ? (float) $pavilion->lat : null;
                        }
                        if ($long === null) {
                            $long = $pavilion->lng !== null ? (float) $pavilion->lng : null;
                        }
                        if (!$location) {
                            $location = $event->stage ?? $pavilion->name;
                        }
                    } elseif (!$location) {
                        $location = $event->stage;
                    }

                    $poi = [
                        'id' => $event->id,
                        'type' => 'event',
                        'name' => $event->title,
                        'lat' => $lat,
                        'long' => $long,
                        'location' => $location,
                    ];
                }
            }

            if (!$poi) {
                return response()->json([
                    'success' => false,
                    'message' => 'POI not found',
                ], 404);
            }

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

