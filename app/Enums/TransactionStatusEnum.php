<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class TransactionStatusEnum extends Enum
{
    const CREATED = 'created';
    const PENDING = 'pending';
    const PAID = 'paid';
    const FAILED = 'failed';
    const CANCELED = 'canceled';
    const EXPIRE = 'expire';
}
