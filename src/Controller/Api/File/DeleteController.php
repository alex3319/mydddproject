<?php

declare(strict_types=1);

namespace App\Controller\Api\File;

use App\Controller\Main\BaseCQRSController;
use App\Domain\File\Cqrs\Command\DeleteFileCommand;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class DeleteController extends BaseCQRSController
{
    /**
     * @OA\Delete(
     *     path="/api/files/{uuid}",
     *     summary="Удалить файл",
     *     tags={"Files"},
     *     description="Удаление  файла",
     *     @OA\RequestBody(
     *          request="Files",
     *          @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                  required={"uuid"},
     *                  @OA\Property(property="uuid", type="string", format="uuid")
     *             ),
     *          )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Возвращается uuid удаленного файла",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                  @OA\Property(property="uuid", type="string", format="uuid")
     *             ),
     *         )
     *     )
     * )
     *
     * @throws ExceptionInterface
     */
    #[Route('/api/files/{uuid}', name: 'api_file_delete', methods: ['DELETE'])]
    public function delete(Request $request, $uuid): JsonResponse
    {
        $fieldsArray = json_decode($request->getContent(), true);
        $command = $this->fieldsToDto($fieldsArray, DeleteFileCommand::class, $uuid);
        return $this->validateDispatchAndRenderJson($command, self::TYPE_DELETE);
    }
}
