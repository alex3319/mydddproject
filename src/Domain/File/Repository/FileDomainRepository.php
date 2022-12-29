<?php

declare(strict_types=1);

namespace App\Domain\File\Repository;

use App\Domain\Application\Interfaces\Command;
use App\Domain\File\Cqrs\Command\CreateFileCommand;
use App\Domain\File\Cqrs\Command\DeleteFileCommand;
use App\Domain\File\Cqrs\Command\UpdateFileCommand;
use App\Entity\FileStorage;
use App\Repository\FileStorageRepository;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Uuid;

class FileDomainRepository
{
    private EntityManagerInterface $em;
    private FileStorageRepository $fileRepository;

    public function __construct(
        EntityManagerInterface $em,
        FileStorageRepository $fileRepository
    ) {
        $this->em = $em;
        $this->fileRepository = $fileRepository;
    }

    public function getAllToArrayObjects(): array
    {
        return $this->fileRepository->findAll();
    }

    public function getAllToArray(): array
    {
        return $this->fileRepository->fetchAll();
    }

    public function findFileToUuid($uuid): ?FileStorage
    {
        //return $this->em->getRepository(FileStorage::class)->find($uuid);
        return $this->em->getRepository(FileStorage::class)
            ->findOneBy(['uuid' => $uuid]);
    }

    public function saveEntity(CreateFileCommand $command): bool
    {
        $file = new FileStorage();
        $file->setUuid(new Uuid($command->getUuid()));
        $file->setFilename($command->getFilename());
        $file->setType($command->getType());
        return $this->save($file);
    }

    private function save(FileStorage $entity): bool
    {
        try {
            $this->em->persist($entity);
            $this->em->flush();
            return true;
        } catch (Exception $exception) {
            echo $exception->getMessage();
            return false;
        }
    }

    public function updateEntity(UpdateFileCommand $command): bool
    {
        $entity = $this->findFileToUuid($command->getUuid());
        if (!$entity) {
            return false;
        }

        if ($command->getFilename()) {
            $entity->setFilename($command->getFilename());
        }
        if ($command->getType()) {
            $entity->setType($command->getType());
        }

        return $this->update($entity);
    }

    private function update(FileStorage $entity): bool
    {
        try {
            $this->em->persist($entity);
            $this->em->flush();
            return true;
        } catch (Exception $exception) {
            echo $exception->getMessage();
            return false;
        }
    }

    //public function deleteEntity(Command $command): bool
    public function deleteEntity(DeleteFileCommand $command): bool
    {
        $entity = $this->findFileToUuid($command->getUuid());
        if (!$entity) {
            return false;
        }
        return $this->delete($entity);
    }

    public function delete($entity): bool
    {
        try {
            $this->em->remove($entity);
            $this->em->flush();
            return true;
        } catch (Exception $exception) {
            echo $exception->getMessage();
            return false;
        }
    }

}
