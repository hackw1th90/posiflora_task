<?php
declare(strict_types=1);

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class TelegramIntegrationDto
 *
 * @package App\Model
 */
class TelegramIntegrationDto
{
    /**
     * @param string $botToken
     * @param string $chatId
     * @param bool|null $enabled
     */
    public function __construct(
        #[Assert\Length(max: 256, maxMessage: 'Content is more than {{ limit }}')]
        public string $botToken,
        #[Assert\Length(max: 32, maxMessage: 'Content is more than {{ limit }}')]
        public string $chatId,
        public bool|null $enabled = null,
    ) {
    }
}