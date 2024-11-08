<?php
namespace App\Controller\Admin;

use App\Entity\Habitat;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Bundle\SecurityBundle\Security as SecurityBundleSecurity;
use Vich\UploaderBundle\Form\Type\VichImageType;

class HabitatCrudController extends AbstractCrudController
{
    private $security;

    public function __construct(SecurityBundleSecurity $security)
    {
        $this->security = $security;

    }

    public static function getEntityFqcn(): string
    {
        return Habitat::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        if ($this->security->isGranted('ROLE_VETERINAIRE')) {
            // Désactiver l'action "new" pour les vétérinaires
            $actions->disable(Action::NEW);
            $actions->disable(Action::DELETE);
        }

        return $actions;
    }

    public function configureFields(string $pageName): iterable
    {
        $mappingsParams = $this->getParameter('vich_uploader.mappings');
        $imageMappings = $mappingsParams['images_habitats']['uri_prefix'];

        if ($this->security->isGranted('ROLE_VETERINAIRE')) {
            if ($pageName === 'index') {
                // Afficher tous les champs mais non modifiables
                return [
                    TextField::new('nom')->setFormTypeOption('disabled', true),
                    TextareaField::new('description')->setFormTypeOption('disabled', true),
                    TextareaField::new('commentaire_habitat', 'Commentaires du vétérinaire')->setFormTypeOption('disabled', true),
                    TextareaField::new('imageFile', 'Image')->setFormType(VichImageType::class)->hideOnIndex(),
                ];
            } elseif ($pageName === 'edit') {
                // Afficher uniquement le champ commentaire_habitat pour les vétérinaires
                return [
                    TextField::new('nom')->setFormTypeOption('disabled', true),
                    TextareaField::new('description')->setFormTypeOption('disabled', true),
                    TextareaField::new('commentaire_habitat', 'Commentaires du vétérinaire')->setRequired(false),
                ];
            }
        }

        // Pour les autres utilisateurs, afficher tous les champs
        return [
            TextField::new('nom'),
            TextareaField::new('description'),
            TextareaField::new('commentaire_habitat', 'Commentaires du vétérinaire')->setRequired(false),
            TextareaField::new('imageFile', 'Image')->setFormType(VichImageType::class)->hideOnIndex(),
        ];
    }
}