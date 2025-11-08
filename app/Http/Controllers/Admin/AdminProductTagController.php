<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FoodTag;
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
        $tags = FoodTag::withCount(['tagProducts as products_count'])->orderBy('name')->get();

        return response()->json([
            'success' => true,
            'data' => [
                'items' => $tags,
            ],
        ]);
    }

    /**
     * Store a new product tag
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:160|unique:food_tags,name',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $tag = FoodTag::create([
            'name' => $request->name,
        ]);

        $tag->loadCount(['tagProducts as products_count']);

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
        $tag = FoodTag::find($id);

        if (!$tag) {
            return response()->json([
                'success' => false,
                'message' => 'Tag not found',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:160|unique:food_tags,name,' . $tag->id,
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

        $tag->loadCount(['tagProducts as products_count']);

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
        $tag = FoodTag::find($id);

        if (!$tag) {
            return response()->json([
                'success' => false,
                'message' => 'Tag not found',
            ], 404);
        }

        // Detach tag from products before deleting
        $tag->tagProducts()->detach();
        $tag->productTags()->delete();
        $tag->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tag deleted successfully',
        ]);
    }
}

