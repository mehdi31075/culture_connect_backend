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
     * Get a single shop
     */
    public function show(int $id): JsonResponse
    {
        try {
            $shop = Shop::with('pavilion')->find($id);

            if (!$shop) {
                return response()->json([
                    'success' => false,
                    'message' => 'Shop not found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $shop,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve shop',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created shop
     */
    public function store(Request $request): JsonResponse
    {
        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'pavilion_id' => 'nullable|exists:pavilions,id',
                'name' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'type' => 'nullable|string|in:shop',
                'lat' => 'nullable|numeric|between:-90,90',
                'lng' => 'nullable|numeric|between:-180,180',
                'location_name' => 'nullable|string|max:160',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            // Create shop - convert empty pavilion_id to null
            $pavilionId = $request->pavilion_id;
            if ($pavilionId === '' || $pavilionId === null) {
                $pavilionId = null;
            } else {
                $pavilionId = (int) $pavilionId;
            }

            $shop = Shop::create([
                'pavilion_id' => $pavilionId,
                'name' => $request->name,
                'description' => $request->description,
                'type' => $request->type ?? Shop::TYPE_SHOP,
                'lat' => $request->lat ? (float) $request->lat : null,
                'lng' => $request->lng ? (float) $request->lng : null,
                'location_name' => $request->location_name,
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
                'pavilion_id' => 'nullable|exists:pavilions,id',
                'name' => 'sometimes|required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'type' => 'nullable|string|in:shop',
                'lat' => 'nullable|numeric|between:-90,90',
                'lng' => 'nullable|numeric|between:-180,180',
                'location_name' => 'nullable|string|max:160',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            // Update shop - convert empty pavilion_id to null
            $updateData = $request->only(['pavilion_id', 'name', 'description', 'type', 'lat', 'lng', 'location_name']);
            if (isset($updateData['pavilion_id'])) {
                if ($updateData['pavilion_id'] === '' || $updateData['pavilion_id'] === null) {
                    $updateData['pavilion_id'] = null;
                } else {
                    $updateData['pavilion_id'] = (int) $updateData['pavilion_id'];
                }
            }
            // Convert lat/lng to float or null
            if (isset($updateData['lat'])) {
                $updateData['lat'] = $updateData['lat'] === '' ? null : (float) $updateData['lat'];
            }
            if (isset($updateData['lng'])) {
                $updateData['lng'] = $updateData['lng'] === '' ? null : (float) $updateData['lng'];
            }
            $shop->update($updateData);

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

