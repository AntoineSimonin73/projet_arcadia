<?php

namespace App\Controller;

use App\Repository\HorairesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/connexion', name: 'app_connexion')]
    public function login(AuthenticationUtils $authenticationUtils, HorairesRepository $horairesRepository ): Response
    {
        if ($this->getUser()) {
            $this->addFlash('success', 'Vous êtes maintenant connecté.');
            return $this->redirectToRoute('app_accueil');
        }

        $horaires = $horairesRepository->findAll();

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('connexion/connexion.html.twig', ['last_username' => $lastUsername, 'error' => $error, 'horaires' => $horaires, ]);
    }

    #[Route(path: '/deconnexion', name: 'app_deconnexion')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
