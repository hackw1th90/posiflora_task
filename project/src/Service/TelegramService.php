<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\Order;
use App\Entity\TelegramIntegration;
use App\Enum\TelegramSendLogStatusEnum;
use App\Enum\TelegramSendOperationLogStatusEnum;
use App\Repository\TelegramIntegrationRepository;
use App\Repository\TelegramSendLogRepository;
use Throwable;

/**
 * Class TelegramService
 *
 * @package App\Service
 */
readonly class TelegramService
{
    /**
     * @param TelegramIntegrationRepository $telegramIntegrationRepository
     * @param TelegramSendLogRepository $telegramSendLogRepository
     * @param TelegramMessagingService $telegramMessagingService
     */
    public function __construct(
        private TelegramIntegrationRepository $telegramIntegrationRepository,
        private TelegramSendLogRepository     $telegramSendLogRepository,
        private TelegramMessagingService      $telegramMessagingService,
    )
    {
    }

    /**
     * @param Order $order
     * @return TelegramSendOperationLogStatusEnum
     */
    public function notifyAboutOrder(Order $order): TelegramSendOperationLogStatusEnum
    {
        $telegramIntegration = $this->telegramIntegrationRepository->findOneBy([
            'shop' => $order->getShop(), 'enabled' => true
        ]);
        if (!$telegramIntegration instanceof TelegramIntegration) {

            return TelegramSendOperationLogStatusEnum::SKIPPED;
        }
        $log = $this->telegramSendLogRepository->findOneBy(['order' => $order, 'shop' => $order->getShop()]);
        if ($log instanceof TelegramSendOperationLogStatusEnum) {

            return TelegramSendOperationLogStatusEnum::SKIPPED;
        }
        $message = sprintf(
            'Новый заказ %s на сумму %s ₽, клиент %s ',
            $order->getNumber(), $order->getTotal(), $order->getCustomerName()
        );
        $result = $this->sendMessage($telegramIntegration, $message);
        if ($result instanceof Throwable) {
            $status = TelegramSendLogStatusEnum::FAILED;
            $error = $result->getMEssage();
        }
        $sendLog = $this->telegramSendLogRepository->new(
            $order, $message, $status ?? TelegramSendLogStatusEnum::SENT, $error ?? ''
        );

        return TelegramSendOperationLogStatusEnum::from($sendLog->getStatus()->value);
    }

    /**
     * @param TelegramIntegration $telegramIntegration
     * @param string $message
     * @return Throwable|null
     */
    private function sendMessage(TelegramIntegration $telegramIntegration, string $message): ?Throwable
    {
        try {
            $this->telegramMessagingService->send(
                $telegramIntegration->getBotToken(), $telegramIntegration->getChatId(), $message
            );
        } catch (Throwable $e) {

            return $e;
        }

        return null;
    }
}
