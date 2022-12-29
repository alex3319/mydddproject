<?php

declare(strict_types=1);

namespace App\Controller\Api\Page;

use App\Controller\Main\BaseCQRSController;
use App\Domain\Page\Cqrs\Command\UpdatePageCommand;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class UpdateController extends BaseCQRSController
{
    /**
     * @OA\Put(
     *     path="/api/pages/{uuid}",
     *     summary="Обновить страницу",
     *     tags={"Pages"},
     *     description="Обновление страницы",
     *     @OA\RequestBody(
     *          request="pages",
     *          @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                  required={"uuid"},
     *                  @OA\Property(property="uuid", type="string", format="uuid"),
     *                  @OA\Property(property="name", type="string", example="upd Доставка денеров"),
     *                  @OA\Property(property="title", type="string", example="upd Доставка денеров"),
     *                  @OA\Property(property="description", type="string", example="upd Денеры доставкой Красноярск"),
     *                  @OA\Property(property="keywords", type="string", example="upd доставка, шаурма, пицца"),
     *                  @OA\Property(property="h1", type="string", example="upd Заголовок title"),
     *                  @OA\Property(property="content", type="string",
     *                  example="upd Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consectetur delectus,"),
     *                  @OA\Property(property="slug", type="string", example="testovaya-stranitsa")
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
     *                  @OA\Property(property="name", type="string", example="upd Доставка денеров"),
     *                  @OA\Property(property="title", type="string", example="upd Доставка денеров"),
     *                  @OA\Property(property="description", type="string", example="upd Денеры доставкой Красноярск"),
     *                  @OA\Property(property="keywords", type="string", example="upd доставка, шаурма, пицца"),
     *                  @OA\Property(property="h1", type="string", example="upd Заголовок title"),
     *                  @OA\Property(property="content", type="string",
     *                  example="upd Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consectetur delectus,"),
     *                  @OA\Property(property="slug", type="string", example="testovaya-stranitsa")
     *             ),
     *         )
     *     )
     * )
     *
     * @throws ExceptionInterface
     */
    #[Route('/api/pages/{uuid}', name: 'api_page_update', methods: ['PUT'])]
    public function update(Request $request, $uuid): JsonResponse
    {
        $fieldsArray = json_decode($request->getContent(), true);
        $command = $this->fieldsToDto($fieldsArray, UpdatePageCommand::class, $uuid);

        return $this->validateDispatchAndRenderJson($command, self::TYPE_UPDATE);
    }
}
