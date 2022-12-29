<?php

namespace App\Controller;

use App\Repository\OrderProductRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\UserType;

#[Route('/profile')]
class ProfileController extends AbstractController
{
    #[Route('/', name: 'app_profile_index')]
    public function index(): Response
    {
        $user = $this->getUser();
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
            'user'            => $user,
        ]);
    }

    #[Route('/orders', name: 'app_profile_orders')]
    public function orders(OrderRepository $orderRepository): Response
    {
        $user = $this->getUser();
        $orders = $orderRepository->findBy(['userId' => $user->getId()]);

        return $this->render('profile/orders.html.twig', [
            'user'            => $user,
            'orders'          => $orders,
        ]);
    }

    #[Route('/order/{id}', name: 'app_profile_order')]
    public function order($id, OrderRepository $orderRepository, OrderProductRepository $orderProductRepository, ProductRepository $productRepository): Response
    {
        $user = $this->getUser();
        $order = $orderRepository->find($id);

        if ($order) {
            // получаем товары корзины
            $orderProducts = $orderProductRepository->findBy(['orderId' => $order->getId()]);

            // получаем массив опций каждого товара
            foreach ($orderProducts as $orderProduct) {
                // получаем товар
                $product = $productRepository->find($orderProduct->getProductId());

                // извлекаем выбранные опции товара
                if ($options = json_decode($orderProduct->getOptions(), true)) {
                    // id всех опций для единой выборки
                    $allOptionsIds = [];

                    foreach ($options as $optionsIds) {
                        $allOptionsIds = array_merge($allOptionsIds, $optionsIds);
                    }

                    // исключаем повторы
                    $allOptionsIds = array_unique($allOptionsIds);

                    // выбираем все нужные опции
                    $allOptions = $productRepository->findBy(['id' => $allOptionsIds]);

                    // замена
                    foreach ($options as $groupKey => $optionsIds) {
                        foreach ($optionsIds as $optionIndex => $optionId) {
                            foreach ($allOptions as $option) {
                                if ($optionId == $option->getId()) {
                                    $options[$groupKey][$optionIndex] = $option;
                                }
                            }
                        }
                    }
                }

                $products[] = (object) [
                    'id' => $orderProduct->getId(),
                    'data' => $product,
                    'amount' => $orderProduct->getAmount(),
                    'options' => $options
                ];
            }

            return $this->render('profile/order.html.twig', [
                'user' => $user,
                'order' => $order,
            ]);
        } else {
            throw $this->createNotFoundException('The order does not exist');
        }
    }

    #[Route('/user', name: 'app_profile_user')]
    public function user(ManagerRegistry $doctrine, Request $request): Response
    {
        $user = $this->getUser();
        $entityManager = $doctrine->getManager();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $entityManager->persist($user);
            $entityManager->flush();
        }

        return $this->renderForm('profile/user.html.twig', [
            'form' => $form,
            'user' => $user,
        ]);
    }
}
