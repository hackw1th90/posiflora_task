<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Shop;
use App\Repository\ShopRepository;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class WebController
 *
 * @package App\Controller
 * @noinspection PhpUnused
 */
class WebController extends AbstractController
{
    /**
     * @param ShopRepository $shopRepository
     */
    public function __construct(private readonly ShopRepository $shopRepository)
    {
    }

    /**
     * @param int $shopId
     * @return Response
     */
    public function main(int $shopId): Response
    {
        $shop = $this->shopRepository->find($shopId);
        if (!$shop instanceof Shop) {

            throw new RuntimeException('Shop ID not valid');
        }
        try {
            $response = $this->render('base.html.twig', [
                'id' => $shopId,
                'title' => 'POSIFLORA dev-task web-page ',
                'name' => $shop->getName(),
                'date' => microtime(true),
            ]);
            $response->headers->set('Content-Type', 'text/html');
        } catch (Throwable $e) {

            return new Response($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $response;
    }
}