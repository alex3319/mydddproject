<?php

namespace App\Controller;

use App\Entity\CartProduct;
use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Form\OrderType;
use App\Repository\CartProductRepository;
use App\Repository\CartRepository;
use App\Repository\OrderRepository;
use App\Repository\OrderProductRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/order')]
class OrderController extends AbstractController
{
    #[Route('/', name: 'app_order_index', methods: ['GET'])]
    public function index(OrderRepository $orderRepository): Response
    {
        return $this->render('order/index.html.twig', [
            'orders' => $orderRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_order_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        OrderRepository $orderRepository,
        OrderProductRepository $orderProductRepository,
        CartRepository $cartRepository,
        CartProductRepository $cartProductRepository,
        ManagerRegistry $doctrine
    ): Response
    {
        $user = $this->getUser();
        $dateTIme = new \DateTime();

        if (!$user) {
            // ответ
            $response = new Response();
            $response->setContent(json_encode([
                'success' => false,
                'action' => 'login'
            ]));
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }

        // создание заказа
        $order = new Order();
        $order->setUserId($user->getId());
        $order->setStatusId(1);
        $order->setDatetime($dateTIme);
        $order->setAddress('пусто');
        $order->setSumm(0);
        $orderRepository->add($order, true);

        // получаем корзину
        $cart = $cartRepository->findOneBy(['session_id' => 1]);

        // получаем товары корзины
        $cartProducts = $cartProductRepository->findBy(['cartId' => $cart->getId()]);

        // получаем массив опций каждого товара
        foreach ($cartProducts as $cartProduct) {
            // добавление товара в корзину
            $orderProduct = new OrderProduct();
            $orderProduct->setOrderId($order->getId());
            $orderProduct->setProductId($cartProduct->getProductId());
            $orderProduct->setOptions($cartProduct->getOptions());
            $orderProduct->setAmount($cartProduct->getAmount());
            $orderProductRepository->add($orderProduct, true);
        }

        // очистка корзины
        $entityManager = $doctrine->getManager();
        $cartProducts = $entityManager->getRepository(CartProduct::class)->findBy(['cartId' => $cart->getId()]);
        foreach ($cartProducts as $cartProduct) {
            $entityManager->remove($cartProduct);
        }
        $entityManager->flush();

        // ответ
        $response = new Response();
        $response->setContent(json_encode([
            'success' => true,
            'order_id' => $order->getId()
        ]));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    #[Route('/{id}', name: 'app_order_show', methods: ['GET'])]
    public function show(Order $order): Response
    {
        return $this->render('order/show.html.twig', [
            'order' => $order,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_order_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Order $order, OrderRepository $orderRepository): Response
    {
        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $orderRepository->add($order, true);

            return $this->redirectToRoute('app_order_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('order/edit.html.twig', [
            'order' => $order,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_order_delete', methods: ['POST'])]
    public function delete(Request $request, Order $order, OrderRepository $orderRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$order->getId(), $request->request->get('_token'))) {
            $orderRepository->remove($order, true);
        }

        return $this->redirectToRoute('app_order_index', [], Response::HTTP_SEE_OTHER);
    }
}
