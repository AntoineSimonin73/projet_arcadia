<?php

namespace App\Controller\Admin;

use App\Entity\Habitat;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichImageType;

class HabitatCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Habitat::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        $mappingsParams = $this->getParameter('vich_uploader.mappings');
        $imageMappings = $mappingsParams['images_habitats']['uri_prefix'];

        return [
            TextField::new('nom'),
            TextareaField::new('description'),
            TextAreaField::new('commentaire_habitat', 'Commentaires du vétérinaire')
                ->setRequired(false),
            TextareaField::new('imageFile', 'Images')->setFormType(VichImageType::class)->hideOnIndex(),
            ImageField::new('imageName', 'Images')->setBasePath($imageMappings)->hideOnForm(),
        ];
    }

}
