<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EventFeature;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminEventFeatureController extends Controller
{
    /**
     * Get all event features
     */
    public function index(): JsonResponse
    {
        try {
            $features = EventFeature::withCount(['events as events_count'])
                ->orderBy('name')
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'items' => $features,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve event features',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created event feature
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:60|unique:event_features,name',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $feature = EventFeature::create([
                'name' => $request->name,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Event feature created successfully',
                'data' => $feature,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create event feature',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update an existing event feature
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $feature = EventFeature::find($id);

            if (!$feature) {
                return response()->json([
                    'success' => false,
                    'message' => 'Event feature not found',
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:60|unique:event_features,name,' . $id,
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $feature->update([
                'name' => $request->name,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Event feature updated successfully',
                'data' => $feature,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update event feature',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete an event feature
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $feature = EventFeature::find($id);

            if (!$feature) {
                return response()->json([
                    'success' => false,
                    'message' => 'Event feature not found',
                ], 404);
            }

            // Detach from all events before deleting
            $feature->events()->detach();
            $feature->delete();

            return response()->json([
                'success' => true,
                'message' => 'Event feature deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete event feature',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

