<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Order;
use App\Entity\TelegramSendLog;
use App\Enum\TelegramSendLogStatusEnum;
use DateMalformedStringException;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use RuntimeException;

/**
 * Class TelegramSendLogRepository
 *
 * @package App\Repository
 * @method TelegramSendLog|null findOneBy(array $params, array $orderBy = [])
 */
final class TelegramSendLogRepository extends ServiceEntityRepository
{
    /**
     * TelegramSendLogRepository constructor.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TelegramSendLog::class);
    }

    /**
     * @param Order $order
     * @param string $message
     * @param TelegramSendLogStatusEnum $status
     * @param string $error
     * @return TelegramSendLog
     */
    public function new(Order $order, string $message, TelegramSendLogStatusEnum $status, string $error = ''): TelegramSendLog
    {
        $item = new TelegramSendLog($order->getShop(), $order, $message, $status, $error);

        $this->getEntityManager()->persist($item);
        $this->getEntityManager()->flush();

        return $item;
    }

    /**
     * @param int $days
     * @param TelegramSendLogStatusEnum $status
     * @return int
     * @throws DateMalformedStringException
     */
    public function sentTotalForLastDays(int $days, TelegramSendLogStatusEnum $status): int
    {
        if ($days < 1 || $days > 365) {
            throw new RuntimeException('Days are out of range (1-365): ' . $days);
        }
        $dt = DateTime::createFromFormat('Y-m-d 00:00:00', new DateTime()->format('Y-m-d 00:00:00'))->modify(
            '-' . $days . ' day'
        );
        $results = $this->createQueryBuilder('tsl')
            ->select('count(tsl.id) as counts')
            ->where('tsl.status = :status')
            ->andWhere('tsl.sentAt' . ' > :from')
            ->setParameter('status', $status)
            ->setParameter('from', $dt);

        return $results->getQuery()->getArrayResult()[0]['counts'] ?? 0;
    }
}