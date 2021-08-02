<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\Image;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        $routeBuilder = $this->get(AdminUrlGenerator::class);
        return $this->redirect($routeBuilder->setController(ArticleCrudController::class)->set('menuIndex', '1')
            ->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle(
                'Back office - Amon Passage Secret'
            )
            ->setFaviconPath('build/images/favicon.ico');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoRoute('Retour au site', 'fa fa-home', 'home');
        yield MenuItem::linkToCrud('Articles', 'fa fa-file-invoice', Article::class);
        yield MenuItem::linkToCrud('Images', 'fa fa-image', Image::class);
        yield MenuItem::linkToLogout('Déconnexion', 'fa fa-sign-out-alt');
    }

    public function configureAssets(): Assets
    {
        return Assets::new()->addWebpackEncoreEntry('admin');
    }
}
