<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Repository\UserRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\{Action, Actions, Crud, KeyValueStore};
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\{ChoiceField, IdField, EmailField, TextField};
use Symfony\Component\Form\Extension\Core\Type\{ChoiceType, PasswordType, RepeatedType};
use Symfony\Component\Form\{FormBuilderInterface, FormError, FormEvent, FormEvents};
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCrudController extends AbstractCrudController
{
    private $userRepository;
    private $userPasswordHasher;

    public function __construct(UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userRepository = $userRepository;
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_EDIT, Action::INDEX)
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_EDIT, Action::DETAIL)
            ;
    }

    private function isAdminExists(): bool
    {
    $adminCount = $this->userRepository->createQueryBuilder('u')
        ->where('u.roles LIKE :role')
        ->setParameter('role', '%"ROLE_ADMIN"%')
        ->getQuery()
        ->getResult();

    return count($adminCount) > 0;
    }

    private function isEmailExists(string $email): bool
    {
    $existingUser = $this->userRepository->findOneBy(['email' => $email]);
    return $existingUser !== null;
    }

    public function configureFields(string $pageName): iterable
    {

    $fields = [
        IdField::new('id')->hideOnForm(),
        TextField::new('nom'),
        TextField::new('prenom'),
        EmailField::new('email'),
        ChoiceField::new('roles')
            ->setChoices([
                'Employé' => 'ROLE_EMPLOYE', 
                'Vétérinaire' => 'ROLE_VETERINAIRE', 
                'Administrateur' => 'ROLE_ADMIN'
            ])
            ->renderExpanded()
            ->setRequired(true)
            ->allowMultipleChoices(true)
            ->setFormTypeOption('empty_data', 'ROLE_USER'),
    ];
        
    $password = TextField::new('password')
        ->setFormType(RepeatedType::class)
        ->setFormTypeOptions([
            'type' => PasswordType::class,
            'first_options' => ['label' => 'Mot de passe',],
            'second_options' => ['label' => 'Répétez le mot de passe',],
            'mapped' => false,
        ])
        ->setRequired($pageName === Crud::PAGE_NEW)
        ->onlyOnForms()
        ;
    $fields[] = $password;

    return $fields;
    }

    public function createEditFormBuilder(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormBuilderInterface
    {
    $formBuilder = parent::createEditFormBuilder($entityDto, $formOptions, $context);

    $formBuilder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
        $user = $event->getData();
        $form = $event->getForm();

        if ($this->isEmailExists($user->getEmail()) && !$form->isEmpty()) {
            $form->get('email')->addError(new FormError('Cet email est déjà utilisé.'));
        }
    
    
        if ($form->has('roles')) {
            $roles = $form->get('roles')->getData(); // Assurez-vous que c'est toujours une chaîne
            $user->setRoles((array)$roles);
            
            // Vérifiez si un admin existe déjà
            if (in_array('ROLE_ADMIN', $roles) && $this->isAdminExists()) {
                $form->get('roles')->addError(new FormError('Un utilisateur avec le rôle Admin existe déjà.'));
            }
        }
    });

    return $formBuilder;
    }

    public function createNewFormBuilder(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormBuilderInterface
    {
        $formBuilder = parent::createNewFormBuilder($entityDto, $formOptions, $context);
        $formBuilder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            $user = $event->getData();
            $form = $event->getForm();

            if ($this->isEmailExists($user->getEmail()) && !$form->isEmpty()) {
                $form->get('email')->addError(new FormError('Cet email est déjà utilisé.'));
            }
        
    
            if ($form->has('roles')) {
                $roles = $form->get('roles')->getData(); // Assurez-vous que c'est toujours un tableau
                $roles = (array)$roles;
                $user->setRoles($roles);
            }

            if (in_array('ROLE_ADMIN', $roles) && $this->isAdminExists()) {
                $form->get('roles')->addError(new FormError('Un utilisateur avec le rôle Admin existe déjà.'));
            }
        });
        return $this->addPasswordEventListener($formBuilder);
    }

    private function addPasswordEventListener(FormBuilderInterface $formBuilder): FormBuilderInterface
    {
        return $formBuilder->addEventListener(FormEvents::POST_SUBMIT, $this->hashPassword());
    }

    private function hashPassword() {
        return function(FormEvent $event) {
            $form = $event->getForm();
            if (!$form->isValid()) {
                return;
            }
            
            $password = $form->get('password')->getData();
            if ($password === null) {
                return; // Pas de hash si le mot de passe n'est pas modifié
            }
    
            $user = $form->getData();
            $hash = $this->userPasswordHasher->hashPassword($user, $password);
            $user->setPassword($hash);
        };
    }
}