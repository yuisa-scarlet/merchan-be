<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\ApiResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Middleware\RedirectIfNotAuthenticatedApi;
use App\Http\Requests\Api\WithdrawalRequest;
use App\Http\Resources\TransactionResource;
use App\Services\TransactionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\RouteDiscovery\Attributes\DoNotDiscover;
use Spatie\RouteDiscovery\Attributes\Route;

class WithdrawalController extends Controller
{
    #[DoNotDiscover]
    public function __construct(private TransactionService $service) {}

    #[Route(method: 'POST', middleware: [RedirectIfNotAuthenticatedApi::class])]
    public function __invoke(WithdrawalRequest $request): JsonResponse
    {
        $data = $request->validated();

        /** @var User $user */
        $user = Auth::guard('api')->user();

        try {
            $transactions = $this->service->withdraw($data, $user->id);

            return ApiResponseFormatter::success(
                data: new TransactionResource($transactions),
                message: 'Withdrawal successful',
            );
        } catch (\Exception $e) {
            return ApiResponseFormatter::error(
                status: 'withdrawal_failed',
                message: 'Withdrawal failed',
                code: 500,
                errors: $e->getMessage()
            );
        }
    }
}
