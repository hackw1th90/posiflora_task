<?php
declare(strict_types=1);

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class OrderDto
 *
 * @package App\Model
 */
class OrderDto
{
    /**
     * @param string $number
     * @param int $total
     * @param string $customerName
     */
    public function __construct(
        #[Assert\Length(max: 64, maxMessage: 'Content is more than {{ limit }}')]
        #[Assert\NotBlank(message: 'Content is empty')]
        public string $number,
        #[Assert\NotBlank(message: 'Content is empty')]
        public int    $total,
        #[Assert\Length(max: 64, maxMessage: 'Content is more than {{ limit }}')]
        #[Assert\NotBlank(message: 'Content is empty')]
        public string $customerName,
    )
    {
    }
}