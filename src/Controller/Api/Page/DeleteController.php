<?php

declare(strict_types=1);

namespace App\Controller\Api\Page;

use App\Controller\Main\BaseCQRSController;
use App\Domain\Page\Cqrs\Command\DeletePageCommand;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class DeleteController extends BaseCQRSController
{
    /**
     * @OA\Delete(
     *     path="/api/pages/{uuid}",
     *     summary="Удалить страницу",
     *     tags={"Pages"},
     *     description="Удаление страницы",
     *     @OA\RequestBody(
     *          request="pages",
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
     *         description="Возвращается uuid удаленной страницы",
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
    #[Route('/api/pages/{uuid}', name: 'api_page_delete', methods: ['DELETE'])]
    public function delete(Request $request, $uuid): JsonResponse
    {
        $fieldsArray = json_decode($request->getContent(), true);
        $command = $this->fieldsToDto($fieldsArray, DeletePageCommand::class, $uuid);

        return $this->validateDispatchAndRenderJson($command, self::TYPE_DELETE);
    }
}
