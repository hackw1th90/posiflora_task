<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Shop;
use App\Entity\TelegramIntegration;
use App\Model\TelegramIntegrationDto;
use DateTime;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class TelegramIntegrationRepository
 *
 * @package App\Repository
 */
final class TelegramIntegrationRepository extends ServiceEntityRepository
{
    /**
     * ShopRepository constructor.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TelegramIntegration::class);
    }

    /**
     * @param Shop $shop
     * @param TelegramIntegrationDto $dto
     * @param DateTimeInterface|null $dt
     * @return TelegramIntegration
     */
    public function upsertFromShopDto(Shop $shop, TelegramIntegrationDto $dto, ?DateTimeInterface $dt = null): TelegramIntegration
    {
        $item = $this->findOneBy(['shop' => $shop]);
        if (!$item instanceof TelegramIntegration) {
            $item = new TelegramIntegration($shop, $dto->botToken, $dto->chatId, (bool)$dto->enabled, $dt);
        } else {
            $item->setBotToken($dto->botToken);
            $item->setChatId($dto->chatId);
            $item->setEnabled((bool)$dto->enabled);
            $item->setUpdatedAt($dt ?? new DateTime());
        }
        $this->getEntityManager()->persist($item);
        $this->getEntityManager()->flush();

        return $item;
    }
}