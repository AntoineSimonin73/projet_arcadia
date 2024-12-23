<?php

namespace App\Controller\Admin;

use App\Entity\Animal;
use App\Repository\ConsultationRepository;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichImageType;

class AnimalCrudController extends AbstractCrudController
{
    public function getViewCount(int $animalId): int
    {
        return $this->consultationRepository->countByAnimalId($animalId);
    }

    private $consultationRepository;

    public function __construct(ConsultationRepository $consultationRepository)
    {
        $this->consultationRepository = $consultationRepository;
    }

    public static function getEntityFqcn(): string
    {
        return Animal::class;
    }

    
    public function configureFields(string $pageName, $entity = null): iterable
    {
        $mappingsParams = $this->getParameter('vich_uploader.mappings');
        $imageMappings = $mappingsParams['images_animaux']['uri_prefix'];

        return [
            TextField::new('nom'),
            AssociationField::new('race'),
            IntegerField::new('age'),
            AssociationField::new('habitat'),
            TextareaField::new('imageFile', 'Images')->setFormType(VichImageType::class)->hideOnIndex(),
        ];
    }
}
