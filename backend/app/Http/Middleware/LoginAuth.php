<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class LoginAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is authenticated
        if (!Auth::check()) {
            // Save the current URL as the intended URL in the session
            Session::put('url.intended', $request->fullUrl());

            // If not authenticated, redirect to the custom login route
            return redirect()->route('auth.showLoginForm');
        }

        // If authenticated, proceed with the request
        return $next($request);
    }
}
