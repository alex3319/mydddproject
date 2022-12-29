<?php

declare(strict_types=1);

namespace App\Domain\Payment\Repository;

use App\Domain\Application\Interfaces\Command;
use App\Domain\Payment\Cqrs\Command\CreatePaymentCommand;
use App\Domain\Payment\Cqrs\Command\UpdatePaymentCommand;
use App\Entity\Payment;
use App\Repository\PaymentRepository;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Uuid;

class PaymentDomainRepository
{
    private EntityManagerInterface $em;
    private PaymentRepository $paymentRepository;

    public function __construct(
        EntityManagerInterface $em,
        PaymentRepository $paymentRepository
    ) {
        $this->em = $em;
        $this->paymentRepository = $paymentRepository;
    }

    public function getAllToArrayObjects(): array
    {
        return $this->paymentRepository->findAll();
    }

    public function getAllToArray(): array
    {
        return $this->paymentRepository->fetchAll();
    }

    public function findPaymentToUuid($uuid): ?Payment
    {
        return $this->em->getRepository(Payment::class)->find($uuid);
    }

    public function saveEntity(CreatePaymentCommand $command): bool
    {
        $payment = new Payment();
        $payment->setUuid(new Uuid($command->getUuid()));
        $payment->setUserId($command->getUserId());
        $payment->setOrderId($command->getOrderId());
        $payment->setSumm($command->getSumm());
        $payment->setStatus($command->getStatus());

        return $this->save($payment);
    }

    private function save(Payment $entity): bool
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

    public function updateEntity(UpdatePaymentCommand $command): bool
    {
        $entity = $this->findPaymentToUuid($command->getUuid());

        if (!$entity) {
            return false;
        }

        if ($command->getUserId()) {
            $entity->setUserId($command->getUserId());
        }

        if ($command->getOrderId()) {
            $entity->setOrderId($command->getOrderId());
        }

        if ($command->getSumm()) {
            $entity->setSumm($command->getSumm());
        }

        if ($command->getStatus()) {
            $entity->setStatus($command->getStatus());
        }

        return $this->update($entity);
    }

    private function update(Payment $entity): bool
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
}