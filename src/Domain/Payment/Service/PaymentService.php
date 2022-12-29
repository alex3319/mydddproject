<?php

declare(strict_types=1);

namespace App\Domain\Payment\Service;

use App\Domain\Application\Interfaces\Command;
use App\Domain\Payment\Cqrs\Command\CreatePaymentCommand;
use App\Domain\Payment\Cqrs\Command\UpdatePaymentCommand;
use App\Domain\Payment\Repository\PaymentDomainRepository;
use App\Factory\JsonResponseFactory;
use Symfony\Component\HttpFoundation\JsonResponse;

class PaymentService
{

    private PaymentDomainRepository $paymentDomainRepository;
    private JsonResponseFactory $jsonResponseFactory;

    public function __construct(
        PaymentDomainRepository $paymentDomainRepository,
        JsonResponseFactory $jsonResponseFactory
    ) {
        $this->paymentDomainRepository = $paymentDomainRepository;
        $this->jsonResponseFactory = $jsonResponseFactory;
    }

    public function getAllToArrayObjects(): array
    {
        return $this->paymentDomainRepository->getAllToArrayObjects();
    }

    public function getAllToJson(): JsonResponse
    {
        $response = new JsonResponse($this->paymentDomainRepository->getAllToArray());
        $response->setEncodingOptions(JSON_UNESCAPED_UNICODE);

        return $response;
    }

    public function getOnePaymentToJson($uuid): JsonResponse
    {
        $payment = $this->paymentDomainRepository->findPaymentToUuid($uuid);

        return $this->jsonResponseFactory->create($payment);
    }

    public function updatePayment(UpdatePaymentCommand $updatePaymentCommand): bool
    {
        return $this->paymentDomainRepository->updateEntity($updatePaymentCommand);
    }

    public function savePayment(CreatePaymentCommand $createPaymentCommand): bool
    {
        return $this->paymentDomainRepository->saveEntity($createPaymentCommand);
    }
}
