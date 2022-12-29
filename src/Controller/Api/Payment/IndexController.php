<?php

declare(strict_types=1);

namespace App\Controller\Api\Payment;

use App\Controller\Main\BaseCQRSController;
use App\Domain\Payment\Service\PaymentService;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends BaseCQRSController
{
    /**
     * @OA\Get(
     *     path="/api/payments",
     *     summary="Все страницы",
     *     tags={"Payments"},
     *     description="Список страниц",
     *     @OA\Response(
     *         response="200",
     *         description="",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  type="array",
     *                  @OA\Items(
     *                      @OA\Property(property="uuid", type="string", format="uuid"),
     *                      @OA\Property(property="user_id", type="string", example="3fa85f64-5717-4562-b3fc-2c963f66afa6"),
     *                      @OA\Property(property="order_id", type="string", example="3fa85f64-5717-4562-b3fc-2c963f66afa6"),
     *                      @OA\Property(property="summ", type="string", example="2499.99"),
     *                      @OA\Property(property="status", type="int", example="1"),
     *                  )
     *              ),
     *          ),
     *     )
     * )
     */
    #[Route('/api/payments', name: 'api_payment_index', methods: ['GET'])]
    public function index(PaymentService $paymentService): JsonResponse
    {
        return $paymentService->getAllToJson();
    }
}