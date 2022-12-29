<?php

declare(strict_types=1);

namespace App\Controller\Api\Payment;

use App\Controller\Main\BaseCQRSController;
use App\Domain\Payment\Cqrs\Command\CreatePaymentCommand;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class CreateController extends BaseCQRSController
{
    /**
     * @OA\Post(
     *     path="/api/payments",
     *     summary="Создать оплату",
     *     tags={"Payments"},
     *     description="Создание оплаты",
     *     @OA\RequestBody(
     *          required=true,
     *          request="payments",
     *          description="Добавление данных производится в JSON формате",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                  required={"user_id", "order_id", "summ", "status", "datetime"},
     *                  @OA\Property(property="user_id", type="string", example="3fa85f64-5717-4562-b3fc-2c963f66afa6"),
     *                  @OA\Property(property="order_id", type="string", example="3fa85f64-5717-4562-b3fc-2c963f66afa6"),
     *                  @OA\Property(property="summ", type="string", example="2499.99"),
     *                  @OA\Property(property="status", type="int", example="1"),
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description="Возвращается Uuid созданной оплаты в виде строки",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="uuid", type="string", format="uuid"),
     *             ),
     *         )
     *     )
     * )
     * @throws ExceptionInterface
     */
    #[Route('/api/payments', name: 'api_payments_add', methods: ['POST'])]
    public function add(Request $request): JsonResponse
    {
        $fieldsArray = json_decode($request->getContent(), true);
        $command = $this->fieldsToDto($fieldsArray, CreatePaymentCommand::class);

        return $this->validateDispatchAndRenderJson($command, $this::TYPE_CREATE);
    }
}