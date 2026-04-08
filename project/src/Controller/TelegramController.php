<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Shop;
use App\Entity\TelegramIntegration;
use App\Enum\TelegramSendLogStatusEnum;
use App\Model\TelegramIntegrationDto;
use App\Repository\ShopRepository;
use App\Repository\TelegramIntegrationRepository;
use App\Repository\TelegramSendLogRepository;
use DateMalformedStringException;
use Doctrine\ORM\EntityNotFoundException;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;

/**
 * Class TelegramController
 *
 * @package App\Controller
 */
class TelegramController extends AbstractController
{
    /**
     * @param int $shopId
     * @param TelegramIntegrationDto $dto
     * @param TelegramIntegrationRepository $repository
     * @param ShopRepository $shopRepository
     * @return JsonResponse
     */
    public function manage(
        int                           $shopId,
        #[MapRequestPayload(
            acceptFormat: 'json', validationFailedStatusCode: Response::HTTP_PRECONDITION_FAILED
        )] TelegramIntegrationDto     $dto,
        TelegramIntegrationRepository $repository,
        ShopRepository                $shopRepository
    ): JsonResponse
    {
        $shop = $shopRepository->find($shopId);
        if (!$shop instanceof Shop) {

            throw new RuntimeException('Shop ID not valid');
        }

        return new JsonResponse($repository->upsertFromShopDto($shop, $dto)->displaySafe());
    }

    /**
     * @param int $shopId
     * @param TelegramIntegrationRepository $repository
     * @param TelegramSendLogRepository $sendLogRepository
     * @param ShopRepository $shopRepository
     * @return JsonResponse
     * @throws DateMalformedStringException
     */
    public function view(
        int                           $shopId,
        TelegramIntegrationRepository $repository,
        TelegramSendLogRepository     $sendLogRepository,
        ShopRepository                $shopRepository
    ): JsonResponse
    {
        $shop = $shopRepository->find($shopId);
        if (!$shop instanceof Shop) {

            throw new RuntimeException('Shop ID not valid');
        }
        $telegramIntegration = $repository->findOneBy(['shop' => $shop]);
        if (!$telegramIntegration instanceof TelegramIntegration) {

            throw new EntityNotFoundException('TelegramIntegration for shop with id=' . $shopId . 'not found ');
        }
        $lastSent = $sendLogRepository->findOneBy(['shop' => $telegramIntegration->getShop()], ['sentAt' => 'DESC']);

        return new JsonResponse([
            'enabled' => $telegramIntegration->getEnabled(),
            'chatId' => mb_substr($telegramIntegration->getChatId(), 0, 2) . '...',
            'lastSentAt' => $lastSent?->getSentAt()->format('Y-m-d H:i:s'),
            'sentCount' => $sendLogRepository->sentTotalForLastDays(7, TelegramSendLogStatusEnum::SENT),
            'failedCount' => $sendLogRepository->sentTotalForLastDays(7, TelegramSendLogStatusEnum::FAILED),
        ]);
    }
}