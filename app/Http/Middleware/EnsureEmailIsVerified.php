<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Allow access if email is verified
        if ($user->email_verified_at) {
            return $next($request);
        }

        // Redirect to email verification page if not verified
        if ($request->route()->getName() !== 'email.verify.show' && 
            $request->route()->getName() !== 'email.verify' && 
            $request->route()->getName() !== 'email.resend' &&
            $request->route()->getName() !== 'logout') {
            return redirect()->route('email.verify.show')
                ->with('error', 'Please verify your email address to continue.');
        }

        return $next($request);
    }
}


