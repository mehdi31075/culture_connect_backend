<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventTag;
use App\Models\EventFeature;
use App\Models\EventAttendance;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class EventController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/events",
     *     summary="Get upcoming events",
     *     description="Retrieve upcoming events with filtering by date and tags. Requires authentication. The response includes personalized fields (is_going, is_interested, has_reminder) for the authenticated user.",
     *     operationId="getEvents",
     *     tags={"Event"},
     *     security={{"bearerAuth":{}}},
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
     *         description="Tag filter: specific event tag ID (integer). If not provided, returns all events regardless of tag.",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - JWT token required"
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
                     *                     @OA\Property(property="price", type="number", format="float", example=-1.00, description="Event price in decimal format (-1.00 indicates free event)"),
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
                     *                     ),
                     *                     @OA\Property(
                     *                         property="features",
                     *                         type="array",
                     *                         @OA\Items(
                     *                             @OA\Property(property="id", type="integer"),
                     *                             @OA\Property(property="name", type="string")
                     *                         )
                     *                     ),
                     *                     @OA\Property(
                     *                         property="banners",
                     *                         type="array",
                     *                         description="List of banner image URLs for this event",
                     *                         @OA\Items(type="string", example="https://example.com/storage/events/banner1.jpg")
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
            $tagFilter = $request->get('tag');

            // Build base query for upcoming and ongoing events
            // Include events that:
            // 1. Start in the future (start_time >= now), OR
            // 2. Are currently ongoing (start_time <= now AND end_time >= now)
            $now = Carbon::now();
            $query = Event::where(function ($q) use ($now) {
                $q->where('start_time', '>=', $now)
                  ->orWhere(function ($subQ) use ($now) {
                      $subQ->where('start_time', '<=', $now)
                           ->where('end_time', '>=', $now);
                  });
            });

            // Eager load relationships (this doesn't filter, just loads related data)
            $query->with(['pavilion', 'tags', 'features']);

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

            // Apply tag filter by ID (if tag parameter is provided)
            // If tag parameter is not provided or is null/empty, show all events
            if ($tagFilter !== null && $tagFilter !== '' && $tagFilter !== '0') {
                $tagId = (int) $tagFilter;
                if ($tagId > 0) {
                    $query->whereHas('tags', function ($q) use ($tagId) {
                        $q->where('event_tags.id', $tagId);
                    });
                }
            } else {
                // When no tag filter, ensure we get all events (including those without tags)
                // No additional filtering needed - the query will return all events
            }

            // Execute query and get all events
            $events = $query->orderBy('start_time', 'asc')->get();

            // Ensure we're getting all events (debug: this should show all events)
            // The issue might be that one event has start_time in the past or doesn't meet the condition

            // Get authenticated user (required since route has auth:api middleware)
            $user = auth('api')->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                ], 401);
            }

            $userAttendance = collect();

            if ($user) {
                $eventIds = $events->pluck('id');
                if ($eventIds->isNotEmpty()) {
                    // Query attendance records for this user and these events
                    $userAttendance = EventAttendance::where('user_id', $user->id)
                        ->whereIn('event_id', $eventIds)
                        ->get()
                        ->keyBy('event_id');
                }
            }

            // Add confirmed attendees count and user status to each event
            $events->each(function ($event) use ($userAttendance, $user) {
                $event->confirmed_attendees_count = $event->confirmed_attendees_count;

                // Add user status flags (default to false if no user or no attendance)
                $attendance = $userAttendance->get($event->id);

                if ($attendance && $attendance->status !== null) {
                    // Use strict comparison and ensure status matches exactly
                    $status = (string)$attendance->status;
                    $event->is_going = $status === (string)EventAttendance::STATUS_GOING;
                    // If going, also considered interested. Otherwise check if status is interested
                    $event->is_interested = $status === (string)EventAttendance::STATUS_GOING
                        || $status === (string)EventAttendance::STATUS_INTERESTED;
                    $event->has_reminder = $attendance->reminder_at !== null && $attendance->reminder_at !== '';
                } else {
                    // No attendance record found, or status is null (cancelled)
                    $event->is_going = false;
                    $event->is_interested = false;
                    // Check if there's a reminder even if status is null
                    $event->has_reminder = $attendance && $attendance->reminder_at !== null && $attendance->reminder_at !== '';
                }
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
     *     summary="Mark event as going or cancel",
     *     description="Mark the authenticated user as going to a specific event, or cancel if cancel=true",
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
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             @OA\Property(property="cancel", type="boolean", description="Set to true to cancel/remove going status", example=false)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Marked as going successfully or cancelled",
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

            $cancel = filter_var($request->input('cancel', false), FILTER_VALIDATE_BOOLEAN);

            if ($cancel) {
                // Cancel: Remove going status
                $attendance = EventAttendance::where('user_id', $user->id)
                    ->where('event_id', $event->id)
                    ->where('status', EventAttendance::STATUS_GOING)
                    ->first();

                if ($attendance) {
                    // If there's a reminder, keep the record but change status to interested
                    if ($attendance->reminder_at) {
                        $attendance->status = EventAttendance::STATUS_INTERESTED;
                        $attendance->save();
                    } else {
                        // No reminder, delete the record
                        $attendance->delete();
                    }
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Going status cancelled successfully',
                ]);
            }

            // Create or update event attendance pivot record as going
            // This updates the pivot table (event_attendances) with status 'going'
            EventAttendance::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'event_id' => $event->id,
                ],
                [
                    'status' => EventAttendance::STATUS_GOING,
                    // Preserve reminder_at if it exists, don't clear it
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
     *     summary="Mark event as interested or cancel",
     *     description="Mark the authenticated user as interested in a specific event, or cancel if cancel=true",
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
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             @OA\Property(property="cancel", type="boolean", description="Set to true to cancel/remove interested status", example=false)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Marked as interested successfully or cancelled",
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

            $cancel = filter_var($request->input('cancel', false), FILTER_VALIDATE_BOOLEAN);

            if ($cancel) {
                // Cancel: Remove interested status
                $attendance = EventAttendance::where('user_id', $user->id)
                    ->where('event_id', $event->id)
                    ->where('status', EventAttendance::STATUS_INTERESTED)
                    ->first();

                if ($attendance) {
                    // If there's a reminder, keep the record but set status to null
                    if ($attendance->reminder_at) {
                        $attendance->status = null;
                        $attendance->save();
                    } else {
                        // No reminder, delete the record
                        $attendance->delete();
                    }
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Interested status cancelled successfully',
                ]);
            }

            // Create or update event attendance pivot record as interested
            // This updates the pivot table (event_attendances) with status 'interested'
            // Only set to interested if not already going (going is higher priority)
            $attendance = EventAttendance::firstOrNew([
                'user_id' => $user->id,
                'event_id' => $event->id,
            ]);

            // Only update status if not already going
            if ($attendance->status !== EventAttendance::STATUS_GOING) {
                $attendance->status = EventAttendance::STATUS_INTERESTED;
            }

            $attendance->save();

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
     *     summary="Set reminder for an event or cancel",
     *     description="Create or update a reminder for the authenticated user for a specific event, or cancel if cancel=true",
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
     *             @OA\Property(property="reminder_at", type="string", format="date-time", nullable=true, description="Custom reminder time. If not provided, defaults to 1 hour before event start time."),
     *             @OA\Property(property="cancel", type="boolean", description="Set to true to cancel/remove reminder", example=false)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Reminder set successfully or cancelled",
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

            $cancel = filter_var($request->input('cancel', false), FILTER_VALIDATE_BOOLEAN);

            if ($cancel) {
                // Cancel: Remove reminder
                $attendance = EventAttendance::where('user_id', $user->id)
                    ->where('event_id', $event->id)
                    ->first();

                if ($attendance && $attendance->reminder_at) {
                    // Clear reminder_at
                    $attendance->reminder_at = null;

                    // If no status is set, delete the record
                    if (!$attendance->status) {
                        $attendance->delete();
                    } else {
                        $attendance->save();
                    }
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Reminder cancelled successfully',
                ]);
            }

            // Calculate reminder time (default: 1 hour before event start)
            $reminderAt = $request->input('reminder_at');
            if ($reminderAt) {
                $reminderAt = Carbon::parse($reminderAt);
            } else {
                $reminderAt = Carbon::parse($event->start_time)->subHour();
            }

            // Create or update event attendance pivot record with reminder
            // This updates the pivot table (event_attendances) with reminder_at
            // Preserve existing status if any, otherwise default to interested
            $attendance = EventAttendance::firstOrNew([
                'user_id' => $user->id,
                'event_id' => $event->id,
            ]);

            // If no status is set, default to interested
            if (!$attendance->status) {
                $attendance->status = EventAttendance::STATUS_INTERESTED;
            }

            // Update reminder_at in pivot table
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

    /**
     * @OA\Get(
     *     path="/api/event-tags",
     *     summary="Get all event tags",
     *     description="Retrieve all available event tags",
     *     operationId="getEventTags",
     *     tags={"Event"},
     *     @OA\Response(
     *         response=200,
     *         description="Event tags retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Event tags retrieved successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Cultural")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function getTags(): JsonResponse
    {
        try {
            $tags = EventTag::orderBy('name')->get();

            return response()->json([
                'success' => true,
                'message' => 'Event tags retrieved successfully',
                'data' => $tags,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve event tags',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/events/{id}",
     *     summary="Get event by ID",
     *     description="Retrieve a specific event by its ID. If authenticated, includes personalized fields (is_going, is_interested, has_reminder).",
     *     operationId="getEventById",
     *     tags={"Event"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Event ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Event retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Event retrieved successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="Cultural Festival"),
     *                 @OA\Property(property="description", type="string", nullable=true, example="A celebration of cultural diversity"),
     *                 @OA\Property(property="stage", type="string", nullable=true, example="Main Stage"),
     *                 @OA\Property(property="price", type="number", format="float", example=-1.00, description="Event price in decimal format (-1.00 indicates free event)"),
     *                 @OA\Property(property="start_time", type="string", format="date-time", example="2025-12-01T18:00:00Z"),
     *                 @OA\Property(property="end_time", type="string", format="date-time", example="2025-12-01T22:00:00Z"),
     *                 @OA\Property(property="capacity", type="integer", nullable=true, example=500),
     *                 @OA\Property(property="lat", type="number", format="float", nullable=true, example=25.2048),
     *                 @OA\Property(property="lng", type="number", format="float", nullable=true, example=55.2708),
     *                 @OA\Property(property="location", type="string", nullable=true, example="Main Stage"),
     *                 @OA\Property(property="confirmed_attendees_count", type="integer", example=391),
     *                 @OA\Property(property="is_going", type="boolean", example=false, description="Whether the authenticated user marked as going (only if authenticated)"),
     *                 @OA\Property(property="is_interested", type="boolean", example=false, description="Whether the authenticated user marked as interested (only if authenticated)"),
     *                 @OA\Property(property="has_reminder", type="boolean", example=false, description="Whether the authenticated user set a reminder (only if authenticated)"),
     *                 @OA\Property(
     *                     property="pavilion",
     *                     type="object",
     *                     nullable=true,
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Main Pavilion")
     *                 ),
     *                 @OA\Property(
     *                     property="tags",
     *                     type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="name", type="string", example="Cultural")
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="features",
     *                     type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="name", type="string", example="Live Music")
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="banners",
     *                     type="array",
     *                     description="List of banner image URLs for this event",
     *                     @OA\Items(type="string", example="https://example.com/storage/events/banner1.jpg")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Event not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Event not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Failed to retrieve event")
     *         )
     *     )
     * )
     */
    public function show(int $id): JsonResponse
    {
        try {
            $event = Event::with(['pavilion', 'tags', 'features'])->find($id);

            if (!$event) {
                return response()->json([
                    'success' => false,
                    'message' => 'Event not found',
                ], 404);
            }

            // Get authenticated user (optional - endpoint doesn't require auth)
            $user = auth('api')->user();

            // Add confirmed attendees count
            $event->confirmed_attendees_count = $event->confirmed_attendees_count;

            // Add user status flags if authenticated
            if ($user) {
                $attendance = EventAttendance::where('user_id', $user->id)
                    ->where('event_id', $event->id)
                    ->first();

                if ($attendance && $attendance->status !== null) {
                    $status = (string)$attendance->status;
                    $event->is_going = $status === (string)EventAttendance::STATUS_GOING;
                    $event->is_interested = $status === (string)EventAttendance::STATUS_GOING
                        || $status === (string)EventAttendance::STATUS_INTERESTED;
                    $event->has_reminder = $attendance->reminder_at !== null && $attendance->reminder_at !== '';
                } else {
                    $event->is_going = false;
                    $event->is_interested = false;
                    $event->has_reminder = $attendance && $attendance->reminder_at !== null && $attendance->reminder_at !== '';
                }
            } else {
                // Not authenticated - don't include personalized fields
                $event->is_going = null;
                $event->is_interested = null;
                $event->has_reminder = null;
            }

            return response()->json([
                'success' => true,
                'message' => 'Event retrieved successfully',
                'data' => $event,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve event',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

