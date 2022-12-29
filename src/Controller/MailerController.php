<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailerController extends AbstractController
{
    #[Route('/email')]
    public function sendEmail(MailerInterface $mailer): Response {
        $email = (new Email())
            ->from('hello@example.com')
            ->to('you@example.com')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html('<p>See Twig integration for better HTML integration!</p>');

        //$mailer->send($email);

        try {
            $mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            echo 'error';
            // some error prevented the email sending; display an
            // error message or try to resend the message
        }

        // ...

        $result = 'success';

        return $this->render('profile/result.html.twig', [
            'controller_name' => 'MailerController',
            'result'          => $result,
        ]);

    }
}
