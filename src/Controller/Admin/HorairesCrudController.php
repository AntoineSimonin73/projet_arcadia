<?php

namespace App\Controller\Admin;

use App\Entity\Horaires;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class HorairesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Horaires::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('jour', 'Jour'),
            TextField::new('heure', 'Heure'),
        ];
    }

}