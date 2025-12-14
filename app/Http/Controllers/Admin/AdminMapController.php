<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pavilion;
use App\Models\Shop;
use App\Models\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminMapController extends Controller
{
    /**
     * Get all POIs for the map (Pavilions, Shops, Events)
     * Supports bounding box filtering via query parameters: topLeftLat, topLeftLng, bottomRightLat, bottomRightLng
     */
    public function pois(Request $request): JsonResponse
    {
        try {
            $topLeftLat = $request->get('topLeftLat');
            $topLeftLng = $request->get('topLeftLng');
            $bottomRightLat = $request->get('bottomRightLat');
            $bottomRightLng = $request->get('bottomRightLng');

            // Build pavilions query
            $pavilionsQuery = Pavilion::whereNotNull('lat')
                ->whereNotNull('lng');

            // Apply bounding box filter if provided
            if ($topLeftLat !== null && $topLeftLng !== null && $bottomRightLat !== null && $bottomRightLng !== null) {
                $minLat = min((float) $topLeftLat, (float) $bottomRightLat);
                $maxLat = max((float) $topLeftLat, (float) $bottomRightLat);
                $minLng = min((float) $topLeftLng, (float) $bottomRightLng);
                $maxLng = max((float) $topLeftLng, (float) $bottomRightLng);
                $pavilionsQuery->whereBetween('lat', [$minLat, $maxLat])
                              ->whereBetween('lng', [$minLng, $maxLng]);
            }

            $pavilions = $pavilionsQuery->get()
                ->map(function ($pavilion) {
                    return [
                        'id' => $pavilion->id,
                        'type' => 'pavilion',
                        'name' => $pavilion->name,
                        'lat' => (float) $pavilion->lat,
                        'lng' => (float) $pavilion->lng,
                        'description' => $pavilion->description,
                        'icon' => $pavilion->icon,
                    ];
                });

            // Build shops query
            $shopsQuery = Shop::whereNotNull('lat')
                ->whereNotNull('lng')
                ->with('pavilion');

            // Apply bounding box filter if provided
            if ($topLeftLat !== null && $topLeftLng !== null && $bottomRightLat !== null && $bottomRightLng !== null) {
                $minLat = min((float) $topLeftLat, (float) $bottomRightLat);
                $maxLat = max((float) $topLeftLat, (float) $bottomRightLat);
                $minLng = min((float) $topLeftLng, (float) $bottomRightLng);
                $maxLng = max((float) $topLeftLng, (float) $bottomRightLng);
                $shopsQuery->whereBetween('lat', [$minLat, $maxLat])
                          ->whereBetween('lng', [$minLng, $maxLng]);
            }

            $shops = $shopsQuery->get()
                ->map(function ($shop) {
                    return [
                        'id' => $shop->id,
                        'type' => 'shop',
                        'name' => $shop->name,
                        'lat' => (float) $shop->lat,
                        'lng' => (float) $shop->lng,
                        'location_name' => $shop->location_name,
                        'description' => $shop->description,
                        'type_category' => $shop->type,
                        'pavilion_id' => $shop->pavilion_id,
                        'pavilion_name' => $shop->pavilion ? $shop->pavilion->name : null,
                    ];
                });

            // Build events query - use event's own lat/lng if available, otherwise pavilion's
            $eventsQuery = Event::with('pavilion')
                ->where(function ($query) {
                    // Event has its own coordinates
                    $query->where(function ($q) {
                        $q->whereNotNull('lat')
                          ->whereNotNull('lng');
                    })
                    // OR event's pavilion has coordinates
                    ->orWhereHas('pavilion', function ($q) {
                        $q->whereNotNull('lat')
                          ->whereNotNull('lng');
                    });
                });

            // Apply bounding box filter if provided
            if ($topLeftLat !== null && $topLeftLng !== null && $bottomRightLat !== null && $bottomRightLng !== null) {
                $minLat = min((float) $topLeftLat, (float) $bottomRightLat);
                $maxLat = max((float) $topLeftLat, (float) $bottomRightLat);
                $minLng = min((float) $topLeftLng, (float) $bottomRightLng);
                $maxLng = max((float) $topLeftLng, (float) $bottomRightLng);
                $eventsQuery->where(function ($query) use ($minLat, $maxLat, $minLng, $maxLng) {
                    // Events with their own coordinates
                    $query->where(function ($q) use ($minLat, $maxLat, $minLng, $maxLng) {
                        $q->whereNotNull('lat')
                          ->whereNotNull('lng')
                          ->whereBetween('lat', [$minLat, $maxLat])
                          ->whereBetween('lng', [$minLng, $maxLng]);
                    })
                    // OR events through pavilion coordinates
                    ->orWhereHas('pavilion', function ($q) use ($minLat, $maxLat, $minLng, $maxLng) {
                        $q->whereNotNull('lat')
                          ->whereNotNull('lng')
                          ->whereBetween('lat', [$minLat, $maxLat])
                          ->whereBetween('lng', [$minLng, $maxLng]);
                    });
                });
            }

            $events = $eventsQuery->get()
                ->map(function ($event) {
                    // Use event's own lat/lng if available, otherwise fallback to pavilion
                    $lat = null;
                    $lng = null;
                    $location = $event->location;

                    if ($event->lat !== null && $event->lng !== null) {
                        $lat = (float) $event->lat;
                        $lng = (float) $event->lng;
                    } elseif ($event->pavilion) {
                        $lat = $event->pavilion->lat !== null ? (float) $event->pavilion->lat : null;
                        $lng = $event->pavilion->lng !== null ? (float) $event->pavilion->lng : null;
                    }

                    if (!$location && $event->pavilion) {
                        $location = $event->stage ?? $event->pavilion->name;
                    } elseif (!$location) {
                        $location = $event->stage;
                    }

                    return [
                        'id' => $event->id,
                        'type' => 'event',
                        'name' => $event->title,
                        'lat' => $lat,
                        'lng' => $lng,
                        'location' => $location,
                        'description' => $event->description,
                        'stage' => $event->stage,
                        'start_time' => $event->start_time ? $event->start_time->toIso8601String() : null,
                        'end_time' => $event->end_time ? $event->end_time->toIso8601String() : null,
                        'pavilion_id' => $event->pavilion_id,
                        'pavilion_name' => $event->pavilion ? $event->pavilion->name : null,
                    ];
                })
                ->filter(function ($event) {
                    // Filter out events without valid coordinates
                    return $event['lat'] !== null && $event['lng'] !== null;
                })
                ->values();

            return response()->json([
                'success' => true,
                'data' => [
                    'pavilions' => $pavilions,
                    'shops' => $shops,
                    'events' => $events,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve map POIs',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

