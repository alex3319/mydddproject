<?php

declare(strict_types=1);

namespace App\Domain\Page\Cqrs\Command;

use App\Domain\Page\Service\PageService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class DeletePageCommandHandler
{
    private PageService $pageService;

    public function __construct(PageService $pageService)
    {
        $this->pageService = $pageService;
    }

    public function __invoke(DeletePageCommand $deletePageCommand)
    {
        $this->pageService->deletePage($deletePageCommand);
    }
}
