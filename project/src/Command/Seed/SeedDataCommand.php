<?php

declare(strict_types=1);

namespace App\Command\Seed;

use App\Command\AbstractSeedCommand;
use App\Entity\Order;
use App\Entity\Shop;
use Symfony\Component\Console\Input\InputInterface;
use Throwable;

/**
 * Class SeedDataCommand
 *
 * @package App\Command\Seed
 * @noinspection PhpUnused
 */
class SeedDataCommand extends AbstractSeedCommand
{
    protected const string DESCRIPTION = 'Seed data to test data tables';
    public const string NAME = 'posiflora:seed-data';

    /** @var string[][]|int[][] */
    private const array DATA = [
        'POSIFLORA Base Shop' => [
            ['X123234324-0001', 3, 'Vasya Petrov'],
            ['X123234324-0002', 45, 'Vanya'],
            ['X123234324-0003', 2, 'Oleg'],
            ['X123234324-0004', 40, 'Stas'],
            ['X123234324-0005', 12, 'Igor'],
            ['X123234324-0006', 17, 'Sveta Petrova'],
        ]
    ];

    /**
     * @param InputInterface $input
     * @return array
     */
    protected function executeSeed(InputInterface $input): array
    {
        // @todo без обортки в транзакцию - тестовый сид

        foreach (static::DATA as $shopName => $orders) {
            $shopItem = new Shop($shopName);
            $this->entityManager->persist($shopItem);
            $this->entityManager->flush();
            $results[] = $this->displayItem($shopItem, 'магазин');
            foreach ($orders as $order) {
                $orderItem = new Order($shopItem, ...$order);
                $this->entityManager->persist($orderItem);
                $this->entityManager->flush();
                $results[] = $this->displayItem($orderItem, 'заказ');
            }
        }

        return $results ?? [];
    }

    /**
     * @param Order|Shop $item
     * @param string $name
     * @return string
     */
    private function displayItem(Order|Shop $item, string $name): string
    {
        $desc = [];
        foreach ($item->display() as $k => $v) {
            if ($v) {
                if (!is_array($v)) {
                    $desc[] = ' - ' .$k . ': ' . $v;
                }
            }
        }

        return PHP_EOL . 'Добавлен новый ' . $name . ': ' . PHP_EOL . implode(PHP_EOL, $desc);
    }
}
