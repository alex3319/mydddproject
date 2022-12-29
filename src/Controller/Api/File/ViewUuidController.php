<?php

declare(strict_types=1);

namespace App\Controller\Api\File;

use App\Controller\Main\BaseCQRSController;
use App\Domain\Application\ValueObject\Uuid;
use App\Domain\File\Service\FileService;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ViewUuidController extends BaseCQRSController
{
    /**
     * @OA\Get(
     *     path="/api/files/{uuid}",
     *     @OA\Parameter(required=true, name="uuid", in="path"),
     *     summary="Файл по UUID",
     *     tags={"Files"},
     *     description="Один файл",
     *     @OA\Response(
     *         response="200",
     *         description="",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                  @OA\Property(property="uuid", type="string", format="uuid"),
     *                  @OA\Property(property="filename", type="string", example="view-632acb690b175.png"),
     *                  @OA\Property(property="type", type="string", example="image"),
     *             ),
     *         )
     *     )
     * )
     */
    #[Route('/api/files/{uuid}', name: 'api_file_uuid_view', methods: ['GET'])]
    public function index(FileService $fileService, $uuid): JsonResponse
    {
        $uuidVo = new Uuid($uuid);
        if (true !== $this->validateVO($uuidVo)) {
            return $this->validateVO($uuidVo);
        }
        return $fileService->getOneFileToJson($uuidVo->getValue());
    }
}
