<?php

declare(strict_types=1);

namespace App\Domain\File\Service;

use App\Domain\Application\Interfaces\Command;
use App\Domain\File\Cqrs\Command\CreateFileCommand;
use App\Domain\File\Cqrs\Command\DeleteFileCommand;
use App\Domain\File\Cqrs\Command\UpdateFileCommand;
use App\Domain\File\Repository\FileDomainRepository;
use App\Factory\JsonResponseFactory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;

class FileService
{
    private FileDomainRepository $fileDomainRepository;
    private JsonResponseFactory $jsonResponseFactory;
    private $targetDirectory;

    public function __construct(
        FileDomainRepository $fileDomainRepository,
        JsonResponseFactory $jsonResponseFactory,
        $targetDirectory
    ) {
        $this->fileDomainRepository = $fileDomainRepository;
        $this->jsonResponseFactory  = $jsonResponseFactory;
        $this->targetDirectory      = $targetDirectory;
    }

    public function getAllToArrayObjects(): array
    {
        return $this->fileDomainRepository->getAllToArrayObjects();
    }

    public function getAllToJson(): JsonResponse
    {
        $response = new JsonResponse($this->fileDomainRepository->getAllToArray());
        $response->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        return $response;
    }

    public function getOneFileToJson($uuid): JsonResponse
    {
        $file = $this->fileDomainRepository->findFileToUuid($uuid);
        return $this->jsonResponseFactory->create($file);
    }

    public function updateFile(UpdateFileCommand $updateFileCommand): bool
    {
        return $this->fileDomainRepository->updateEntity($updateFileCommand);
    }

    public function deleteFile(DeleteFileCommand $deleteFileCommand): bool
    {
        $this->deleteFileFromDirectory($deleteFileCommand);
        return $this->fileDomainRepository->deleteEntity($deleteFileCommand);
    }

    public function saveFile(CreateFileCommand $createFileCommand): bool
    {
        return $this->fileDomainRepository->saveEntity($createFileCommand);
    }

    public function deleteFileFromDirectory(DeleteFileCommand $deleteFileCommand)
    {
        $file = $this->fileDomainRepository->findFileToUuid($deleteFileCommand->getUuid());
        $filename = $file->getFilename();
        $path = $this->targetDirectory . '/' . $filename;
        $filesystem = new Filesystem();
        $filesystem->remove([$path]);
    }
}
