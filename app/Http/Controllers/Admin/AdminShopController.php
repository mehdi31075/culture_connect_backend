<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\Pavilion;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class AdminShopController extends Controller
{
    /**
     * Store a newly created shop
     */
    public function store(Request $request): JsonResponse
    {
        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'pavilion_id' => 'required|exists:pavilions,id',
                'name' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'type' => 'nullable|string|in:shop,food_truck,restaurant',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            // Create shop
            $shop = Shop::create([
                'pavilion_id' => $request->pavilion_id,
                'name' => $request->name,
                'description' => $request->description,
                'type' => $request->type ?? Shop::TYPE_SHOP,
            ]);

            // Load relationships
            $shop->load('pavilion');

            return response()->json([
                'success' => true,
                'message' => 'Shop created successfully',
                'data' => $shop,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create shop',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update an existing shop
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $shop = Shop::find($id);

            if (!$shop) {
                return response()->json([
                    'success' => false,
                    'message' => 'Shop not found',
                ], 404);
            }

            // Validate the request
            $validator = Validator::make($request->all(), [
                'pavilion_id' => 'sometimes|required|exists:pavilions,id',
                'name' => 'sometimes|required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'type' => 'nullable|string|in:shop,food_truck,restaurant',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            // Update shop
            $shop->update($request->only(['pavilion_id', 'name', 'description', 'type']));

            // Load relationships
            $shop->load('pavilion');

            return response()->json([
                'success' => true,
                'message' => 'Shop updated successfully',
                'data' => $shop,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update shop',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete a shop
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $shop = Shop::find($id);

            if (!$shop) {
                return response()->json([
                    'success' => false,
                    'message' => 'Shop not found',
                ], 404);
            }

            // Delete shop
            $shop->delete();

            return response()->json([
                'success' => true,
                'message' => 'Shop deleted successfully',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete shop',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

