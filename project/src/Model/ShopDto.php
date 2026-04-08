<?php
declare(strict_types=1);

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class ShopDto
 *
 * @package App\Model
 */
class ShopDto
{
    /**
     * @param string|null $name
     */
    public function __construct(
        #[Assert\Length(max: 256, maxMessage: 'Content is more than {{ limit }}')]
        public null|string $name,
    ) {
    }
}