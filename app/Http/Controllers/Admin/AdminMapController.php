<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pavilion;
use App\Models\Shop;
use App\Models\Event;
use Illuminate\Http\JsonResponse;

class AdminMapController extends Controller
{
    /**
     * Get all POIs for the map (Pavilions, Shops, Events)
     */
    public function pois(): JsonResponse
    {
        try {
            // Get pavilions with coordinates
            $pavilions = Pavilion::whereNotNull('lat')
                ->whereNotNull('lng')
                ->get()
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

            // Get shops with coordinates
            $shops = Shop::whereNotNull('lat')
                ->whereNotNull('lng')
                ->with('pavilion')
                ->get()
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

            // Get events through their pavilions
            $events = Event::with('pavilion')
                ->whereHas('pavilion', function ($query) {
                    $query->whereNotNull('lat')
                          ->whereNotNull('lng');
                })
                ->get()
                ->map(function ($event) {
                    $pavilion = $event->pavilion;
                    return [
                        'id' => $event->id,
                        'type' => 'event',
                        'name' => $event->title,
                        'lat' => (float) $pavilion->lat,
                        'lng' => (float) $pavilion->lng,
                        'description' => $event->description,
                        'stage' => $event->stage,
                        'start_time' => $event->start_time ? $event->start_time->toIso8601String() : null,
                        'end_time' => $event->end_time ? $event->end_time->toIso8601String() : null,
                        'pavilion_id' => $event->pavilion_id,
                        'pavilion_name' => $pavilion->name,
                    ];
                });

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

