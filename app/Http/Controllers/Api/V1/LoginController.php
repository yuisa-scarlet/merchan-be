<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\ApiResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Spatie\RouteDiscovery\Attributes\Route;

class LoginController extends Controller
{
    #[Route(method: 'POST')]
    public function __invoke(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        if (!Auth::attempt($credentials)) {
            return ApiResponseFormatter::error(
                status: 'invalid_credentials',
                message: 'Invalid credentials',
                code: 401,
                errors: "The provided credentials do not match our records."
            );
        }

        /** @var User $user */
        $user = Auth::user();

        return ApiResponseFormatter::success(
            data: [
                'access_token' => $user->createToken('access_token')->plainTextToken,
                'token_type' => 'Bearer',
                'expires_in' => 3600,
                'user' => new UserResource($user),
            ],
            message: 'Login successful',
        );
    }
}
