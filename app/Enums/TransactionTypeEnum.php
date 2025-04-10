<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class TransactionTypeEnum extends Enum
{
    const DEPOSIT = 'deposit';
    const WITHDRAWAL = 'withdrawal';
}
