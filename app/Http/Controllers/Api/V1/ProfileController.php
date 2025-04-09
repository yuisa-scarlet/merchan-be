<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\ApiResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Middleware\RedirectIfNotAuthenticatedApi;
use App\Http\Resources\UserProfileResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Spatie\RouteDiscovery\Attributes\Route;

class ProfileController extends Controller
{
    #[Route(method: 'GET', middleware: [RedirectIfNotAuthenticatedApi::class])]
    public function __invoke(): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::guard('api')->user();

        return ApiResponseFormatter::success(
            data: new UserProfileResource($user),
            message: 'User profile retrieved successfully'
        );
    }
}
