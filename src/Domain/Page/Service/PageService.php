<?php

declare(strict_types=1);

namespace App\Domain\Page\Service;

use App\Domain\Application\Interfaces\Command;
use App\Domain\Page\Cqrs\Command\CreatePageCommand;
use App\Domain\Page\Cqrs\Command\UpdatePageCommand;
use App\Domain\Page\Repository\PageDomainRepository;
use App\Factory\JsonResponseFactory;
use Symfony\Component\HttpFoundation\JsonResponse;

class PageService
{

    private PageDomainRepository $pageDomainRepository;
    private JsonResponseFactory $jsonResponseFactory;

    public function __construct(
        PageDomainRepository $pageDomainRepository,
        JsonResponseFactory $jsonResponseFactory
    ) {
        $this->pageDomainRepository = $pageDomainRepository;
        $this->jsonResponseFactory = $jsonResponseFactory;
    }

    public function getAllToArrayObjects(): array
    {
        return $this->pageDomainRepository->getAllToArrayObjects();
    }

    public function getAllToJson(): JsonResponse
    {
        $response = new JsonResponse($this->pageDomainRepository->getAllToArray());
        $response->setEncodingOptions(JSON_UNESCAPED_UNICODE);

        return $response;
    }

    public function getOnePageToJson($uuid): JsonResponse
    {
        $page = $this->pageDomainRepository->findPageToUuid($uuid);

        return $this->jsonResponseFactory->create($page);
    }

    public function findPageToSlug($slug): JsonResponse
    {
        $page = $this->pageDomainRepository->findPageToSlug($slug);

        return $this->jsonResponseFactory->create($page);
    }

    public function updatePage(UpdatePageCommand $updatePageCommand): bool
    {
        return $this->pageDomainRepository->updateEntity($updatePageCommand);
    }

    public function deletePage(Command $deletePageCommand): bool
    {
        return $this->pageDomainRepository->deleteEntity($deletePageCommand);
    }

    public function savePage(CreatePageCommand $createPageCommand): bool
    {
        return $this->pageDomainRepository->saveEntity($createPageCommand);
    }
}
