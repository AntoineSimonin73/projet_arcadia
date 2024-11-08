<?php
namespace App\Controller\Admin;

use App\Entity\Nourrissage;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Bundle\SecurityBundle\Security;

class NourrissageCrudController extends AbstractCrudController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public static function getEntityFqcn(): string
    {
        return Nourrissage::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        if ($this->security->isGranted('ROLE_VETERINAIRE')) {
            // Désactiver les actions non voulues pour les vétérinaires
            $actions->disable(Action::NEW);
            $actions->disable(Action::EDIT);
            $actions->disable(Action::DELETE);
        }

        return $actions;
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