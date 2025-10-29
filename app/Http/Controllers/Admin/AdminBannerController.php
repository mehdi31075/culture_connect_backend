<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AdminBannerController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page', 15);
        $query = Banner::query()->orderBy('order')->orderByDesc('id');
        $banners = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => [
                'items' => $banners->items(),
                'pagination' => [
                    'current_page' => $banners->currentPage(),
                    'per_page' => $banners->perPage(),
                    'total' => $banners->total(),
                    'last_page' => $banners->lastPage(),
                    'from' => $banners->firstItem(),
                    'to' => $banners->lastItem(),
                    'has_more_pages' => $banners->hasMorePages(),
                ]
            ]
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'nullable|string|max:255',
            'link' => 'nullable|url|max:2048',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'image' => 'required|file|mimes:jpeg,png,jpg,gif,svg|max:4096',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $path = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = 'banner_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('banners', $filename, 'public');
        }

        $banner = Banner::create([
            'title' => $request->title,
            'link' => $request->link,
            'order' => $request->get('order', 0),
            'is_active' => (bool) $request->get('is_active', true),
            'image' => $path ? url('storage/' . $path) : null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Banner created successfully',
            'data' => $banner,
        ], 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $banner = Banner::find($id);
        if (!$banner) {
            return response()->json(['success' => false, 'message' => 'Banner not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'nullable|string|max:255',
            'link' => 'nullable|url|max:2048',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:4096',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = [];
        foreach (['title','link','order','is_active'] as $field) {
            if ($request->has($field) && $request->$field !== null) {
                $data[$field] = $request->$field;
            }
        }

        if ($request->hasFile('image')) {
            // delete old file
            if ($banner->image) {
                $publicPrefix = url('storage/') . '/';
                $relative = str_starts_with($banner->image, $publicPrefix)
                    ? substr($banner->image, strlen($publicPrefix))
                    : null;
                if ($relative) {
                    Storage::disk('public')->delete($relative);
                }
            }
            $file = $request->file('image');
            $filename = 'banner_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('banners', $filename, 'public');
            $data['image'] = url('storage/' . $path);
        }

        if (!empty($data)) {
            $banner->update($data);
        }

        return response()->json([
            'success' => true,
            'message' => 'Banner updated successfully',
            'data' => $banner->fresh(),
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $banner = Banner::find($id);
        if (!$banner) {
            return response()->json(['success' => false, 'message' => 'Banner not found'], 404);
        }

        if ($banner->image) {
            $publicPrefix = url('storage/') . '/';
            $relative = str_starts_with($banner->image, $publicPrefix)
                ? substr($banner->image, strlen($publicPrefix))
                : null;
            if ($relative) {
                Storage::disk('public')->delete($relative);
            }
        }

        $banner->delete();

        return response()->json([
            'success' => true,
            'message' => 'Banner deleted successfully',
        ]);
    }
}
