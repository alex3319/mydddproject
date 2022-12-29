<?php

declare(strict_types=1);

namespace App\Domain\File\Cqrs\Command;

use App\Domain\File\Service\FileService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class UpdateFileCommandHandler
{
    private FileService $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function __invoke(UpdateFileCommand $updateFileCommand)
    {
        $this->fileService->updateFile($updateFileCommand);
    }
}
