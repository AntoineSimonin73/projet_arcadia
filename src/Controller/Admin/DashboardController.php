<?php

namespace App\Controller\Admin;

use App\Entity\Animal;
use App\Entity\Avis;
use App\Entity\Habitat;
use App\Entity\Horaires;
use App\Entity\Nourrissage;
use App\Entity\Race;
use App\Entity\RapportVeterinaire;
use App\Entity\Service;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;

class DashboardController extends AbstractDashboardController
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

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
            ->setTitle('ZOO d\'Arcadia Admin');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Tableau de bord', 'fa fa-home');

        if ($this->security->isGranted('ROLE_EMPLOYE')) {
            yield MenuItem::linkToCrud('Avis', 'fas fa-comments', Avis::class);
            yield MenuItem::linkToCrud('Nourrissages', 'fas fa-list', Nourrissage::class);
            yield MenuItem::linkToCrud('Services', 'fa-solid fa-mug-hot', Service::class);

        } elseif ($this->security->isGranted('ROLE_VETERINAIRE')) {
            yield MenuItem::linkToCrud('Habitats', 'fa-solid fa-igloo', Habitat::class);
            yield MenuItem::linkToCrud('Rapports vétérinaire', 'fa-solid fa-notes-medical', RapportVeterinaire::class);
        } else {
            yield MenuItem::linkToCrud('Animaux', 'fa-solid fa-hippo', Animal::class);
            yield MenuItem::linkToCrud('Avis', 'fas fa-comments', Avis::class);
            yield MenuItem::linkToCrud('Habitats', 'fa-solid fa-igloo', Habitat::class);
            yield MenuItem::linkToCrud('Horaires', 'fa-solid fa-clock', Horaires::class);
            yield MenuItem::linkToCrud('Nourrissages', 'fas fa-list', Nourrissage::class);
            yield MenuItem::linkToCrud('Races', 'fa-solid fa-tag', Race::class);
            yield MenuItem::linkToCrud('Rapports vétérinaire', 'fa-solid fa-notes-medical', RapportVeterinaire::class);
            yield MenuItem::linkToCrud('Services', 'fa-solid fa-mug-hot', Service::class);
            yield MenuItem::linkToCrud('Utilisateurs', 'fa-solid fa-users', User::class);
        }
    }
}
