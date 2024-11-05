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

class RapportVeterinaireCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RapportVeterinaire::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('etat', 'Etat de santé'),
            TextField::new('nourriture', 'Nourriture proposée'),
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