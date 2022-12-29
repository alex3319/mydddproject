<?php

declare(strict_types=1);

namespace App\Domain\File\Cqrs\Command;

use App\Domain\Application\Interfaces\Command;
use Symfony\Component\Validator\Constraints as Assert;

class CreateFileCommand implements Command
{
    #[Assert\NotBlank]
    #[Assert\Uuid(message: 'Неверный UUID')]
    private string $uuid;

    #[Assert\NotBlank(message: 'Поле не может быть пустым')]
    private string $filename;

    #[Assert\NotBlank(message: 'Поле не может быть пустым')]
    private string $type;

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): void
    {
        $this->uuid = $uuid;
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): void
    {
        $this->filename = $filename;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }
}
