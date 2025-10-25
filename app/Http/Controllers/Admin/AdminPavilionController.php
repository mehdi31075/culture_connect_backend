<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pavilion;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Tag(
 *     name="Admin Pavilion",
 *     description="Admin pavilion management endpoints"
 * )
 */
class AdminPavilionController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/admin/pavilions",
     *     summary="Create a new pavilion",
     *     description="Create a new pavilion with icon upload",
     *     operationId="createPavilion",
     *     tags={"Admin Pavilion"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="name", type="string", example="Main Pavilion"),
     *                 @OA\Property(property="description", type="string", example="The main cultural pavilion"),
     *                 @OA\Property(property="country", type="string", example="UAE"),
     *                 @OA\Property(property="lat", type="number", format="float", example=25.2048),
     *                 @OA\Property(property="lng", type="number", format="float", example=55.2708),
     *                 @OA\Property(property="open_hours", type="string", example="9:00 AM - 10:00 PM"),
     *                 @OA\Property(
     *                     property="icon",
     *                     type="string",
     *                     format="binary",
     *                     description="Pavilion icon image file"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Pavilion created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Pavilion created successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Main Pavilion"),
     *                 @OA\Property(property="description", type="string", example="The main cultural pavilion"),
     *                 @OA\Property(property="icon", type="string", example="https://example.com/storage/pavilions/icon_123.png"),
     *                 @OA\Property(property="country", type="string", example="UAE"),
     *                 @OA\Property(property="lat", type="number", format="float", example=25.2048),
     *                 @OA\Property(property="lng", type="number", format="float", example=55.2708),
     *                 @OA\Property(property="open_hours", type="string", example="9:00 AM - 10:00 PM"),
     *                 @OA\Property(property="shops_count", type="integer", example=0),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation failed"),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="name",
     *                     type="array",
     *                     @OA\Items(type="string", example="The name field is required.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Failed to create pavilion")
     *         )
     *     )
     * )
     */
    public function store(Request $request): JsonResponse
    {
        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'description' => 'required|string|max:1000',
                'country' => 'required|string|max:255',
                'lat' => 'required|numeric|between:-90,90',
                'lng' => 'required|numeric|between:-180,180',
                'open_hours' => 'nullable|string|max:255',
                'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB max
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            // Handle icon upload
            $iconPath = null;
            if ($request->hasFile('icon')) {
                $icon = $request->file('icon');
                $iconName = 'pavilion_' . time() . '_' . uniqid() . '.' . $icon->getClientOriginalExtension();
                $iconPath = $icon->storeAs('pavilions', $iconName, 'public');
            }

            // Create pavilion
            $pavilion = Pavilion::create([
                'name' => $request->name,
                'description' => $request->description,
                'country' => $request->country,
                'lat' => $request->lat,
                'lng' => $request->lng,
                'open_hours' => $request->open_hours,
                'icon' => $iconPath ? Storage::url($iconPath) : null,
            ]);

            // Add shops count
            $pavilion->shops_count = 0;

            return response()->json([
                'success' => true,
                'message' => 'Pavilion created successfully',
                'data' => $pavilion,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create pavilion',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/admin/pavilions/{id}",
     *     summary="Update pavilion",
     *     description="Update an existing pavilion with optional icon upload",
     *     operationId="updatePavilion",
     *     tags={"Admin Pavilion"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Pavilion ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="name", type="string", example="Updated Pavilion"),
     *                 @OA\Property(property="description", type="string", example="Updated description"),
     *                 @OA\Property(property="country", type="string", example="UAE"),
     *                 @OA\Property(property="lat", type="number", format="float", example=25.2048),
     *                 @OA\Property(property="lng", type="number", format="float", example=55.2708),
     *                 @OA\Property(property="open_hours", type="string", example="9:00 AM - 10:00 PM"),
     *                 @OA\Property(
     *                     property="icon",
     *                     type="string",
     *                     format="binary",
     *                     description="New pavilion icon image file (optional)"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Pavilion updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Pavilion updated successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Updated Pavilion"),
     *                 @OA\Property(property="description", type="string", example="Updated description"),
     *                 @OA\Property(property="icon", type="string", example="https://example.com/storage/pavilions/icon_123.png"),
     *                 @OA\Property(property="country", type="string", example="UAE"),
     *                 @OA\Property(property="lat", type="number", format="float", example=25.2048),
     *                 @OA\Property(property="lng", type="number", format="float", example=55.2708),
     *                 @OA\Property(property="open_hours", type="string", example="9:00 AM - 10:00 PM"),
     *                 @OA\Property(property="shops_count", type="integer", example=5),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Pavilion not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Pavilion not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation failed"),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="name",
     *                     type="array",
     *                     @OA\Items(type="string", example="The name field is required.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Failed to update pavilion")
     *         )
     *     )
     * )
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $pavilion = Pavilion::find($id);

            if (!$pavilion) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pavilion not found',
                ], 404);
            }

            // Validate the request
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'description' => 'required|string|max:1000',
                'country' => 'required|string|max:255',
                'lat' => 'required|numeric|between:-90,90',
                'lng' => 'required|numeric|between:-180,180',
                'open_hours' => 'nullable|string|max:255',
                'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB max
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            // Handle icon upload if provided
            if ($request->hasFile('icon')) {
                // Delete old icon if exists
                if ($pavilion->icon) {
                    $oldIconPath = str_replace(Storage::url(''), '', $pavilion->icon);
                    Storage::disk('public')->delete($oldIconPath);
                }

                $icon = $request->file('icon');
                $iconName = 'pavilion_' . time() . '_' . uniqid() . '.' . $icon->getClientOriginalExtension();
                $iconPath = $icon->storeAs('pavilions', $iconName, 'public');
                $iconUrl = Storage::url($iconPath);
            } else {
                $iconUrl = $pavilion->icon; // Keep existing icon
            }

            // Update pavilion
            $pavilion->update([
                'name' => $request->name,
                'description' => $request->description,
                'country' => $request->country,
                'lat' => $request->lat,
                'lng' => $request->lng,
                'open_hours' => $request->open_hours,
                'icon' => $iconUrl,
            ]);

            // Add shops count
            $pavilion->shops_count = $pavilion->shops()->count();

            return response()->json([
                'success' => true,
                'message' => 'Pavilion updated successfully',
                'data' => $pavilion,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update pavilion',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/admin/pavilions/{id}",
     *     summary="Delete pavilion",
     *     description="Delete a pavilion and its associated icon",
     *     operationId="deletePavilion",
     *     tags={"Admin Pavilion"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Pavilion ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Pavilion deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Pavilion deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Pavilion not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Pavilion not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Failed to delete pavilion")
     *         )
     *     )
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $pavilion = Pavilion::find($id);

            if (!$pavilion) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pavilion not found',
                ], 404);
            }

            // Delete icon file if exists
            if ($pavilion->icon) {
                $iconPath = str_replace(Storage::url(''), '', $pavilion->icon);
                Storage::disk('public')->delete($iconPath);
            }

            // Delete pavilion
            $pavilion->delete();

            return response()->json([
                'success' => true,
                'message' => 'Pavilion deleted successfully',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete pavilion',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
