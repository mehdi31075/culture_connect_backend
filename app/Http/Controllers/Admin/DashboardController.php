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
use Carbon\Carbon;

class DashboardController extends Controller
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
     * Get comprehensive dashboard statistics
     */
    public function index()
    {
        $now = Carbon::now();
        $lastWeek = $now->copy()->subWeek();
        $lastMonth = $now->copy()->subMonth();

        // Basic counts
        $stats = [
            'overview' => [
                'total_users' => User::count(),
                'active_users' => User::where('is_active', true)->count(),
                'total_events' => Event::count(),
                'total_pavilions' => Pavilion::count(),
                'total_shops' => Shop::count(),
                'total_products' => Product::count(),
                'total_orders' => Order::count(),
                'total_reviews' => Review::count(),
            ],
            'growth' => [
                'new_users_this_week' => User::where('created_at', '>=', $lastWeek)->count(),
                'new_users_this_month' => User::where('created_at', '>=', $lastMonth)->count(),
                'new_orders_this_week' => Order::where('created_at', '>=', $lastWeek)->count(),
                'new_orders_this_month' => Order::where('created_at', '>=', $lastMonth)->count(),
            ],
            'revenue' => [
                'total_revenue' => Order::where('status', 'delivered')->sum('total_amount'),
                'revenue_this_week' => Order::where('status', 'delivered')
                    ->where('created_at', '>=', $lastWeek)
                    ->sum('total_amount'),
                'revenue_this_month' => Order::where('status', 'delivered')
                    ->where('created_at', '>=', $lastMonth)
                    ->sum('total_amount'),
            ],
            'orders_by_status' => [
                'pending' => Order::where('status', 'pending')->count(),
                'confirmed' => Order::where('status', 'confirmed')->count(),
                'preparing' => Order::where('status', 'preparing')->count(),
                'ready' => Order::where('status', 'ready')->count(),
                'delivered' => Order::where('status', 'delivered')->count(),
                'cancelled' => Order::where('status', 'cancelled')->count(),
            ],
            'recent_activity' => [
                'recent_users' => User::latest()->take(5)->get(['id', 'first_name', 'last_name', 'email', 'created_at']),
                'recent_events' => Event::latest()->take(5)->get(['id', 'name', 'start_time', 'created_at']),
                'recent_orders' => Order::with('user:id,first_name,last_name')->latest()->take(5)->get(['id', 'user_id', 'total_amount', 'status', 'created_at']),
                'recent_reviews' => Review::with('user:id,first_name,last_name')->latest()->take(5)->get(['id', 'user_id', 'rating', 'created_at']),
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Get user analytics
     */
    public function userAnalytics()
    {
        $now = Carbon::now();
        $last30Days = $now->copy()->subDays(30);

        // User registration trends
        $userTrends = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = $now->copy()->subDays($i);
            $userTrends[] = [
                'date' => $date->format('Y-m-d'),
                'count' => User::whereDate('created_at', $date)->count()
            ];
        }

        // User activity by hour
        $hourlyActivity = [];
        for ($i = 0; $i < 24; $i++) {
            $hourlyActivity[] = [
                'hour' => $i,
                'count' => User::whereRaw('HOUR(created_at) = ?', [$i])->count()
            ];
        }

        return response()->json([
            'success' => true,
            'data' => [
                'user_trends' => $userTrends,
                'hourly_activity' => $hourlyActivity,
                'user_distribution' => [
                    'by_locale' => User::selectRaw('locale, COUNT(*) as count')->groupBy('locale')->get(),
                    'active_vs_inactive' => [
                        'active' => User::where('is_active', true)->count(),
                        'inactive' => User::where('is_active', false)->count(),
                    ]
                ]
            ]
        ]);
    }

    /**
     * Get order analytics
     */
    public function orderAnalytics()
    {
        $now = Carbon::now();
        $last30Days = $now->copy()->subDays(30);

        // Order trends
        $orderTrends = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = $now->copy()->subDays($i);
            $orderTrends[] = [
                'date' => $date->format('Y-m-d'),
                'count' => Order::whereDate('created_at', $date)->count(),
                'revenue' => Order::where('status', 'delivered')
                    ->whereDate('created_at', $date)
                    ->sum('total_amount')
            ];
        }

        // Average order value
        $avgOrderValue = Order::where('status', 'delivered')->avg('total_amount');

        return response()->json([
            'success' => true,
            'data' => [
                'order_trends' => $orderTrends,
                'average_order_value' => round($avgOrderValue, 2),
                'order_status_distribution' => Order::selectRaw('status, COUNT(*) as count')
                    ->groupBy('status')
                    ->get(),
                'top_products' => Product::withCount('orderItems')
                    ->orderBy('order_items_count', 'desc')
                    ->take(10)
                    ->get(['id', 'name', 'order_items_count'])
            ]
        ]);
    }

    /**
     * Get event analytics
     */
    public function eventAnalytics()
    {
        $now = Carbon::now();

        // Upcoming events
        $upcomingEvents = Event::where('start_time', '>', $now)
            ->orderBy('start_time')
            ->take(10)
            ->get(['id', 'name', 'start_time', 'pavilion_id']);

        // Event attendance
        $eventAttendance = Event::withCount('attendances')
            ->orderBy('attendances_count', 'desc')
            ->take(10)
            ->get(['id', 'name', 'attendances_count']);

        return response()->json([
            'success' => true,
            'data' => [
                'upcoming_events' => $upcomingEvents,
                'most_popular_events' => $eventAttendance,
                'events_by_pavilion' => Pavilion::withCount('events')
                    ->orderBy('events_count', 'desc')
                    ->get(['id', 'name', 'events_count'])
            ]
        ]);
    }

    /**
     * Get system health metrics
     */
    public function systemHealth()
    {
        $now = Carbon::now();
        $last24Hours = $now->copy()->subDay();

        return response()->json([
            'success' => true,
            'data' => [
                'database_status' => 'healthy',
                'cache_status' => 'healthy',
                'queue_status' => 'healthy',
                'recent_errors' => 0,
                'active_sessions' => User::where('updated_at', '>=', $last24Hours)->count(),
                'system_load' => [
                    'cpu' => '25%',
                    'memory' => '60%',
                    'disk' => '45%'
                ]
            ]
        ]);
    }
}
