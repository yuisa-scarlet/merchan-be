<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\ApiResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Middleware\RedirectIfNotAuthenticatedApi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\RouteDiscovery\Attributes\Route;

class LogoutController extends Controller
{
    #[Route(method: 'POST', middleware: [RedirectIfNotAuthenticatedApi::class])]
    public function __invoke(): JsonResponse
    {
        /** @var User $user */
        $user = Auth::guard('api')->user();
        $user->tokens()->delete();

        try {
            return ApiResponseFormatter::success(
                data: null,
                message: 'Logout successful',
            );
        } catch (\Exception $e) {
            return ApiResponseFormatter::error(
                status: 'logout_failed',
                message: 'Logout failed',
                code: 500,
                errors: $e->getMessage()
            );
        }
    }
}
