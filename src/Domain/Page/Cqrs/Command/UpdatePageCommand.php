<?php

declare(strict_types=1);

namespace App\Domain\Page\Cqrs\Command;

use Symfony\Component\Validator\Constraints as Assert;

class UpdatePageCommand
{
    #[Assert\NotBlank]
    #[Assert\Uuid]
    private ?string $uuid;

    private ?string $name = null;

    private ?string $title = null;

    private ?string $description = null;

    private ?string $keywords = null;

    private ?string $h1 = null;

    private ?string $content = null;

    private ?string $slug = null;

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): void
    {
        $this->uuid = $uuid;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getKeywords(): ?string
    {
        return $this->keywords;
    }

    public function setKeywords(string $keywords): void
    {
        $this->keywords = $keywords;
    }

    public function getH1(): ?string
    {
        return $this->h1;
    }

    public function setH1(string $h1): void
    {
        $this->h1 = $h1;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }
}
