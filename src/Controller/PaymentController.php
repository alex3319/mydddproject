<?php

namespace App\Controller;

use App\Controller\Main\BaseCQRSController;
use App\Domain\Page\Cqrs\Command\CreatePaymentCommand;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PaymentController extends BaseCQRSController
{
    #[Route('/payment', name: 'app_payment')]
    public function index(): Response
    {
        $command = new CreatePaymentCommand();
        $this->bus->dispatch($command);

        return $this->render('payment/index.html.twig', [
            'controller_name' => 'PaymentController',
        ]);
    }
}
