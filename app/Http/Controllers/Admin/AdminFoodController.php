<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Food;
use App\Models\ProductTag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AdminFoodController extends Controller
{
    /**
     * Get all foods with pagination
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->get('per_page', 15);
            $search = $request->get('search');
            $shopId = $request->get('shop_id');

            $query = Food::with(['shop', 'tags']);

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            }

            if ($shopId) {
                $query->where('shop_id', $shopId);
            }

            $foods = $query->orderBy('is_trending', 'desc')
                ->orderBy('trending_position', 'asc')
                ->orderBy('name', 'asc')
                ->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => [
                    'items' => $foods->items(),
                    'pagination' => [
                        'current_page' => $foods->currentPage(),
                        'per_page' => $foods->perPage(),
                        'total' => $foods->total(),
                        'last_page' => $foods->lastPage(),
                        'from' => $foods->firstItem(),
                        'to' => $foods->lastItem(),
                        'has_more_pages' => $foods->hasMorePages(),
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve foods',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created food
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'shop_id' => 'required|exists:shops,id',
                'name' => 'required|string|max:160',
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'images' => 'nullable|array',
                'images.*' => 'file|mimes:jpeg,png,jpg,gif,svg|max:4096',
                'views_count' => 'nullable|integer|min:0',
                'likes_count' => 'nullable|integer|min:0',
                'comments_count' => 'nullable|integer|min:0',
                'is_trending' => 'nullable|boolean',
                'trending_position' => 'nullable|integer|min:1',
                'trending_score' => 'nullable|numeric|min:0|max:100',
                'preparation_time' => 'nullable|integer|min:0',
                'is_available' => 'nullable|boolean',
                'tags' => 'nullable|array',
                'tags.*' => 'integer|exists:food_tags,id',
                'new_tags' => 'nullable|array',
                'new_tags.*' => 'string|max:160',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            // Handle image uploads
            $imageUrls = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $filename = 'food_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $path = $image->storeAs('foods', $filename, 'public');
                    $imageUrls[] = url('storage/' . $path);
                }
            }

            $food = Food::create([
                'shop_id' => $request->shop_id,
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'images' => !empty($imageUrls) ? $imageUrls : null,
                'views_count' => $request->get('views_count', 0),
                'likes_count' => $request->get('likes_count', 0),
                'comments_count' => $request->get('comments_count', 0),
                'is_trending' => $request->get('is_trending', false),
                'trending_position' => $request->trending_position,
                'trending_score' => $request->trending_score,
                'preparation_time' => $request->preparation_time,
                'is_available' => $request->get('is_available', true),
            ]);

            // Sync tags
            $this->syncFoodTags($food, $request->get('tags', []), $request->get('new_tags', []));

            return response()->json([
                'success' => true,
                'message' => 'Food created successfully',
                'data' => $food->load(['shop', 'tags']),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create food',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update an existing food
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $food = Food::find($id);

            if (!$food) {
                return response()->json([
                    'success' => false,
                    'message' => 'Food not found',
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'shop_id' => 'sometimes|exists:shops,id',
                'name' => 'sometimes|string|max:160',
                'description' => 'nullable|string',
                'price' => 'sometimes|numeric|min:0',
                'images' => 'nullable|array',
                'images.*' => 'file|mimes:jpeg,png,jpg,gif,svg|max:4096',
                'views_count' => 'nullable|integer|min:0',
                'likes_count' => 'nullable|integer|min:0',
                'comments_count' => 'nullable|integer|min:0',
                'is_trending' => 'nullable|boolean',
                'trending_position' => 'nullable|integer|min:1',
                'trending_score' => 'nullable|numeric|min:0|max:100',
                'preparation_time' => 'nullable|integer|min:0',
                'is_available' => 'nullable|boolean',
                'tags' => 'nullable|array',
                'tags.*' => 'integer|exists:food_tags,id',
                'new_tags' => 'nullable|array',
                'new_tags.*' => 'string|max:160',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $data = $request->only([
                'shop_id', 'name', 'description', 'price',
                'views_count', 'likes_count', 'comments_count',
                'is_trending', 'trending_position', 'trending_score',
                'preparation_time', 'is_available'
            ]);

            // Handle new image uploads
            if ($request->hasFile('images')) {
                $imageUrls = $food->images ?? [];
                foreach ($request->file('images') as $image) {
                    $filename = 'food_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $path = $image->storeAs('foods', $filename, 'public');
                    $imageUrls[] = url('storage/' . $path);
                }
                $data['images'] = $imageUrls;
            }

            $food->update($data);

            // Sync tags if provided
            if ($request->has('tags') || $request->has('new_tags')) {
                $this->syncFoodTags($food, $request->get('tags', []), $request->get('new_tags', []));
            }

            return response()->json([
                'success' => true,
                'message' => 'Food updated successfully',
                'data' => $food->fresh()->load(['shop', 'tags']),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update food',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete a food
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $food = Food::find($id);

            if (!$food) {
                return response()->json([
                    'success' => false,
                    'message' => 'Food not found',
                ], 404);
            }

            // Delete associated images
            if ($food->images) {
                foreach ($food->images as $imageUrl) {
                    $publicPrefix = url('storage/') . '/';
                    $relative = str_starts_with($imageUrl, $publicPrefix)
                        ? substr($imageUrl, strlen($publicPrefix))
                        : null;
                    if ($relative) {
                        Storage::disk('public')->delete($relative);
                    }
                }
            }

            // Detach tags
            $food->tags()->detach();

            $food->delete();

            return response()->json([
                'success' => true,
                'message' => 'Food deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete food',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Sync food tags (existing and new)
     */
    protected function syncFoodTags(Food $food, array $tagIds = [], array $newTagNames = []): void
    {
        $tagsToAttach = [];

        // Add existing tag IDs
        foreach ($tagIds as $tagId) {
            $tagsToAttach[] = $tagId;
        }

        // Create and add new tags
        foreach ($newTagNames as $tagName) {
            $tagName = trim($tagName);
            if (!empty($tagName)) {
                $tag = ProductTag::firstOrCreate(['name' => $tagName]);
                $tagsToAttach[] = $tag->id;
            }
        }

        // Sync tags
        $food->tags()->sync($tagsToAttach);
    }
}

