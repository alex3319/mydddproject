<?php

declare(strict_types=1);

namespace App\Domain\Payment\Cqrs\Command;

use App\Domain\Payment\Service\PaymentService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class UpdatePaymentCommandHandler
{
    private PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function __invoke(UpdatePaymentCommand $updatePaymentCommand)
    {
        $this->paymentService->updatePayment($updatePaymentCommand);
    }
}