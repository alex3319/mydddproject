<?php

declare(strict_types=1);

namespace App\Domain\File\Cqrs\Command;

use App\Domain\Application\Interfaces\Command;
use Symfony\Component\Validator\Constraints as Assert;

class DeleteFileCommand implements Command
{
    #[Assert\NotBlank]
    #[Assert\Uuid]
    private string $uuid;

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): void
    {
        $this->uuid = $uuid;
    }
}
