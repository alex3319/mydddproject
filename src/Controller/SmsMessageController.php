<?php

namespace App\Controller;

use App\Entity\SmsMessage;
use App\Form\SmsMessageType;
use App\Repository\SmsMessageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/sms')]
class SmsMessageController extends AbstractController
{
    #[Route('/', name: 'app_sms_message_index', methods: ['GET'])]
    public function index(SmsMessageRepository $smsMessageRepository): Response
    {
        return $this->render('sms_message/index.html.twig', [
            'sms_messages' => $smsMessageRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_sms_message_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SmsMessageRepository $smsMessageRepository): Response
    {
        $smsMessage = new SmsMessage();
        $form = $this->createForm(SmsMessageType::class, $smsMessage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $smsMessageRepository->add($smsMessage, true);

            return $this->redirectToRoute('app_sms_message_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sms_message/new.html.twig', [
            'sms_message' => $smsMessage,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_sms_message_show', methods: ['GET'])]
    public function show(SmsMessage $smsMessage): Response
    {
        return $this->render('sms_message/show.html.twig', [
            'sms_message' => $smsMessage,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_sms_message_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SmsMessage $smsMessage, SmsMessageRepository $smsMessageRepository): Response
    {
        $form = $this->createForm(SmsMessageType::class, $smsMessage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $smsMessageRepository->add($smsMessage, true);

            return $this->redirectToRoute('app_sms_message_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sms_message/edit.html.twig', [
            'sms_message' => $smsMessage,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_sms_message_delete', methods: ['POST'])]
    public function delete(Request $request, SmsMessage $smsMessage, SmsMessageRepository $smsMessageRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$smsMessage->getId(), $request->request->get('_token'))) {
            $smsMessageRepository->remove($smsMessage, true);
        }

        return $this->redirectToRoute('app_sms_message_index', [], Response::HTTP_SEE_OTHER);
    }
}
