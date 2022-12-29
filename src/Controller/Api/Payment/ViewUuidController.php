<?php

declare(strict_types=1);

namespace App\Controller\Api\Payment;

use App\Controller\Main\BaseCQRSController;
use App\Domain\Application\ValueObject\Uuid;
use App\Domain\Payment\Service\PaymentService;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ViewUuidController extends BaseCQRSController
{
    /**
     * @OA\Get(
     *     path="/api/payments/{uuid}",
     *     @OA\Parameter(required=true, name="uuid", in="path"),
     *     summary="Оплата по UUID",
     *     tags={"Payments"},
     *     description="Одна оплата",
     *     @OA\Response(
     *         response="200",
     *         description="",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                  @OA\Property(property="uuid", type="string", format="uuid"),
     *                  @OA\Property(property="user_id", type="string", example="uuid пользователя"),
     *                  @OA\Property(property="order_id", type="string", example="uuid заказа"),
     *                  @OA\Property(property="summ", type="string", example="смма заказа"),
     *                  @OA\Property(property="status", type="int", example="статус"),
     *             ),
     *         )
     *     )
     * )
     */
    #[Route('/api/payments/{uuid}', name: 'api_payment_uuid_view', methods: ['GET'])]
    public function index(PaymentService $paymentService, $uuid): JsonResponse
    {
        $uuidVo = new Uuid($uuid);

        if (true !== $this->validateVO($uuidVo)) {
            return $this->validateVO($uuidVo);
        }

        return $paymentService->getOnePaymentToJson($uuidVo->getValue());
    }
}
