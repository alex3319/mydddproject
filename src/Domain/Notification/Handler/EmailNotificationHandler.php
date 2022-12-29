<?php

namespace App\Domain\Notification\Handler;

use App\Domain\Notification\Command\EmailNotification;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;


#[AsMessageHandler]
class EmailNotificationHandler
{

    private LoggerInterface $logger;
    public function __construct(LoggerInterface $logger)
    {

        $this->logger = $logger;
    }

    public function __invoke(EmailNotification $message)
    {
        // ... do some work - like sending an SMS message!

        $this->logger->debug('Hello');
        return 'Done - ура работает!!!';
    }
}
