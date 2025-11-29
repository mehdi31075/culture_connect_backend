<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Offer;
use App\Models\Pavilion;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class HomeController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/stats",
     *     summary="Get home page statistics",
     *     description="Get statistics for the home page including pavilions count, live events count, and active offers count",
     *     tags={"Home"},
     *     @OA\Response(
     *         response=200,
     *         description="Statistics retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Statistics retrieved successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="pavilions_count", type="integer", example=15, description="Total number of pavilions"),
     *                 @OA\Property(property="live_events_count", type="integer", example=8, description="Number of live/upcoming events"),
     *                 @OA\Property(property="active_offers_count", type="integer", example=12, description="Number of active offers")
     *             )
     *         )
     *     )
     * )
     */
    public function stats(): JsonResponse
    {
        $now = Carbon::now();

        // Count all pavilions
        $pavilionsCount = Pavilion::count();

        // Count live events (events that are currently happening or upcoming)
        // Live events are those where:
        // - start_time <= now AND end_time >= now (currently happening)
        // - OR start_time >= now (upcoming)
        $liveEventsCount = Event::where(function ($query) use ($now) {
            $query->where('start_time', '<=', $now)
                  ->where('end_time', '>=', $now);
        })->orWhere('start_time', '>=', $now)->count();

        // Count active offers (offers that are currently active based on start_at and end_at)
        // Active offers are those where:
        // - start_at <= now AND end_at >= now
        $activeOffersCount = Offer::where('start_at', '<=', $now)
            ->where('end_at', '>=', $now)
            ->count();

        return response()->json([
            'success' => true,
            'message' => 'Statistics retrieved successfully',
            'data' => [
                'pavilions_count' => $pavilionsCount,
                'live_events_count' => $liveEventsCount,
                'active_offers_count' => $activeOffersCount,
            ],
        ]);
    }
}

