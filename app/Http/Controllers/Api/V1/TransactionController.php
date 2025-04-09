<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\ApiResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    
    public function index(): JsonResponse
    {
        return ApiResponseFormatter::success(
            data: null,
            message: 'Transaction list retrieved successfully',
        );
    }

    public function show(Transaction $transaction): JsonResponse
    {
        return ApiResponseFormatter::success(
            data: $transaction,
            message: 'Transaction details retrieved successfully',
        );
    }
}
