<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\ApiResponseFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WithdrawalController extends Controller
{
    public function __invoke(): JsonResponse
    {
        return ApiResponseFormatter::success(
            data: null,
            message: 'Withdrawal successful',
        );
    }
}
