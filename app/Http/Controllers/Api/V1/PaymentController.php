<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\ApiResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PaymentRequest;
use App\Http\Resources\TransactionResource;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;
use Spatie\RouteDiscovery\Attributes\DoNotDiscover;
use Spatie\RouteDiscovery\Attributes\Route;

class PaymentController extends Controller
{
    #[DoNotDiscover]
    public function __construct(private PaymentService $service) {}

    #[Route(method: 'POST')]
    public function __invoke(PaymentRequest $request): JsonResponse
    {
        $data = $request->validated();

        try {
            $payment = $this->service->processPayment($data);

            return ApiResponseFormatter::success(
                data: new TransactionResource($payment),
                message: 'Payment processed successfully',
            );
        } catch (\Exception $e) {
            if ($e->getMessage() === 'not_found') {
                return ApiResponseFormatter::error(
                    status: 'transaction_not_found',
                    message: 'Transaction not found',
                    code: 404
                );
            }
            
            return ApiResponseFormatter::error(
                status: 'payment_failed',
                message: 'Payment processing failed',
                code: 500,
                errors: $e->getMessage()
            );
        }
    }
}
