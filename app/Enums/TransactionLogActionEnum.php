<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class TransactionLogActionEnum extends Enum
{
    const CREATED = 'created';
    const UPDATED = 'updated';
    const WEBHOOK_SENT = 'webhook_sent';
    const WEBHOOK_RECEIVED = 'webhook_received';
}
