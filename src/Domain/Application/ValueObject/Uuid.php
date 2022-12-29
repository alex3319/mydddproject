<?php

declare(strict_types=1);

namespace App\Domain\Application\ValueObject;

use Symfony\Component\Validator\Constraints as Assert;

class Uuid
{
    #[Assert\NotBlank]
    #[Assert\Uuid(message: 'Неверный UUID')]
    protected string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

//    public static function generate(): self
//    {
//        return new static(\Ramsey\Uuid\Uuid::uuid4()->toString());
//    }

    public function getPropertyPath(): ?string
    {
        return null;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function isEqual(self $value): bool
    {
        return $this->value === $value->getValue();
    }

    public function __toString(): string
    {
        return $this->getValue();
    }
}
