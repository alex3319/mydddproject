<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Page;
use App\Entity\Product;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        // return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);

        return $this->redirect($adminUrlGenerator->setController(UserCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Панель администратора');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::section('Товары');
        yield MenuItem::linkToCrud('Категории', 'fa fa-tags', Category::class);
        yield MenuItem::linkToCrud('Товары', 'fa fa-tags', Product::class);

        yield MenuItem::section('Пользователи и группы');
        yield MenuItem::linkToCrud('Пользователи', 'fa fa-tags', User::class);

//        yield MenuItem::section('Контент');
//        yield MenuItem::linkToCrud('Страницы', 'fa fa-tags', Page::class);

//        yield MenuItem::linkToLogout('Logout', 'fa fa-exit');
        // yield MenuItem::linkToCrud('Blog Posts', 'fa fa-file-text', BlogPost::class);

//        yield MenuItem::section('Users');
//        yield MenuItem::linkToCrud('Comments', 'fa fa-comment', Comment::class);
//        yield MenuItem::linkToCrud('Users', 'fa fa-user', User::class);
//
//
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }

//    public function configureActions(): Actions
//    {
//        return parent::configureActions()
//            ->add(Crud::PAGE_INDEX, Action::DETAIL);
//    }
}
