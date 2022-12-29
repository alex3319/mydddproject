<?php

declare(strict_types=1);

namespace App\Domain\File\Cqrs\Command;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateFileCommand
{
    #[Assert\NotBlank]
    #[Assert\Uuid]
    private ?string $uuid;
    private ?string $filename = null;
    private ?string $type = null;

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): void
    {
        $this->uuid = $uuid;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): void
    {
        $this->filename = $filename;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType(string $type)
    {
        $this->type = $type;
    }

}
