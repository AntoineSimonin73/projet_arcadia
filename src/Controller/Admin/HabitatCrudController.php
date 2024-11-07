<?php

namespace App\Controller\Admin;

use App\Entity\Habitat;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Bundle\SecurityBundle\Security;

class HabitatCrudController extends AbstractCrudController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    
    public static function getEntityFqcn(): string
    {
        return Habitat::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        $mappingsParams = $this->getParameter('vich_uploader.mappings');
        $imageMappings = $mappingsParams['images_habitats']['uri_prefix'];

        if ($this->security->isGranted('ROLE_VETERINAIRE')) {
            return [
                TextareaField::new('commentaire_habitat', 'Commentaires du vétérinaire')
                    ->setRequired(false),
            ];
        }

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
