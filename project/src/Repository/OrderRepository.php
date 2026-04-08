<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Order;
use App\Entity\Shop;
use App\Model\OrderDto;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class OrderRepository
 *
 * @package App\Repository
 */
final class OrderRepository extends ServiceEntityRepository
{
    /**
     * OrderRepository constructor.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    /**
     * @param Shop $shop
     * @param OrderDto $dto
     * @param DateTimeInterface|null $dt
     * @return Order
     */
    public function createFromOrderDto(Shop $shop, OrderDto $dto, ?DateTimeInterface $dt = null): Order
    {
        $item = new Order($shop, $dto->number, $dto->total, $dto->customerName, $dt);
        $this->getEntityManager()->persist($item);
        $this->getEntityManager()->flush();

        return $item;
    }
}