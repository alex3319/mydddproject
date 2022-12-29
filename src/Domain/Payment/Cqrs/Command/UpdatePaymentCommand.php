<?php

namespace App\Domain\Payment\Cqrs\Command;

use Symfony\Component\Validator\Constraints as Assert;

class UpdatePaymentCommand
{
    #[Assert\NotBlank]
    #[Assert\Uuid]
    private ?string $uuid;

    #[Assert\NotBlank]
    private ?string $userId;

    #[Assert\NotBlank]
    private ?string $orderId;

    #[Assert\NotBlank]
    private ?string $summ;

    #[Assert\NotBlank]
    private ?int $status;

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): void
    {
        $this->uuid = $uuid;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function setUserId(string $userId): void
    {
        $this->userId = $userId;
    }

    public function getOrderId(): string
    {
        return $this->orderId;
    }

    public function setOrderId(string $orderId): void
    {
        $this->orderId = $orderId;
    }

    public function getSumm(): string
    {
        return $this->summ;
    }

    public function setSumm(string $summ): void
    {
        $this->summ = $summ;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }
}
