<?php

namespace App\Services;

use App\Enums\TransactionLogActionEnum;
use App\Enums\TransactionStatusEnum;
use App\Enums\TransactionTypeEnum;
use App\Models\Transaction;
use App\Models\TransactionLog;

class PaymentService
{
  public function processPayment(array $data): Transaction
  {
    $transaction = Transaction::where('external_ref', $data['external_ref'])->first();

    if (!$transaction) {
      throw new \Exception('not_found');
    }

    $payload = [
      'external_ref' => $data['external_ref'],
      'amount' => $data['amount'],
      'status' => TransactionStatusEnum::PAID,
    ];

    $transaction->update([
      'status' => TransactionStatusEnum::PAID,
      'updated_at' => now(),
    ]);

    if (
      $data['status'] === TransactionStatusEnum::PAID
      && $transaction->transaction_type == TransactionTypeEnum::DEPOSIT
    ) {
      $transaction->user->increment('balance', $transaction->amount);
    }

    if (
      $data['status'] === TransactionStatusEnum::PAID
      && $transaction->transaction_type == TransactionTypeEnum::WITHDRAWAL
    ) {
      $transaction->user->decrement('balance', $transaction->amount);
    }

    $this->paymentLogs($transaction, $payload);

    return $transaction->load('user');
  }

  private function paymentLogs($transaction, $payload)
  {
    return TransactionLog::create([
      'transaction_id' => $transaction->id,
      'action' => TransactionLogActionEnum::WEBHOOK_RECEIVED,
      'description' => 'Payment status: ' . $payload['status'],
      'data' => json_encode($payload),
    ]);
  }
}