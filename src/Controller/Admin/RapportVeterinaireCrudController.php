<?php

namespace App\Controller\Admin;

use App\Entity\RapportVeterinaire;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeCrudActionEvent;
use Symfony\Bundle\SecurityBundle\Security as SecurityBundleSecurity;

class RapportVeterinaireCrudController extends AbstractCrudController implements EventSubscriberInterface
{
    private $security;
    private $requestStack;
    private $router;

    public function __construct(SecurityBundleSecurity $security, RequestStack $requestStack, RouterInterface $router)
    {
        $this->security = $security;
        $this->requestStack = $requestStack;
        $this->router = $router;
    }

    public static function getEntityFqcn(): string
    {
        return RapportVeterinaire::class;
    }

    public static function getSubscribedEvents()
    {
        return [
            BeforeCrudActionEvent::class => 'onBeforeCrudAction',
        ];
    }

    public function onBeforeCrudAction(BeforeCrudActionEvent $event): void
    {
        $request = $this->requestStack->getCurrentRequest();
        $action = $request->query->get('crudAction');
        if ($this->security->isGranted('ROLE_VETERINAIRE') && !in_array($action, ['index', 'detail', 'edit', 'new'])) {
            throw $this->createAccessDeniedException('Vous n\'avez pas les permissions nécessaires pour effectuer cette action.');
        }
    }
    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('etat', 'Etat de santé'),
            TextField::new('nourriture_proposee', 'Nourriture proposée'),
            NumberField::new('grammage', 'Grammage nourriture'),
            DateField::new('date', 'Date'),
            TextareaField::new('details', 'Détails')
                ->setRequired(false),
            AssociationField::new('animal', 'Animal'),
        ];
    }
    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(EntityFilter::new('animal'))
            ->add(DateTimeFilter::new('date'));
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setDefaultSort(['date' => 'DESC']);
    }
}