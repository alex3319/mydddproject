<?php

declare(strict_types=1);

namespace App\Controller\Api\File;

use App\Controller\Main\BaseCQRSController;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use App\Domain\File\Service\FileUploader;

class UploadController extends BaseCQRSController
{
    /**
     * @OA\Post(
     *     path="/api/files/upload",
     *     summary="Загрузить файл",
     *     tags={"Files"},
     *     description="Загрузка файла. Перед созданием файла необходимо загрузить его.",
     *     @OA\RequestBody(
     *          required=true,
     *          request="files",
     *          description="Добавление данных производится в form-data формате",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                  required={"file"},
     *                  @OA\Property(property="file", type="file")
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description="Возвращается имя под которым файл загружен на сервер, в виде строки",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="filename", type="string", format="string"),
     *             ),
     *         )
     *     )
     * )
     * @throws ExceptionInterface
     */
    #[Route('/api/files/upload', name: 'api_files_upload', methods: ['POST'])]
    public function add(Request $request, FileUploader $fileUploader): JsonResponse
    {
        $uploadedFile = $request->files->get('file');
        if ($uploadedFile) {
            $filename = $fileUploader->upload($uploadedFile);
            return new JsonResponse(['filename' => $filename], $this::OK);
        } else {
            return new JsonResponse(['error' => 'file is required'], $this::OK);
        }
        return new JsonResponse(['error' => 'неизвестная ошибка'], $this::OK);
    }
}
