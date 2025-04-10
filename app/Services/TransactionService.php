<?php

namespace App\Services;

use App\Enums\TransactionLogActionEnum;
use App\Enums\TransactionStatusEnum;
use App\Enums\TransactionTypeEnum;
use App\Models\Transaction;
use App\Models\TransactionLog;

class TransactionService
{
  public function deposit(array $data, int $userId): Transaction
  {
    $transaction = Transaction::create([
      'user_id' => $userId,
      'amount' => $data['amount'],
      'transaction_type' => TransactionTypeEnum::DEPOSIT,
      'status' => TransactionStatusEnum::PENDING,
      'external_ref' => $this->generateExternalRef(TransactionTypeEnum::DEPOSIT, $userId),
    ]);

    $this->transactionLogs([
      'transaction_id' => $transaction->id,
      'action' => TransactionLogActionEnum::CREATED,
      'description' => 'Deposit created',
      'data' => json_encode($transaction),
    ]);

    return $transaction->load('user');
  }

  public function withdraw(array $data, int $userId): Transaction
  {
    $transaction = Transaction::create([
      'user_id' => $userId,
      'amount' => $data['amount'],
      'transaction_type' => TransactionTypeEnum::WITHDRAWAL,
      'status' => TransactionStatusEnum::PENDING,
      'external_ref' => $this->generateExternalRef(TransactionTypeEnum::WITHDRAWAL, $userId),
    ]);

    $this->transactionLogs([
      'transaction_id' => $transaction->id,
      'action' => TransactionLogActionEnum::CREATED,
      'description' => 'Withdrawal created',
      'data' => json_encode($transaction),
    ]);

    return $transaction->load('user');
  }

  private function transactionLogs(array $data)
  {
    return TransactionLog::create([
      'transaction_id' => $data['transaction_id'],
      'action' => $data['action'],
      'description' => $data['description'],
      'data' => $data['data'],
    ]);
  }

  private function generateExternalRef(string $type, int $userId): string
  {
    $typeCode = $type === TransactionTypeEnum::DEPOSIT ? 'D' : 'W';
    $timestamp = now()->format('YmdHis');

    return "TRX-{$typeCode}{$userId}{$timestamp}";
  }
}