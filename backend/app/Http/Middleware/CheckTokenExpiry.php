<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTokenExpiry
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->guard('api')->user();

        if ($user) {
            $token = $user->tokens()->where('id', $user->token()->id)->first();

            if ($token && $token->expires_at && $token->expires_at->isPast()) {
                // Revoke the expired token to prevent further use
                // $token->revoke();
                return response()->json(['message' => 'Token has expired.'], 401);
            }
        }

        return $next($request);
    }
}
