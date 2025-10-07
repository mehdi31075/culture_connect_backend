<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Event;
use App\Models\Pavilion;
use App\Models\Shop;
use App\Models\Product;
use App\Models\Order;
use App\Models\Review;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Tag(
 *     name="Admin",
 *     description="Admin panel endpoints for managing the application"
 * )
 */
class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware(function ($request, $next) {
            if (!$request->user() || !$request->user()->is_staff) {
                return response()->json(['error' => 'Unauthorized. Admin access required.'], 403);
            }
            return $next($request);
        });
    }

    /**
     * @OA\Get(
     *     path="/api/admin/dashboard",
     *     summary="Get admin dashboard statistics",
     *     description="Get comprehensive statistics for the admin dashboard",
     *     tags={"Admin"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Dashboard statistics retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="overview", type="object",
     *                     @OA\Property(property="total_users", type="integer", example=150),
     *                     @OA\Property(property="active_users", type="integer", example=120),
     *                     @OA\Property(property="total_events", type="integer", example=25),
     *                     @OA\Property(property="total_orders", type="integer", example=300)
     *                 ),
     *                 @OA\Property(property="recent_activity", type="object")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Admin access required",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Unauthorized. Admin access required.")
     *         )
     *     )
     * )
     */
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'total_events' => Event::count(),
            'total_pavilions' => Pavilion::count(),
            'total_shops' => Shop::count(),
            'total_products' => Product::count(),
            'total_orders' => Order::count(),
            'total_reviews' => Review::count(),
            'recent_users' => User::latest()->take(5)->get(),
            'recent_events' => Event::latest()->take(5)->get(),
            'recent_orders' => Order::latest()->take(5)->get(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Get all users with pagination
     */
    public function users(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $search = $request->get('search');

        $query = User::query();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $users = $query->with('profile')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }

    /**
     * Get user details
     */
    public function getUser($id)
    {
        $user = User::with(['profile', 'wallet', 'orders', 'reviews'])->find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    /**
     * Update user
     */
    public function updateUser(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:120',
            'email' => 'sometimes|email|unique:users,email,' . $id,
            'phone' => 'sometimes|string|max:32|unique:users,phone,' . $id,
            'is_active' => 'sometimes|boolean',
            'is_staff' => 'sometimes|boolean',
            'locale' => 'sometimes|string|in:en,ar',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $user->update($request->only(['name', 'email', 'phone', 'is_active', 'is_staff', 'locale']));

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully',
            'data' => $user
        ]);
    }

    /**
     * Delete user
     */
    public function deleteUser($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Prevent deleting staff users
        if ($user->is_staff) {
            return response()->json(['error' => 'Cannot delete staff users'], 403);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully'
        ]);
    }

    /**
     * Get all events with pagination
     */
    public function events(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $search = $request->get('search');

        $query = Event::with(['pavilion', 'tags']);

        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }

        $events = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $events
        ]);
    }

    /**
     * Get all pavilions
     */
    public function pavilions(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $search = $request->get('search');

        $query = Pavilion::with(['shops', 'events']);

        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }

        $pavilions = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $pavilions
        ]);
    }

    /**
     * Get all shops
     */
    public function shops(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $search = $request->get('search');

        $query = Shop::with(['pavilion', 'products']);

        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }

        $shops = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $shops
        ]);
    }

    /**
     * Get all orders
     */
    public function orders(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $status = $request->get('status');

        $query = Order::with(['user', 'items.product']);

        if ($status) {
            $query->where('status', $status);
        }

        $orders = $query->latest()->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }

    /**
     * Update order status
     */
    public function updateOrderStatus(Request $request, $id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|string|in:pending,confirmed,preparing,ready,delivered,cancelled',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $order->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Order status updated successfully',
            'data' => $order
        ]);
    }

    /**
     * Get all reviews
     */
    public function reviews(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $rating = $request->get('rating');

        $query = Review::with(['user', 'reviewable']);

        if ($rating) {
            $query->where('rating', $rating);
        }

        $reviews = $query->latest()->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $reviews
        ]);
    }

    /**
     * Delete review
     */
    public function deleteReview($id)
    {
        $review = Review::find($id);

        if (!$review) {
            return response()->json(['error' => 'Review not found'], 404);
        }

        $review->delete();

        return response()->json([
            'success' => true,
            'message' => 'Review deleted successfully'
        ]);
    }

    /**
     * Get system notifications
     */
    public function notifications(Request $request)
    {
        $perPage = $request->get('per_page', 15);

        $notifications = Notification::with('user')
            ->latest()
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $notifications
        ]);
    }

    /**
     * Send system notification
     */
    public function sendNotification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|string|in:info,warning,success,error',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $notification = Notification::create([
            'user_id' => $request->user_id,
            'title' => $request->title,
            'message' => $request->message,
            'type' => $request->type,
            'is_read' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Notification sent successfully',
            'data' => $notification
        ]);
    }
}
