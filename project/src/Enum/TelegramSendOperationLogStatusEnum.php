<?php
declare(strict_types=1);

namespace App\Enum;

/**
 * Enum TelegramSendOperationLogStatusEnum
 *
 * @package App\Enum
 */
enum TelegramSendOperationLogStatusEnum: string
{
    case SKIPPED = 'SKIPPED';
    case SENT = TelegramSendLogStatusEnum::SENT->value;
    case FAILED = TelegramSendLogStatusEnum::FAILED->value;
}
