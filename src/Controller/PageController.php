<?php

namespace App\Controller;

use App\Domain\Page\Cqrs\Command\DeletePageCommand;
use App\Domain\Page\Service\PageService;
use App\Entity\Page;
use App\Entity\User;
use App\Form\CodeType;
use App\Form\RegistrationFormPhoneType;
use App\Form\RegistrationFormType;
use App\Repository\PageRepository;
use App\Security\AppCustomAuthenticator;
use App\Service\SmsCodeGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use LogicException;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\String\ByteString;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

#[Route('/')]
class PageController extends AbstractController
{
    private VerifyEmailHelperInterface $verifyEmailHelper;
    private MailerInterface $mailer;
    private RequestStack $requestStack;

    public function __construct(
        VerifyEmailHelperInterface $helper,
        MailerInterface $mailer,
        RequestStack $requestStack
    ) {
        $this->verifyEmailHelper = $helper;
        $this->mailer = $mailer;
        $this->requestStack = $requestStack;
    }

    #[Route('/page/delete', name: 'app_page_delete', methods: ['GET'])]
    public function delete(PageService $pageService): Response
    {
        $dpc = new DeletePageCommand();
        $dpc->setUuid('df0edb77-8d5b-4c3a-86b5-29cde725919b');

        $deleteRes = $pageService->deletePage($dpc);

        return $this->render('page/index.html.twig', [
            'pages' => $pageService->getAllToArrayObjects(),
        ]);
    }

    public function showArgs(...$args)
    {
        dd($args);
    }

    /**
     * @Route("/page/test", name="test")
     */
    public function test(EventDispatcherInterface $dispatcher): Response
    {
        $dispatcher->addListener('dump_event_args', [$this, 'showArgs']);
        $dispatcher->dispatch($this, 'dump_event_args');

        return new Response('<p>Test events</p>');
    }

    #[Route('/page', name: 'app_page_index', methods: ['GET'])]
    public function index(PageService $pageService): Response
    {
        return $this->render('page/index.html.twig', [
            'pages' => $pageService->getAllToArrayObjects(),
        ]);
    }

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        UserAuthenticatorInterface $userAuthenticator,
        AppCustomAuthenticator $authenticator,
        EntityManagerInterface $entityManager,
        ManagerRegistry $doctrine
    ): Response {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        // вход по почте
        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $entityManager->persist($user);
            $entityManager->flush();
            $signatureComponents = $this->verifyEmailHelper->generateSignature(
                'app_register_email_verify',
                $user->getId(),
                $user->getEmail()
            );
            $email = new TemplatedEmail();
            $email->from('send@example.com');
            $email->to($user->getEmail());
            $email->htmlTemplate('registration/confirmation_email.html.twig');
            $signedUrl = $signatureComponents->getSignedUrl();
            $email->context([
                'user' => $user,
                'signedUrl' => $signedUrl,
            ]);
            $this->mailer->send($email);

            return $this->render('registration/email_sended.html.twig', []);

            /*
            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
            */
        }

        // вход по номеру телефона
        $form2 = $this->createForm(RegistrationFormPhoneType::class, $user);
        $form2->handleRequest($request);
        if ($form2->isSubmitted() && $form2->isValid()) {
            $phone = $form2->get('phone')->getData();

            $repository = $doctrine->getRepository(User::class);
            $user = $repository->findOneBy(['phone' => $phone]);

            if (!$user) {
                // если пользователь новый
                $user = new User();
                $user = $form2->getData();
            }

            // Записать смс код
            $generator = new SmsCodeGenerator();
            $code = $generator->getCode();
            $user->setConfirmationCode($code);

            // поле password не может быть null
            $password = ByteString::fromRandom(32)->toString();
            // $user->setPassword($password);
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $password
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            // $this->smsSender->send($code);

            $session = $this->requestStack->getSession();
            $session->set('phone', $phone);

            // редирект
            return $this->redirectToRoute('app_register_code');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
            'registrationPhoneForm' => $form2->createView(),
        ]);
    }

    #[Route('/verify', name: 'app_register_email_verify')]
    public function verifyUserEmail(
        Request $request,
        EntityManagerInterface $entityManager,
        // UserAuthenticatorInterface $userAuthenticator,
        // AppCustomAuthenticator $authenticator
    ): Response {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();

        // Do not get the User's Id or Email Address from the Request object
        try {
            $this->verifyEmailHelper->validateEmailConfirmation($request->getUri(), $user->getId(), $user->getEmail());
        } catch (VerifyEmailExceptionInterface $e) {
            $this->addFlash('verify_email_error', $e->getReason());

            return $this->redirectToRoute('app_register');
        }

        // Mark your user as verified. e.g. switch a User::verified property to true
        $user->setConfirmed(true);
        $entityManager->persist($user);
        $entityManager->flush();

        $this->addFlash('success', 'Your e-mail address has been verified.');

        // return $userAuthenticator->authenticateUser(
        //    $user,
        //    $authenticator,
        //    $request
        // );

        return $this->redirectToRoute('app_profile_index');
        // return $this->redirectToRoute('app_login');
    }

    #[Route('/code', name: 'app_register_code')]
    public function code(
        Request $request,
        EntityManagerInterface $entityManager,
        ManagerRegistry $doctrine,
        UserAuthenticatorInterface $userAuthenticator,
        AppCustomAuthenticator $authenticator
    ): Response {
        $session = $this->requestStack->getSession();
        $phone = $session->get('phone');

        $repository = $doctrine->getRepository(User::class);

        $user = $repository->findOneBy(['phone' => $phone]);
        $code1 = $user->getConfirmationCode();

        $codeForm = $this->createForm(CodeType::class);
        $codeForm->handleRequest($request);

        if ($codeForm->isSubmitted() && $codeForm->isValid()) {
            $code2 = $codeForm->get('code')->getData();
            if ($code1 == $code2) {
                // TO DO действия по аунтентификации  пользователя
                // сделать пользователя  аутентифицированным
                return $userAuthenticator->authenticateUser(
                    $user,
                    $authenticator,
                    $request
                );

                // Редирект  на профиль
                return $this->redirectToRoute('app_profile_index');
            } else {
                return $this->redirectToRoute('app_register');
            }
        }

        return $this->render('registration/code.html.twig', [
            'codeForm' => $codeForm->createView(),
        ]);
    }

