<?php
declare(strict_types=1);

namespace App\Enum;

/**
 * Enum TelegramSendLogStatusEnum
 *
 * @package App\Enum
 */
enum TelegramSendLogStatusEnum: string
{
    case SENT = 'SENT';
    case FAILED = 'FAILED';
}
