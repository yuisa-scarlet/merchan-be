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

        $totalDeposit = $user->transactions()
            ->where('transaction_type', 'deposit')
            ->where('status', 'paid')
            ->sum('amount');

        $totalWithdrawal = $user->transactions()
            ->where('transaction_type', 'withdrawal')
            ->where('status', 'paid')
            ->sum('amount');

        $user->total_deposit = $totalDeposit;
        $user->total_withdrawal = $totalWithdrawal;

        return ApiResponseFormatter::success(
            data: new UserProfileResource($user),
            message: 'User profile retrieved successfully'
        );
    }
}
