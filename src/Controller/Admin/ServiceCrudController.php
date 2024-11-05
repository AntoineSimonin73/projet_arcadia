<?php

namespace App\Controller\Admin;

use App\Entity\Service;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ServiceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Service::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $mappingsParams = $this->getParameter('vich_uploader.mappings');
        $imageMappings = $mappingsParams['images_services']['uri_prefix'];

        return [
            TextField::new('nom'),
            TextareaField::new('description'),
            TextareaField::new('imageFile', 'Images')->setFormType(VichImageType::class)->hideOnIndex(),
            ImageField::new('imageName', 'Images')->setBasePath($imageMappings)->hideOnForm(),
        ];
    }
}
