<?php

declare(strict_types=1);

namespace App\Controller\Api\Page;

use App\Controller\Main\BaseCQRSController;
use App\Domain\Page\Service\PageService;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ViewSlugController extends BaseCQRSController
{
    /**
     * @OA\Get(
     *     path="/api/page/{slug}",
     *     @OA\Parameter(required=true, name="slug", in="path", example="testovaya-stranitsa-9"),
     *     summary="Страница по slug",
     *     tags={"Pages"},
     *     description="Получение страницы по slug",
     *     @OA\Response(
     *         response="200",
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
     */
    #[Route('/api/page/{slug}', name: 'api_page_slug_view', methods: ['GET'])]
    public function findToSlug(PageService $pageService, $slug): JsonResponse
    {
        return $pageService->findPageToSlug($slug);
    }
}
