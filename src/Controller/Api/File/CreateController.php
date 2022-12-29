<?php

declare(strict_types=1);

namespace App\Controller\Api\File;

use App\Controller\Main\BaseCQRSController;
use App\Domain\File\Cqrs\Command\CreateFileCommand;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class CreateController extends BaseCQRSController
{
    /**
     * @OA\Post(
     *     path="/api/files",
     *     summary="Создать файл",
     *     tags={"Files"},
     *     description="Создание файла",
     *     @OA\RequestBody(
     *          required=true,
     *          request="files",
     *          description="Добавление данных производится в JSON формате",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                  required={"filename", "type"},
     *                  @OA\Property(property="filename", type="string", example="postman-632acb690b175.png"),
     *                  @OA\Property(property="type", type="string", example="image")
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
    #[Route('/api/files', name: 'api_files_add', methods: ['POST'])]
    public function add(Request $request): JsonResponse
    {
        $fieldsArray = json_decode($request->getContent(), true);
        $command = $this->fieldsToDto($fieldsArray, CreateFileCommand::class);
        return $this->validateDispatchAndRenderJson($command, $this::TYPE_CREATE);
    }
}