//    /**
//     * @Route("/page/add", name="add_post")
//     */
//    public function addPost(Request $request): Response
//    {
//        $form = $this->createForm(PageType::class);
//
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $pageId = Uuid::v4()->toRfc4122();
//
//
//
//            $this->pageService->addPage(
//                new AddPageCommand(
//                    $blogPostId,
//                    $form->get('title')->getData(),
//                    $form->get('content')->getData(),
//                    $parameterBag->getImageTempDir().'/'.$imageTempName
//                )
//            );
//
//            return $this->redirectToRoute('web_blog_success_added_post', ['blogPostId' => $blogPostId]);
//        }
//
//        return $this->render('@blog/add-blog-post.html.twig', [
//            'form' => $form->createView(),
//        ]);
//    }

//    /**
//     * @Route("/success", name="success_added_post")
//     */
//    public function successAddedPost(Request $request): Response
//    {
//        return $this->render('@blog/success-added-blog-post.html.twig',
//            ['blogPostId' => $request->get('blogPostId')]
//        );
//    }

    #[Route('/payment', name: 'app_payment_index', methods: ['GET'])]
    public function payment(PageRepository $pageRepository): Response
    {
        return $this->render('page/payment/index.html.twig');
    }

    #[Route('/payment/success', name: 'app_payment_success', methods: ['GET'])]
    public function paymentSuccess(PageRepository $pageRepository): Response
    {
        return $this->render('page/payment/success.html.twig');
    }

    #[Route('/payment/fail', name: 'app_payment_fail', methods: ['GET'])]
    public function paymentFail(PageRepository $pageRepository): Response
    {
        return $this->render('page/payment/fail.html.twig');
    }



    #[Route('/page/{slug}', name: 'app_page_show', methods: ['GET'])]
    public function show(Page $page): Response
    {
        return $this->render('page/show.html.twig', [
            'page' => $page,
        ]);
    }
}
