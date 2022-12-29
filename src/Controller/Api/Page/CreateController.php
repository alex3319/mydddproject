<?php

declare(strict_types=1);

namespace App\Controller\Api\Page;

use App\Controller\Main\BaseCQRSController;
use App\Domain\Page\Cqrs\Command\CreatePageCommand;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class CreateController extends BaseCQRSController
{
    /**
     * @OA\Post(
     *     path="/api/pages",
     *     summary="Создать страницу",
     *     tags={"Pages"},
     *     description="Создание страницы",
     *     @OA\RequestBody(
     *          required=true,
     *          request="pages",
     *          description="Добавление данных производится в JSON формате",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                  required={"name", "title", "description", "content"},
     *                  @OA\Property(property="name", type="string", example="Доставка денеров"),
     *                  @OA\Property(property="title", type="string", example="Доставка денеров"),
     *                  @OA\Property(property="description", type="string", example="Денеры с доставкаой Красноярск"),
     *                  @OA\Property(property="keywords", type="string", example="доставка, шаурма, пицца"),
     *                  @OA\Property(property="h1", type="string", example="Заголовок title"),
     *                  @OA\Property(property="content", type="string",
     *                  example="Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consectetur delectus, ex"),
     *                  @OA\Property(property="slug", type="string", example="testovaya-stranitsa")
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description="Возвращается Uuid созданной страницы в виде строки",
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
    #[Route('/api/pages', name: 'api_pages_add', methods: ['POST'])]
    public function add(Request $request): JsonResponse
    {
        // проверка доступа
        if (!in_array('ROLE_MANAGER', $this->getRoles())){
            return new JsonResponse(['error' => 'forbidden'], $this::FORBIDDEN);
        }

        $fieldsArray = json_decode($request->getContent(), true);
        $command = $this->fieldsToDto($fieldsArray, CreatePageCommand::class);

        return $this->validateDispatchAndRenderJson($command, $this::TYPE_CREATE);
    }
}
