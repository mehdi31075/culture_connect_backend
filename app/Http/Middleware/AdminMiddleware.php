<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!$request->user()) {
            return response()->json([
                'error' => 'Unauthenticated. Please login first.'
            ], 401);
        }

        // Check if user is staff/admin
        if (!$request->user()->is_staff) {
            return response()->json([
                'error' => 'Unauthorized. Admin access required.'
            ], 403);
        }

        return $next($request);
    }
}
