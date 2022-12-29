<?php

declare(strict_types=1);

namespace App\Controller\Api\File;

use App\Controller\Main\BaseCQRSController;
use App\Domain\File\Cqrs\Command\UpdateFileCommand;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class UpdateController extends BaseCQRSController
{
    /**
     * @OA\Put(
     *     path="/api/files/{uuid}",
     *     summary="Обновить файл",
     *     tags={"Files"},
     *     description="Обновление файла",
     *     @OA\RequestBody(
     *          request="Files",
     *          @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                  required={"uuid"},
     *                  @OA\Property(property="uuid", type="string", format="uuid"),
     *             ),
     *          )
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description="",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                  @OA\Property(property="uuid", type="string", format="uuid", example="ba49eb3f-384f-409b-8642-96c9b8a38230"),
     *                  @OA\Property(property="filename", type="string", example="image-632c564d09421.png"),
     *                  @OA\Property(property="type", type="string", example="image"),
     *             ),
     *         )
     *     )
     * )
     *
     * @throws ExceptionInterface
     */
    #[Route('/api/files/{uuid}', name: 'api_file_update', methods: ['PUT'])]
    public function update(Request $request, $uuid): JsonResponse
    {
        $fieldsArray = json_decode($request->getContent(), true);
        $command = $this->fieldsToDto($fieldsArray, UpdateFileCommand::class, $uuid);
        return $this->validateDispatchAndRenderJson($command, self::TYPE_UPDATE);
    }
}
