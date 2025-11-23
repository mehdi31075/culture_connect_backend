<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventTag;
use App\Models\EventAttendance;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/events",
     *     summary="Get upcoming events",
     *     description="Retrieve upcoming events with filtering by date and tags",
     *     operationId="getEvents",
     *     tags={"Event"},
     *     @OA\Parameter(
     *         name="date_filter",
     *         in="query",
     *         required=false,
     *         description="Date filter: all_time, today, tomorrow, this_week",
     *         @OA\Schema(type="string", enum={"all_time", "today", "tomorrow", "this_week"}, default="all_time")
     *     ),
     *     @OA\Parameter(
     *         name="tag",
     *         in="query",
     *         required=false,
     *         description="Tag filter: 'all' or specific tag ID/name (Cultural, Food, Shows, etc.)",
     *         @OA\Schema(type="string", default="all")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Events retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Events retrieved successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="title", type="string", example="Cultural Festival"),
     *                     @OA\Property(property="description", type="string", nullable=true),
     *                     @OA\Property(property="stage", type="string", nullable=true),
     *                     @OA\Property(property="price", type="string", example="Free"),
     *                     @OA\Property(property="start_time", type="string", format="date-time"),
     *                     @OA\Property(property="end_time", type="string", format="date-time"),
     *                     @OA\Property(property="capacity", type="integer", nullable=true, example=500),
     *                     @OA\Property(property="confirmed_attendees_count", type="integer", example=391),
     *                     @OA\Property(property="is_going", type="boolean", example=false, description="Whether the authenticated user marked as going"),
     *                     @OA\Property(property="is_interested", type="boolean", example=false, description="Whether the authenticated user marked as interested"),
     *                     @OA\Property(property="has_reminder", type="boolean", example=false, description="Whether the authenticated user set a reminder"),
     *                     @OA\Property(property="pavilion", type="object", nullable=true),
     *                     @OA\Property(
     *                         property="tags",
     *                         type="array",
     *                         @OA\Items(
     *                             @OA\Property(property="id", type="integer"),
     *                             @OA\Property(property="name", type="string")
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $dateFilter = $request->get('date_filter', 'all_time');
            $tagFilter = $request->get('tag', 'all');

            $query = Event::with(['pavilion', 'tags'])
                ->where('start_time', '>=', Carbon::now());

            // Apply date filter
            switch ($dateFilter) {
                case 'today':
                    $query->whereDate('start_time', Carbon::today());
                    break;
                case 'tomorrow':
                    $query->whereDate('start_time', Carbon::tomorrow());
                    break;
                case 'this_week':
                    $query->whereBetween('start_time', [
                        Carbon::now()->startOfWeek(),
                        Carbon::now()->endOfWeek()
                    ]);
                    break;
                case 'all_time':
                default:
                    // No additional filter needed
                    break;
            }

            // Apply tag filter
            if ($tagFilter !== 'all') {
                // Try to find tag by ID or name
                $tag = EventTag::where('id', $tagFilter)
                    ->orWhere('name', $tagFilter)
                    ->first();

                if ($tag) {
                    $query->whereHas('tags', function ($q) use ($tag) {
                        $q->where('event_tags.id', $tag->id);
                    });
                }
            }

            $events = $query->orderBy('start_time', 'asc')->get();

            // Get authenticated user if available
            $user = auth()->user();
            $userAttendance = collect();

            if ($user) {
                $eventIds = $events->pluck('id');
                $userAttendance = EventAttendance::where('user_id', $user->id)
                    ->whereIn('event_id', $eventIds)
                    ->get()
                    ->keyBy('event_id');
            }

            // Add confirmed attendees count and user status to each event
            $events->each(function ($event) use ($userAttendance) {
                $event->confirmed_attendees_count = $event->confirmed_attendees_count;

                // Add user status flags (default to false if no user or no attendance)
                $attendance = $userAttendance->get($event->id);
                $event->is_going = $attendance ? ($attendance->status === EventAttendance::STATUS_GOING) : false;
                $event->is_interested = $attendance ? ($attendance->status === EventAttendance::STATUS_INTERESTED) : false;
                $event->has_reminder = $attendance ? ($attendance->reminder_at !== null) : false;
            });

            return response()->json([
                'success' => true,
                'message' => 'Events retrieved successfully',
                'data' => $events,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve events',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/events/{event}/mark-going",
     *     summary="Mark event as going",
     *     description="Mark the authenticated user as going to a specific event",
     *     operationId="markGoing",
     *     tags={"Event"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="event",
     *         in="path",
     *         required=true,
     *         description="Event ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Marked as going successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Marked as going successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Event not found"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function markGoing(Request $request, int $eventId): JsonResponse
    {
        try {
            $user = auth()->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                ], 401);
            }

            $event = Event::find($eventId);

            if (!$event) {
                return response()->json([
                    'success' => false,
                    'message' => 'Event not found',
                ], 404);
            }

            // Create or update event attendance as going
            EventAttendance::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'event_id' => $event->id,
                ],
                [
                    'status' => EventAttendance::STATUS_GOING,
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Marked as going successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark as going',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/events/{event}/mark-interested",
     *     summary="Mark event as interested",
     *     description="Mark the authenticated user as interested in a specific event",
     *     operationId="markInterested",
     *     tags={"Event"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="event",
     *         in="path",
     *         required=true,
     *         description="Event ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Marked as interested successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Marked as interested successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Event not found"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function markInterested(Request $request, int $eventId): JsonResponse
    {
        try {
            $user = auth()->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                ], 401);
            }

            $event = Event::find($eventId);

            if (!$event) {
                return response()->json([
                    'success' => false,
                    'message' => 'Event not found',
                ], 404);
            }

            // Create or update event attendance as interested
            EventAttendance::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'event_id' => $event->id,
                ],
                [
                    'status' => EventAttendance::STATUS_INTERESTED,
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Marked as interested successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark as interested',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/events/{event}/remind-me",
     *     summary="Set reminder for an event",
     *     description="Create or update a reminder for the authenticated user for a specific event",
     *     operationId="remindMe",
     *     tags={"Event"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="event",
     *         in="path",
     *         required=true,
     *         description="Event ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             @OA\Property(property="reminder_at", type="string", format="date-time", nullable=true, description="Custom reminder time. If not provided, defaults to 1 hour before event start time.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Reminder set successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Reminder set successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Event not found"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function remindMe(Request $request, int $eventId): JsonResponse
    {
        try {
            $user = auth()->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                ], 401);
            }

            $event = Event::find($eventId);

            if (!$event) {
                return response()->json([
                    'success' => false,
                    'message' => 'Event not found',
                ], 404);
            }

            // Calculate reminder time (default: 1 hour before event start)
            $reminderAt = $request->input('reminder_at');
            if ($reminderAt) {
                $reminderAt = Carbon::parse($reminderAt);
            } else {
                $reminderAt = Carbon::parse($event->start_time)->subHour();
            }

            // Create or update event attendance with reminder (preserve existing status if any)
            $attendance = EventAttendance::firstOrNew([
                'user_id' => $user->id,
                'event_id' => $event->id,
            ]);

            // If no status is set, default to interested
            if (!$attendance->status) {
                $attendance->status = EventAttendance::STATUS_INTERESTED;
            }

            $attendance->reminder_at = $reminderAt;
            $attendance->save();

            return response()->json([
                'success' => true,
                'message' => 'Reminder set successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to set reminder',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

