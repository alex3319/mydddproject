<?php

declare(strict_types=1);

namespace App\Domain\Application\Interfaces;

interface Command
{
    public function setUuid(string $uuid): void;

    public function getUuid(): string;
}
