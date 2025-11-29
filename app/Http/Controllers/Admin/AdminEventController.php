<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventTag;
use App\Models\EventFeature;
use App\Models\Pavilion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
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

            $query = Event::with(['pavilion', 'tags', 'features']);

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
                'price' => 'nullable|numeric|min:-1',
                'start_time' => 'required|date',
                'end_time' => 'required|date|after:start_time',
                'capacity' => 'nullable|integer|min:0',
                'tags' => 'nullable|array',
                'tags.*' => 'integer|exists:event_tags,id',
                'new_tags' => 'nullable|array',
                'new_tags.*' => 'string|max:60',
                'banners' => 'nullable|array',
                'banners.*' => 'string|url|max:2048',
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
                'price' => $request->price ?? -1.00,
                'start_time' => Carbon::parse($request->start_time),
                'end_time' => Carbon::parse($request->end_time),
                'capacity' => $request->capacity,
                'banners' => $request->banners ?? [],
            ]);

            // Sync tags if provided
            $tagIds = $request->tags ?? [];

            // Create new tags if provided
            if ($request->has('new_tags') && is_array($request->new_tags)) {
                foreach ($request->new_tags as $tagName) {
                    $tagName = trim($tagName);
                    if (!empty($tagName)) {
                        $tag = EventTag::firstOrCreate(['name' => $tagName]);
                        $tagIds[] = $tag->id;
                    }
                }
            }

            // Remove duplicates and sync
            $tagIds = array_unique($tagIds);
            if (!empty($tagIds)) {
                $event->tags()->sync($tagIds);
            } else {
                $event->tags()->detach();
            }

            // Sync features if provided
            $featureIds = $request->features ?? [];

            // Create new features if provided
            if ($request->has('new_features') && is_array($request->new_features)) {
                foreach ($request->new_features as $featureName) {
                    $featureName = trim($featureName);
                    if (!empty($featureName)) {
                        $feature = EventFeature::firstOrCreate(['name' => $featureName]);
                        $featureIds[] = $feature->id;
                    }
                }
            }

            // Remove duplicates and sync
            $featureIds = array_unique($featureIds);
            if (!empty($featureIds)) {
                $event->features()->sync($featureIds);
            } else {
                $event->features()->detach();
            }

            // Load relationships
            $event->load('pavilion', 'tags', 'features');
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
                'price' => 'nullable|numeric|min:-1',
                'start_time' => 'sometimes|required|date',
                'end_time' => 'sometimes|required|date|after:start_time',
                'capacity' => 'nullable|integer|min:0',
                'tags' => 'nullable|array',
                'tags.*' => 'integer|exists:event_tags,id',
                'new_tags' => 'nullable|array',
                'new_tags.*' => 'string|max:60',
                'banners' => 'nullable|array',
                'banners.*' => 'string|url|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $updateData = [];
            foreach (['pavilion_id', 'title', 'description', 'stage', 'price', 'capacity'] as $field) {
                if ($request->has($field)) {
                    $updateData[$field] = $request->$field;
                }
            }

            // Handle banners array
            if ($request->has('banners')) {
                $updateData['banners'] = $request->banners;
            }

            if ($request->has('start_time')) {
                $updateData['start_time'] = Carbon::parse($request->start_time);
            }

            if ($request->has('end_time')) {
                $updateData['end_time'] = Carbon::parse($request->end_time);
            }

            $event->update($updateData);

            // Sync tags if provided
            $tagIds = $request->tags ?? [];

            // Create new tags if provided
            if ($request->has('new_tags') && is_array($request->new_tags)) {
                foreach ($request->new_tags as $tagName) {
                    $tagName = trim($tagName);
                    if (!empty($tagName)) {
                        $tag = EventTag::firstOrCreate(['name' => $tagName]);
                        $tagIds[] = $tag->id;
                    }
                }
            }

            // Remove duplicates and sync
            $tagIds = array_unique($tagIds);
            if (!empty($tagIds)) {
                $event->tags()->sync($tagIds);
            } else {
                $event->tags()->detach();
            }

            // Sync features if provided
            $featureIds = $request->features ?? [];

            // Create new features if provided
            if ($request->has('new_features') && is_array($request->new_features)) {
                foreach ($request->new_features as $featureName) {
                    $featureName = trim($featureName);
                    if (!empty($featureName)) {
                        $feature = EventFeature::firstOrCreate(['name' => $featureName]);
                        $featureIds[] = $feature->id;
                    }
                }
            }

            // Remove duplicates and sync
            $featureIds = array_unique($featureIds);
            if (!empty($featureIds)) {
                $event->features()->sync($featureIds);
            } else {
                $event->features()->detach();
            }

            // Load relationships
            $event->load('pavilion', 'tags', 'features');
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

    /**
     * Upload event banner image
     */
    public function uploadBanner(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'image' => 'required|file|mimes:jpeg,png,jpg,gif,svg|max:4096',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = 'event_banner_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('events/banners', $filename, 'public');
                $url = url('storage/' . $path);

                return response()->json([
                    'success' => true,
                    'message' => 'Banner uploaded successfully',
                    'data' => [
                        'url' => $url,
                        'path' => $path,
                    ],
                ], 201);
            }

            return response()->json([
                'success' => false,
                'message' => 'No image file provided',
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload banner',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

