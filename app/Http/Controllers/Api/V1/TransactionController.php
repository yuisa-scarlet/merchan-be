<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\UserRoleEnum;
use App\Helpers\ApiResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Middleware\RedirectIfNotAuthenticatedApi;
use App\Http\Resources\TransactionCollection;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\RouteDiscovery\Attributes\Route;

class TransactionController extends Controller
{
    #[Route(method: 'GET', fullUri: 'transactions', middleware: [RedirectIfNotAuthenticatedApi::class])]
    public function index(): JsonResponse
    {
        $user = Auth::guard('api')->user();
        $transactions = QueryBuilder::for(Transaction::with('user'))
            ->defaultSort('-created_at')
            ->allowedFilters(['external_ref', 'status'])
            ->allowedSorts(['created_at', 'updated_at'])
            ->when($user->role === UserRoleEnum::USER, function ($query) use ($user) {
                return $query->where('user_id', $user->id);
            })
            ->get();

        return ApiResponseFormatter::success(
            data: (new TransactionCollection($transactions))->response()->getData(true)['data'],
            message: 'Transaction list retrieved successfully',
        );
    }

    #[Route(method: 'GET', fullUri: '/transaction/{transaction}', middleware: [RedirectIfNotAuthenticatedApi::class])]
    public function show(Transaction $transaction): JsonResponse
    {
        $user = Auth::guard('api')->user();
        
        if ($transaction->user_id !== $user->id) {
            return ApiResponseFormatter::error(
                status: 'forbidden',
                message: 'You do not have permission to access this transaction',
            );
        }

        $transaction->load('user');
        $transaction->load('logs');

        return ApiResponseFormatter::success(
            data: new TransactionResource($transaction),
            message: 'Transaction details retrieved successfully',
        );
    }
}
