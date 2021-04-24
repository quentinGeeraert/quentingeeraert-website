<?php

namespace App\Controller\EasyAdmin;

use App\Entity\Article;
use App\Entity\Experience;
use App\Entity\ProjectPortfolio;
use App\Entity\Skill;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_ADMIN", statusCode=403)
 */
class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/bo", name="bo_dashboard")
     */
    public function index(): Response
    {
        return $this->render('bundles/EasyAdminBundle/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Symfony Web App');
    }

    public function configureAssets(): Assets
    {
        return Assets::new()
            ->addCssFile('build/app.css');
    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::linktoDashboard('Dashboard', 'fa fa-home'),

            MenuItem::linkToCrud('Articles', 'fa fa-book', Article::class),

            MenuItem::subMenu('Personal', 'fa fa-address-card')->setSubItems([
                MenuItem::linkToCrud('Experiences', 'fa fa-project-diagram', Experience::class),
                MenuItem::linkToCrud('Skills', 'fa fa-project-diagram', Skill::class),
                MenuItem::linkToCrud('Portfolio', 'fa fa-project-diagram', ProjectPortfolio::class),
            ]),
        ];
    }
}
