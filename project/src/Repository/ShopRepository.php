<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Shop;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class ShopRepository
 *
 * @package App\Repository
 */
final class ShopRepository extends ServiceEntityRepository
{
    /**
     * ShopRepository constructor.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Shop::class);
    }
}
