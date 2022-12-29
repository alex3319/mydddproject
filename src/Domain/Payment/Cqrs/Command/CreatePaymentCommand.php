<?php

declare(strict_types=1);

namespace App\Domain\Payment\Cqrs\Command;

use App\Domain\Application\Interfaces\Command;
use DateTimeInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;


class CreatePaymentCommand implements Command
{
    #[Assert\NotBlank]
    #[Assert\Uuid(message: 'Неверный UUID')]
    private string $uuid;

    #[Assert\NotBlank]
    private string $userId;

    #[Assert\NotBlank]
    private string $orderId;

    #[Assert\NotBlank]
    private string $summ;

    #[Assert\NotBlank]
    private int $status;

    /*#[Assert\NotBlank]
    private DateTimeInterface $datetime;*/

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

    /*public function getDatetime(): \DateTimeInterface
    {
        return $this->datetime;
    }

    public function setDatetime(\DateTimeInterface $datetime): void
    {
        $this->datetime = $datetime;
    }*/
}
