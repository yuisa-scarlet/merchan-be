<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\ApiResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Spatie\RouteDiscovery\Attributes\DoNotDiscover;
use Spatie\RouteDiscovery\Attributes\Route;

class RegisterController extends Controller
{
    #[DoNotDiscover]
    public function __construct(private AuthService $service) {}

    #[Route(method: 'POST')]
    public function __invoke(RegisterRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $user = $this->service->register($data);

            return ApiResponseFormatter::success(
                data : new UserResource($user),
                message: 'Registration successful',
            );
        } catch (\Exception $e) {
            if ($e->getMessage() === 'invalid_role') {
                return ApiResponseFormatter::error();
            }

            return ApiResponseFormatter::error(
                status: 'internal_server_error',
                message: 'Internal server error',
                code: 500,
                errors: $e->getMessage()
            );
        }
    }
}
