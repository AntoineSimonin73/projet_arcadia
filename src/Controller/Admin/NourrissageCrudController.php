<?php
namespace App\Controller\Admin;

use App\Entity\Nourrissage;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeCrudActionEvent;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;

class NourrissageCrudController extends AbstractCrudController implements EventSubscriberInterface
{
    private $security;
    private $requestStack;
    private $router;

    public function __construct(Security $security, RequestStack $requestStack, RouterInterface $router)
    {
        $this->security = $security;
        $this->requestStack = $requestStack;
        $this->router = $router;
    }

    public static function getEntityFqcn(): string
    {
        return Nourrissage::class;
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
        $fields = [
            IdField::new('id')->hideOnForm()->hideOnIndex(),
            DateTimeField::new('date')->setLabel('Date et heure'),
        ];

        if ($pageName === 'index') {
            // Afficher Nourriture Proposée en tant que texte statique dans la vue liste
            $fields[] = TextField::new('rapportVeterinaire.nourriture_proposee', 'Nourriture Proposée');
            $fields[] = TextField::new('animal.nom', 'Animal');
        } else {
            // Utiliser le champ relationnel pour les autres vues (création, édition)
            $fields[] = AssociationField::new('rapportVeterinaire')
                ->setLabel('Nourriture Proposée')
                ->setFormTypeOption('choice_label', 'nourriture_proposee')
                ->setFormTypeOption('placeholder', 'Choisissez une nourriture proposée');
            $fields[] = AssociationField::new('Animal')
                ->setLabel('Animal')
                ->setFormTypeOption('choice_label', 'nom')
                ->setFormTypeOption('placeholder', 'Choisissez un animal');
        }
        
        // Ajouter le champ Grammage après Nourriture Proposée
        $fields[] = IntegerField::new('grammage')->setLabel('Grammage');

        return $fields;
    }
}