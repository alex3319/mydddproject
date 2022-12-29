<?php

declare(strict_types=1);

namespace App\Domain\Page\Repository;

use App\Domain\Application\Interfaces\Command;
use App\Domain\Page\Cqrs\Command\CreatePageCommand;
use App\Domain\Page\Cqrs\Command\UpdatePageCommand;
use App\Entity\Page;
use App\Repository\PageRepository;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Uuid;

class PageDomainRepository
{
    private EntityManagerInterface $em;
    private PageRepository $pageRepository;

    public function __construct(
        EntityManagerInterface $em,
        PageRepository $pageRepository
    ) {
        $this->em = $em;
        $this->pageRepository = $pageRepository;
    }

    public function getAllToArrayObjects(): array
    {
        return $this->pageRepository->findAll();
    }

    public function getAllToArray(): array
    {
        return $this->pageRepository->fetchAll();
    }

    public function findPageToUuid($uuid): ?Page
    {
        return $this->em->getRepository(Page::class)->find($uuid);
    }

    public function findPageToSlug($slug): ?Page
    {
        return $this->em->getRepository(Page::class)->findOneBy(['slug' => $slug]);
    }

    public function saveEntity(CreatePageCommand $command): bool
    {
        $page = new Page();
        $page->setUuid(new Uuid($command->getUuid()));
        $page->setName($command->getName());
        $page->setTitle($command->getTitle());
        $page->setDescription($command->getDescription());
        $page->setContent($command->getContent());
        if ($command->getH1()) {
            $page->setH1($command->getH1());
        }
        if ($command->getKeywords()) {
            $page->setKeywords($command->getKeywords());
        }
        if ($command->getSlug()) {
            $page->setSlug($command->getSlug());
        }

        return $this->save($page);
    }

    private function save(Page $entity): bool
    {
        try {
            $this->em->persist($entity);
            $this->em->flush();

            return true;
        } catch (Exception $exception) {
            // todo Нужно писать логи
            echo $exception->getMessage();

            return false;
        }
    }

    public function updateEntity(UpdatePageCommand $command): bool
    {
        $entity = $this->findPageToUuid($command->getUuid());
        if (!$entity) {
            return false;
        }

        // todo if (!$page) {} если не существует то что?

        if ($command->getName()) {
            $entity->setName($command->getName());
        }
        if ($command->getTitle()) {
            $entity->setTitle($command->getTitle());
        }
        if ($command->getDescription()) {
            $entity->setDescription($command->getDescription());
        }
        if ($command->getContent()) {
            $entity->setContent($command->getContent());
        }
        if ($command->getH1()) {
            $entity->setH1($command->getH1());
        }
        if ($command->getKeywords()) {
            $entity->setKeywords($command->getKeywords());
        }
        if ($command->getSlug()) {
            $entity->setSlug($command->getSlug());
        }

        return $this->update($entity);
    }

    private function update(Page $entity): bool
    {
        try {
            $this->em->persist($entity);
            $this->em->flush();

            return true;
        } catch (Exception $exception) {
            // todo Нужно писать логи
            echo $exception->getMessage();

            return false;
        }
    }

    public function deleteEntity(Command $command): bool
    {
        $entity = $this->findPageToUuid($command->getUuid());
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
            // todo Нужно писать логи
            echo $exception->getMessage();

            return false;
        }
    }
}
