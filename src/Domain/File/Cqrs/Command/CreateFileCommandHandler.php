<?php

declare(strict_types=1);

namespace App\Domain\File\Cqrs\Command;

//use App\Domain\File\Cqrs\Command\CreateFileCommand;
use App\Domain\File\Service\FileService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CreateFileCommandHandler
{
    private FileService $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function __invoke(CreateFileCommand $createFileCommand)
    {
         $this->fileService->saveFile($createFileCommand);
    }
}
