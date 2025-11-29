<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminOfferController extends Controller
{
    /**
     * Get all offers with pagination
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->get('per_page', 15);
            $search = $request->get('search');
            $shopId = $request->get('shop_id');

            $query = Offer::with(['shop', 'product', 'food']);

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            }

            if ($shopId) {
                $query->where('shop_id', $shopId);
            }

            $offers = $query->latest()->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => [
                    'items' => $offers->items(),
                    'pagination' => [
                        'current_page' => $offers->currentPage(),
                        'per_page' => $offers->perPage(),
                        'total' => $offers->total(),
                        'last_page' => $offers->lastPage(),
                        'from' => $offers->firstItem(),
                        'to' => $offers->lastItem(),
                        'has_more_pages' => $offers->hasMorePages(),
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve offers',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get a single offer
     */
    public function show(int $id): JsonResponse
    {
        try {
            $offer = Offer::with(['shop', 'product', 'food'])->find($id);

            if (!$offer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Offer not found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $offer,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve offer',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created offer
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'shop_id' => 'required|exists:shops,id',
                'product_id' => 'nullable|exists:products,id',
                'food_id' => 'nullable|exists:foods,id',
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'discount_type' => 'required|in:' . Offer::DISCOUNT_TYPE_PERCENT . ',' . Offer::DISCOUNT_TYPE_FIXED,
                'value' => 'required|numeric|min:0',
                'is_bundle' => 'nullable|boolean',
                'start_at' => 'required|date',
                'end_at' => 'required|date|after:start_at',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $offer = Offer::create($request->only([
                'shop_id', 'product_id', 'food_id', 'title', 'description',
                'discount_type', 'value', 'is_bundle', 'start_at', 'end_at'
            ]));

            return response()->json([
                'success' => true,
                'message' => 'Offer created successfully',
                'data' => $offer->load(['shop', 'product', 'food']),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create offer',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update an existing offer
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $offer = Offer::find($id);

            if (!$offer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Offer not found',
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'shop_id' => 'sometimes|exists:shops,id',
                'product_id' => 'nullable|exists:products,id',
                'food_id' => 'nullable|exists:foods,id',
                'title' => 'sometimes|string|max:255',
                'description' => 'nullable|string',
                'discount_type' => 'sometimes|in:' . Offer::DISCOUNT_TYPE_PERCENT . ',' . Offer::DISCOUNT_TYPE_FIXED,
                'value' => 'sometimes|numeric|min:0',
                'is_bundle' => 'nullable|boolean',
                'start_at' => 'sometimes|date',
                'end_at' => 'sometimes|date|after:start_at',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $offer->update($request->only([
                'shop_id', 'product_id', 'food_id', 'title', 'description',
                'discount_type', 'value', 'is_bundle', 'start_at', 'end_at'
            ]));

            return response()->json([
                'success' => true,
                'message' => 'Offer updated successfully',
                'data' => $offer->fresh()->load(['shop', 'product', 'food']),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update offer',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete an offer
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $offer = Offer::find($id);

            if (!$offer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Offer not found',
                ], 404);
            }

            $offer->delete();

            return response()->json([
                'success' => true,
                'message' => 'Offer deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete offer',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

