<?php

namespace App\Controller\Admin;

use App\Entity\User;
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
        // redirect to some CRUD controller
        $routeBuilder = $this->get(AdminUrlGenerator::class);

        return $this->redirect($routeBuilder->setController(UserCrudController::class)->generateUrl().'&menuIndex=0');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setFaviconPath('images/admin-keys.png')
            ->setTitle('UserManag')
            ->disableUrlSignatures();
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToCrud('users', 'fas fa-user', User::class);
        yield MenuItem::linkToRoute('Homepage', 'fas fa-door-open', "homepage");
        yield MenuItem::section('');
        yield MenuItem::linkToLogout('Log out', 'fa fa-sign-out');
    }
}
