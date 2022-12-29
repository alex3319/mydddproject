<?php

declare(strict_types=1);

namespace App\Controller\Api\File;

use App\Controller\Main\BaseCQRSController;
use App\Domain\File\Service\FileService;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends BaseCQRSController
{
    /**
     * @OA\Get(
     *     path="/api/files",
     *     summary="Все файлы",
     *     tags={"Files"},
     *     description="Все файлы",
     *     @OA\Response(
     *         response="200",
     *         description="",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  type="array",
     *                  @OA\Items(
     *                      @OA\Property(property="uuid", type="string", format="uuid", example="ba49eb3f-384f-409b-8642-96c9b8a38230"),
     *                      @OA\Property(property="filename", type="string", example="image-632c564d09421.png"),
     *                      @OA\Property(property="type", type="string", example="image"),
     *                  )
     *              ),
     *          ),
     *     )
     * )
     */
    #[Route('/api/files', name: 'api_file_index', methods: ['GET'])]
    public function index(FileService $fileService): JsonResponse
    {
        return $fileService->getAllToJson();
    }
}
