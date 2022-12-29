<?php
namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Repository\ProductRelationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/menu')]
class CategoryController extends AbstractController
{
    private $optionType = [
        1 => 'to_add',
        2 => 'to_remove',
        3 => 'options'
    ];

    #[Route('/', name: 'app_category_index', methods: ['GET'])]
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('category/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    //defaults: ["itemSlug"=>''] - делает этот параметр необязательным. Маршрут /slug - тоже работает.
    #[Route('/{slug}/{itemSlug}', name: 'app_category_show', defaults: ["itemSlug" => ''], methods: ['GET'])]
    public function showItem(ProductRepository $productRepository, ProductRelationRepository $productRelationRepository, CategoryRepository $categoryRepository, $slug, $itemSlug): Response
    {
        $category = $categoryRepository->findOneBy(['slug' => $slug]);

        if (is_null($category and empty($itemSlug))) {
            return $this->return404();
        }

        if ($category and empty($itemSlug)) {
            return $this->render('category/show.html.twig', [
                'category' => $category,
            ]);
        }

        // получение товара из базы
        $product = $productRepository->findOneBy(['slug' => $itemSlug]);

        if (is_null($product)) {
            return $this->return404();
        }

        // генерация массива опций (добавить/удалить/дополнительно)
        $options = [];
        $relations = $productRelationRepository->findBy(['source' => $product->getId()]);

        foreach ($relations as $r) {
            $optionType = $this->optionType[$r->getType()];

            if (!isset($options[$optionType])) {
                $options[$optionType] = [];
            }

            $options[$optionType][] = $productRepository->findOneBy(['id' => $r->getTarget()]);
        }

        // вывод шаблона
        return $this->render('category/show-product.html.twig', [
            'product' => $product,
            'category' => $category,
            'options' => $options
        ]);
    }

    private function return404(): Response
    {
        return $this->render('404.html.twig');
    }
}
