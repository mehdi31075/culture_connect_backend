<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventTag;
use App\Models\Pavilion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class AdminEventController extends Controller
{
    /**
     * Get all events with pagination
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->get('per_page', 15);
            $search = $request->get('search');

            $query = Event::with(['pavilion', 'tags']);

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            }

            $events = $query->latest('start_time')->paginate($perPage);

            // Add confirmed attendees count to each event
            $events->getCollection()->transform(function ($event) {
                $event->confirmed_attendees_count = $event->confirmed_attendees_count;
                return $event;
            });

            return response()->json([
                'success' => true,
                'data' => [
                    'items' => $events->items(),
                    'pagination' => [
                        'current_page' => $events->currentPage(),
                        'per_page' => $events->perPage(),
                        'total' => $events->total(),
                        'last_page' => $events->lastPage(),
                        'from' => $events->firstItem(),
                        'to' => $events->lastItem(),
                        'has_more_pages' => $events->hasMorePages(),
                    ]
                ]
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
     * Store a newly created event
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'pavilion_id' => 'nullable|exists:pavilions,id',
                'title' => 'required|string|max:160',
                'description' => 'nullable|string',
                'stage' => 'nullable|string|max:160',
                'price' => 'nullable|string|max:60',
                'start_time' => 'required|date',
                'end_time' => 'required|date|after:start_time',
                'capacity' => 'nullable|integer|min:0',
                'tags' => 'nullable|array',
                'tags.*' => 'integer|exists:event_tags,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $event = Event::create([
                'pavilion_id' => $request->pavilion_id,
                'title' => $request->title,
                'description' => $request->description,
                'stage' => $request->stage,
                'price' => $request->price ?? 'Free',
                'start_time' => Carbon::parse($request->start_time),
                'end_time' => Carbon::parse($request->end_time),
                'capacity' => $request->capacity,
            ]);

            // Sync tags if provided
            if ($request->has('tags')) {
                $event->tags()->sync($request->tags);
            }

            // Load relationships
            $event->load('pavilion', 'tags');
            $event->confirmed_attendees_count = $event->confirmed_attendees_count;

            return response()->json([
                'success' => true,
                'message' => 'Event created successfully',
                'data' => $event,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create event',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update an existing event
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $event = Event::find($id);

            if (!$event) {
                return response()->json([
                    'success' => false,
                    'message' => 'Event not found',
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'pavilion_id' => 'sometimes|nullable|exists:pavilions,id',
                'title' => 'sometimes|required|string|max:160',
                'description' => 'nullable|string',
                'stage' => 'nullable|string|max:160',
                'price' => 'nullable|string|max:60',
                'start_time' => 'sometimes|required|date',
                'end_time' => 'sometimes|required|date|after:start_time',
                'capacity' => 'nullable|integer|min:0',
                'tags' => 'nullable|array',
                'tags.*' => 'integer|exists:event_tags,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $updateData = $request->only(['pavilion_id', 'title', 'description', 'stage', 'price', 'capacity']);

            if ($request->has('start_time')) {
                $updateData['start_time'] = Carbon::parse($request->start_time);
            }

            if ($request->has('end_time')) {
                $updateData['end_time'] = Carbon::parse($request->end_time);
            }

            $event->update($updateData);

            // Sync tags if provided
            if ($request->has('tags')) {
                $event->tags()->sync($request->tags);
            }

            // Load relationships
            $event->load('pavilion', 'tags');
            $event->confirmed_attendees_count = $event->confirmed_attendees_count;

            return response()->json([
                'success' => true,
                'message' => 'Event updated successfully',
                'data' => $event,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update event',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete an event
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $event = Event::find($id);

            if (!$event) {
                return response()->json([
                    'success' => false,
                    'message' => 'Event not found',
                ], 404);
            }

            $event->delete();

            return response()->json([
                'success' => true,
                'message' => 'Event deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete event',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

