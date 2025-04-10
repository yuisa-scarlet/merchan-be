<?php

namespace App\Http\Requests\Api;

use App\Helpers\ApiResponseFormatter;
use App\Models\Transaction;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'external_ref' => 'required|string|max:255|exists:transactions,external_ref',
            'amount' => 'required|numeric|min:1',
            'status' => 'required|in:paid,failed,canceled',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $transaction = Transaction::where('external_ref', $this->external_ref)->first();

            if ($transaction && $transaction->status !== 'pending') {
                $validator->errors()->add('external_ref', 'The transaction already processed.');
            }

            if ($transaction && $transaction->amount !== $this->amount) {
                $validator->errors()->add('amount', 'The amount does not match the transaction amount.');
            }

            if ($transaction && $transaction->amount < $this->amount) {
                $validator->errors()->add('amount', 'The amount exceeds the transaction amount.');
            }
        });
    }

    public function messages()
    {
        return [
            'external_ref.required' => 'The external reference is required.',
            'external_ref.string' => 'The external reference must be a string.',
            'external_ref.max' => 'The external reference may not be greater than 255 characters.',
            'external_ref.exists' => 'The external reference does not exist in the transactions table.',
            'amount.required' => 'The amount is required.',
            'amount.numeric' => 'The amount must be a number.',
            'amount.min' => 'The amount must be at least 1.',
            'status.required' => 'The status is required.',
            'status.in' => 'The selected status is invalid. Allowed values are: paid, failed, canceled.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new \Illuminate\Validation\ValidationException(
            $validator,
            ApiResponseFormatter::error(
                status: 'validation_failed',
                message: 'Validation failed',
                code: 422,
                errors: $validator->errors()
            )
        );
    }
}
