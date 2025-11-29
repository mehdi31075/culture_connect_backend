<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductTag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminProductController extends Controller
{
    /**
     * Get a single product
     */
    public function show(int $id): JsonResponse
    {
        try {
            $product = Product::with(['shop', 'tags'])->find($id);

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $product,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve product',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get all products with pagination
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->get('per_page', 15);
            $search = $request->get('search');
            $shopId = $request->get('shop_id');

            $query = Product::with(['shop', 'tags']);

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            }

            if ($shopId) {
                $query->where('shop_id', $shopId);
            }

            $products = $query->latest()->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => [
                    'items' => $products->items(),
                    'pagination' => [
                        'current_page' => $products->currentPage(),
                        'per_page' => $products->perPage(),
                        'total' => $products->total(),
                        'last_page' => $products->lastPage(),
                        'from' => $products->firstItem(),
                        'to' => $products->lastItem(),
                        'has_more_pages' => $products->hasMorePages(),
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve products',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created product
     */
    public function store(Request $request): JsonResponse
    {
        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'shop_id' => 'required|exists:shops,id',
                'name' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'price' => 'required|numeric|min:0',
                'is_food' => 'nullable|boolean',
                'image_url' => 'nullable|url|max:500',
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

            // Handle image upload if provided
            // $imageUrl = $request->image_url;
            // if ($request->hasFile('image')) {
            //     $image = $request->file('image');
            //     $imageName = 'product_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            //     $imagePath = $image->storeAs('products', $imageName, 'public');
            //     $imageUrl = url('storage/' . $imagePath);
            // }

            // Create product
            $product = Product::create([
                'shop_id' => $request->shop_id,
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'is_food' => $request->boolean('is_food', false),
                'image_url' => $request->image_url,
            ]);

            $this->syncProductTags($product, $request->input('tags', []), $request->input('new_tags', []));

            // Load relationships
            $product->load('shop', 'tags');

            return response()->json([
                'success' => true,
                'message' => 'Product created successfully',
                'data' => $product,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create product',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update an existing product
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $product = Product::find($id);

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found',
                ], 404);
            }

            // Validate the request
            $validator = Validator::make($request->all(), [
                'shop_id' => 'sometimes|required|exists:shops,id',
                'name' => 'sometimes|required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'price' => 'sometimes|required|numeric|min:0',
                'is_food' => 'nullable|boolean',
                'image_url' => 'nullable|url|max:500',
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

            // Update product
            $updateData = $request->only(['shop_id', 'name', 'description', 'price', 'image_url']);
            if ($request->has('is_food')) {
                $updateData['is_food'] = $request->boolean('is_food');
            }
            $product->update($updateData);

            $this->syncProductTags($product, $request->input('tags', []), $request->input('new_tags', []));

            // Load relationships
            $product->load('shop', 'tags');

            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully',
                'data' => $product,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update product',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete a product
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $product = Product::find($id);

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found',
                ], 404);
            }

            // Detach tags and delete product
            $product->tags()->detach();
            $product->delete();

            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete product',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Sync product tags, including creating new tags if provided
     */
    protected function syncProductTags(Product $product, $tagIds, $newTags): void
    {
        $tagIdsCollection = collect($tagIds ?? []);
        if (is_string($tagIds)) {
            $tagIdsCollection = collect(explode(',', $tagIds));
        }

        $tagIdsCollection = $tagIdsCollection
            ->filter(fn ($id) => $id !== null && $id !== '')
            ->map(fn ($id) => (int) $id)
            ->filter();

        $newTagsCollection = collect($newTags ?? []);
        if (is_string($newTags)) {
            $newTagsCollection = collect(explode(',', $newTags));
        }

        $newTagIds = $newTagsCollection
            ->map(fn ($tag) => trim($tag))
            ->filter()
            ->map(function (string $tagName) {
                $tag = ProductTag::firstOrCreate(['name' => $tagName]);
                return $tag->id;
            });

        $allTagIds = $tagIdsCollection->merge($newTagIds)->unique()->values();

        $product->tags()->sync($allTagIds->all());
    }
}


