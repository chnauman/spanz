<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscriptionAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Allow access if user has Pro or Enterprise subscription
        if ($user && $user->canViewTenderDetails()) {
            return $next($request);
        }

        // For non-subscribers, return JSON response for AJAX requests
        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Subscription required to view tender details',
                'requires_subscription' => true
            ], 403);
        }

        // For regular requests, redirect back with message
        return redirect()->back()
            ->with('error', 'You need a Pro or Enterprise subscription to view tender details.');
    }
}
