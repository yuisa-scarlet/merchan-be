<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\ApiResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Middleware\RedirectIfNotAuthenticatedApi;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\RouteDiscovery\Attributes\Route;

class TransactionController extends Controller
{
    #[Route(method: 'GET', fullUri: 'transactions', middleware: [RedirectIfNotAuthenticatedApi::class])]
    public function index(): JsonResponse
    {
        return ApiResponseFormatter::success(
            data: null,
            message: 'Transaction list retrieved successfully',
        );
    }

    #[Route(method: 'GET', uri: 'show', middleware: [RedirectIfNotAuthenticatedApi::class])]
    public function show(Transaction $transaction): JsonResponse
    {
        return ApiResponseFormatter::success(
            data: $transaction,
            message: 'Transaction details retrieved successfully',
        );
    }
}
