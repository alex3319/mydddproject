<?php

declare(strict_types=1);

namespace App\Controller\Api\Payment;

use App\Controller\Main\BaseCQRSController;
use App\Domain\Payment\Cqrs\Command\UpdatePaymentCommand;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class UpdateController extends BaseCQRSController
{
    /**
     * @OA\Put(
     *     path="/api/payments/{uuid}",
     *     summary="Обновить оплату",
     *     tags={"Payments"},
     *     description="Обновление оплаты",
     *     @OA\RequestBody(
     *          request="payments",
     *          @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                  required={"uuid"},
     *                  @OA\Property(property="uuid", type="string", format="uuid"),
     *                  @OA\Property(property="user_id", type="string", example="updated 3fa85f64-5717-4562-b3fc-2c963f66afa6"),
     *                  @OA\Property(property="order_id", type="string", example="updated 3fa85f64-5717-4562-b3fc-2c963f66afa6"),
     *                  @OA\Property(property="summ", type="string", example="1"),
     *                  @OA\Property(property="status", type="int", example="0"),
     *             ),
     *          )
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description="",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                  @OA\Property(property="uuid", type="string", format="uuid"),
     *                  @OA\Property(property="user_id", type="string", example="updated 3fa85f64-5717-4562-b3fc-2c963f66afa6"),
     *                  @OA\Property(property="order_id", type="string", example="updated 3fa85f64-5717-4562-b3fc-2c963f66afa6"),
     *                  @OA\Property(property="summ", type="string", example="1"),
     *                  @OA\Property(property="status", type="int", example="0"),
     *             ),
     *         )
     *     )
     * )
     *
     * @throws ExceptionInterface
     */
    #[Route('/api/payments/{uuid}', name: 'api_payment_update', methods: ['PUT'])]
    public function update(Request $request, $uuid): JsonResponse
    {
        $fieldsArray = json_decode($request->getContent(), true);
        $command = $this->fieldsToDto($fieldsArray, UpdatePaymentCommand::class, $uuid);

        return $this->validateDispatchAndRenderJson($command, self::TYPE_UPDATE);
    }
}
