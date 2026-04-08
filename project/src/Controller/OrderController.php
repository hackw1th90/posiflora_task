<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Shop;
use App\Model\OrderDto;
use App\Repository\OrderRepository;
use App\Repository\ShopRepository;
use App\Service\TelegramService;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;

/**
 * Class OrderController
 *
 * @package App\Controller
 */
class OrderController extends AbstractController
{
    /**
     * @param int $shopId
     * @param OrderDto $dto
     * @param OrderRepository $repository
     * @param ShopRepository $shopRepository
     * @param TelegramService $telegramService
     * @return JsonResponse
     */
    public function add(
        int             $shopId,
        #[MapRequestPayload(
            acceptFormat: 'json', validationFailedStatusCode: Response::HTTP_PRECONDITION_FAILED
        )] OrderDto     $dto,
        OrderRepository $repository,
        ShopRepository  $shopRepository,
        TelegramService $telegramService,
    ): JsonResponse
    {
        $shop = $shopRepository->find($shopId);
        if (!$shop instanceof Shop) {

            throw new RuntimeException('Shop ID not valid');
        }
        $order = $repository->createFromOrderDto($shop, $dto);

        return new JsonResponse([
            'status' => $telegramService->notifyAboutOrder($order)->value,
            'order' => $order->display(),
        ]);
    }
}