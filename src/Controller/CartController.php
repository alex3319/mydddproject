<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\CartProduct;
use App\Entity\Product;
use App\Form\CartType;
use App\Repository\CartRepository;
use App\Repository\CartProductRepository;
use App\Repository\ProductRelationRepository;
use App\Repository\ProductRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/cart')]
class CartController extends AbstractController
{
    // топор
    private $sessionId = 1;

    #[Route('/', name: 'app_cart_index', methods: ['GET'])]
    public function index(CartRepository $cartRepository, CartProductRepository $cartProductRepository, ProductRepository $productRepository): Response
    {
        // получаем корзину
        $cart = $cartRepository->findOneBy(['session_id' => $this->sessionId]);

        // итоговый массив товаров
        $products = [];

        if ($cart) {
            // получаем товары корзины
            $cartProducts = $cartProductRepository->findBy(['cartId' => $cart->getId()]);

            // получаем массив опций каждого товара
            foreach ($cartProducts as $cartProduct) {
                // получаем товар
                $product = $productRepository->find($cartProduct->getProductId());

                // извлекаем выбранные опции товара
                if ($options = json_decode($cartProduct->getOptions(), true)) {
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
                    'id' => $cartProduct->getId(),
                    'data' => $product,
                    'amount' => $cartProduct->getAmount(),
                    'options' => $options
                ];
            }
        }

        return $this->render('cart/index.html.twig', [
            'cart' => $cart,
            'products' => $products
        ]);
    }

    #[Route('/new', name: 'app_cart_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CartRepository $cartRepository, CartProductRepository $cartProductRepository): Response
    {
        $cart = new Cart();
        $form = $this->createForm(CartType::class, $cart);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cartRepository->add($cart, true);

            $relations = $cartProductRepository->findBy(['product' => $productId]);

            return $this->redirectToRoute('app_cart_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cart/new.html.twig', [
            'cart' => $cart,
            'form' => $form,
        ]);
    }

    #[Route('/product/add', name: 'app_cart_product_add', methods: ['POST'])]
    public function add(Request $request, CartRepository $cartRepository, CartProductRepository $cartProductRepository, ProductRepository $productRepository, ManagerRegistry $doctrine): Response
    {
        // входные параметры
        $productId = $request->get('product_id');
        $options = $request->get('options') ?? [];
        $amount = $request->get('amount');

        if ($options) {
            parse_str($options, $options);
            $options = $options['options'];
        }

        // преобразования
        $options = json_encode($options);

        // корзина
        $cart = $cartRepository->findOneBy(['session_id' => $this->sessionId]);

        if (!$cart) {
            $cart = new Cart();
            $cart->setSessionId($this->sessionId);
            $cart->setSumm(0);
            $cartRepository->add($cart, true);
        }

        $cartId = $cart->getId();

        // добавление товара в корзину
        $cartProduct = new CartProduct();
        $cartProduct->setCartId($cartId);
        $cartProduct->setProductId($productId);
        $cartProduct->setOptions($options);
        $cartProduct->setAmount($amount);
        $cartProductRepository->add($cartProduct, true);

        // высчитываем сумму корзины
        $product = $productRepository->findOneBy(['id' => $productId]);
        $productPrice = $product->getPrice();
        $cartSumm = $cart->getSumm() + $amount * $productPrice;

        // обновление значения
        $entityManager = $doctrine->getManager();
        $cart = $entityManager->getRepository(Cart::class)->find($cartId);
        $cart->setSumm($cartSumm);
        $entityManager->flush();

        // ответ
        $response = new Response();
        $response->setContent(json_encode([
            'success' => true
        ]));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    #[Route('/product/remove', name: 'app_cart_product_remove', methods: ['POST'])]
    public function remove(Request $request, CartRepository $cartRepository, CartProductRepository $cartProductRepository, ManagerRegistry $doctrine): Response
    {
        // входные параметры
        $cartProductId = $request->get('id');

        // корзина
        // $cart = $cartRepository->findOneBy(['session_id' => $this->sessionId]);

        // обновление значения
        $entityManager = $doctrine->getManager();
        $cartProduct = $entityManager->getRepository(CartProduct::class)->find($cartProductId);
        $entityManager->remove($cartProduct);
        $entityManager->flush();

        // ответ
        $response = new Response();
        $response->setContent(json_encode([
            'success' => true
        ]));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /*#[Route('/{id}', name: 'app_cart_delete', methods: ['POST'])]
    public function delete(Request $request, Cart $cart, CartRepository $cartRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cart->getId(), $request->request->get('_token'))) {
            $cartRepository->remove($cart, true);
        }

        return $this->redirectToRoute('app_cart_index', [], Response::HTTP_SEE_OTHER);
    }*/
}
