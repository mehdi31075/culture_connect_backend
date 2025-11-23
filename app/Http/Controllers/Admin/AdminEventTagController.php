<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EventTag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminEventTagController extends Controller
{
    /**
     * Get list of event tags
     */
    public function index(): JsonResponse
    {
        $tags = EventTag::withCount(['events as events_count'])->orderBy('name')->get();

        return response()->json([
            'success' => true,
            'data' => [
                'items' => $tags,
            ],
        ]);
    }

    /**
     * Store a new event tag
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:60|unique:event_tags,name',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $tag = EventTag::create([
            'name' => $request->name,
        ]);

        $tag->loadCount(['events as events_count']);

        return response()->json([
            'success' => true,
            'message' => 'Tag created successfully',
            'data' => $tag,
        ], 201);
    }

    /**
     * Update an existing event tag
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $tag = EventTag::find($id);

        if (!$tag) {
            return response()->json([
                'success' => false,
                'message' => 'Tag not found',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:60|unique:event_tags,name,' . $tag->id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $tag->update([
            'name' => $request->name,
        ]);

        $tag->loadCount(['events as events_count']);

        return response()->json([
            'success' => true,
            'message' => 'Tag updated successfully',
            'data' => $tag,
        ]);
    }

    /**
     * Delete an event tag
     */
    public function destroy(int $id): JsonResponse
    {
        $tag = EventTag::find($id);

        if (!$tag) {
            return response()->json([
                'success' => false,
                'message' => 'Tag not found',
            ], 404);
        }

        // Detach tag from events before deleting
        $tag->events()->detach();
        $tag->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tag deleted successfully',
        ]);
    }
}

