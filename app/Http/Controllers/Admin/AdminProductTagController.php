<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductTag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminProductTagController extends Controller
{
    /**
     * Get list of product tags
     */
    public function index(): JsonResponse
    {
        try {
            // Try to get counts, but handle gracefully if relationships fail
            $tags = ProductTag::orderBy('name')->get();

            // Manually count products and foods for each tag to avoid relationship issues
            foreach ($tags as $tag) {
                try {
                    $tag->products_count = $tag->products()->count();
                } catch (\Exception $e) {
                    $tag->products_count = 0;
                }

                try {
                    $tag->foods_count = $tag->foods()->count();
                } catch (\Exception $e) {
                    $tag->foods_count = 0;
                }
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'items' => $tags,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve tags',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a new product tag
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:160|unique:food_tags,name',
            'tag_type' => 'nullable|in:product,food,both',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $tag = ProductTag::create([
            'name' => $request->name,
            'tag_type' => $request->input('tag_type', ProductTag::TYPE_BOTH),
        ]);

        // Manually set counts
        try {
            $tag->products_count = $tag->products()->count();
        } catch (\Exception $e) {
            $tag->products_count = 0;
        }

        try {
            $tag->foods_count = $tag->foods()->count();
        } catch (\Exception $e) {
            $tag->foods_count = 0;
        }

        return response()->json([
            'success' => true,
            'message' => 'Tag created successfully',
            'data' => $tag,
        ], 201);
    }

    /**
     * Update an existing product tag
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $tag = ProductTag::find($id);

        if (!$tag) {
            return response()->json([
                'success' => false,
                'message' => 'Tag not found',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:160|unique:food_tags,name,' . $tag->id,
            'tag_type' => 'nullable|in:product,food,both',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $updateData = ['name' => $request->name];
        if ($request->has('tag_type')) {
            $updateData['tag_type'] = $request->tag_type;
        }
        $tag->update($updateData);

        // Manually set counts
        try {
            $tag->products_count = $tag->products()->count();
        } catch (\Exception $e) {
            $tag->products_count = 0;
        }

        try {
            $tag->foods_count = $tag->foods()->count();
        } catch (\Exception $e) {
            $tag->foods_count = 0;
        }

        return response()->json([
            'success' => true,
            'message' => 'Tag updated successfully',
            'data' => $tag,
        ]);
    }

    /**
     * Delete a product tag
     */
    public function destroy(int $id): JsonResponse
    {
        $tag = ProductTag::find($id);

        if (!$tag) {
            return response()->json([
                'success' => false,
                'message' => 'Tag not found',
            ], 404);
        }

        // Detach tag from products and foods before deleting
        $tag->products()->detach();
        $tag->foods()->detach();
        $tag->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tag deleted successfully',
        ]);
    }
}

