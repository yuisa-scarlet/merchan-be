<?php

namespace App\Http\Middleware;

use App\Helpers\ApiResponseFormatter;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfNotAuthenticatedApi
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('api')->check()) {
            return ApiResponseFormatter::error(
                message: 'Unauthorized. Please login.',
                code: 401,
            );
        };

        return $next($request);
    }
}
