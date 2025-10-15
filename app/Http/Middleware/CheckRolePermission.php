<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRolePermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission = null): Response
    {
        $user = auth()->user();

        // If no user is authenticated, redirect to login
        if (!$user) {
            return redirect()->route('login');
        }

        // If permission is specified, check if user has that permission
        if ($permission && !$user->hasPermission($permission)) {
            return $this->handleUnauthorizedAccess($request, $user);
        }

        return $next($request);
    }

    /**
     * Handle unauthorized access by redirecting back to previous page
     */
    protected function handleUnauthorizedAccess(Request $request, $user)
    {
        // Store the intended URL for potential redirect after login
        if (!$request->session()->has('url.intended')) {
            $request->session()->put('url.intended', $request->url());
        }

        // For AJAX requests, return JSON response
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to access this resource.',
                'error' => 'insufficient_permissions',
                'redirect' => $this->getRedirectUrl($request)
            ], 403);
        }

        // For regular requests, redirect back with error message
        $redirectUrl = $this->getRedirectUrl($request);
        
        return redirect($redirectUrl)
            ->with('error', 'You do not have permission to access this page.')
            ->with('permission_denied', true);
    }

    /**
     * Determine the appropriate redirect URL
     */
    protected function getRedirectUrl(Request $request)
    {
        // If there's a previous URL in the session, use it
        if ($request->session()->has('url.intended')) {
            $intended = $request->session()->get('url.intended');
            // Don't redirect to the same unauthorized URL
            if ($intended !== $request->url()) {
                return $intended;
            }
        }

        // Check if there's a referer header
        if ($request->header('referer')) {
            $referer = $request->header('referer');
            $refererHost = parse_url($referer, PHP_URL_HOST);
            $currentHost = $request->getHost();
            
            // Only redirect to referer if it's from the same domain
            if ($refererHost === $currentHost) {
                return $referer;
            }
        }

        // Default redirect based on user role
        return $this->getDefaultRedirectForRole($request->user());
    }

    /**
     * Get default redirect URL based on user role
     */
    protected function getDefaultRedirectForRole($user)
    {
        switch ($user->role) {
            case 'admin':
                return route('admin.dashboard') ?? route('dashboard');
            case 'buyer':
                return route('tenders.my-tenders') ?? route('dashboard');
            case 'supplier':
            case 'sub_supplier':
                return route('tenders.index') ?? route('dashboard');
            case 'guest':
                return route('home');
            default:
                return route('dashboard');
        }
    }
}
