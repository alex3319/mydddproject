<?php

declare(strict_types=1);

namespace App\Controller\Api\Page;

use App\Controller\Main\BaseCQRSController;
use App\Domain\Page\Service\PageService;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends BaseCQRSController
{
    /**
     * @OA\Get(
     *     path="/api/pages",
     *     summary="Все страницы",
     *     tags={"Pages"},
     *     description="Список страниц",
     *     @OA\Response(
     *         response="200",
     *         description="",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  type="array",
     *                  @OA\Items(
     *                      @OA\Property(property="uuid", type="string", format="uuid"),
     *                      @OA\Property(property="name", type="string", example="upd Доставка денеров"),
     *                      @OA\Property(property="title", type="string", example="upd Доставка денеров"),
     *                      @OA\Property(property="description", type="string", example="Денеры доставкой Красноярск"),
     *                      @OA\Property(property="keywords", type="string", example="upd доставка, шаурма, пицца"),
     *                      @OA\Property(property="h1", type="string", example="upd Заголовок title"),
     *                      @OA\Property(property="content", type="string",
     *                      example="uLorem ipsum dolor sit amet, consectetur adipisicing elit. Consectetur delectus,"),
     *                      @OA\Property(property="slug", type="string", example="testovaya-stranitsa")
     *                  )
     *              ),
     *          ),
     *     )
     * )
     */
    #[Route('/api/pages', name: 'api_page_index', methods: ['GET'])]
    public function index(PageService $pageService): JsonResponse
    {
        return $pageService->getAllToJson();
    }
}
