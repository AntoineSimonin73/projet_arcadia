<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PageController extends AbstractController
{

    #[Route('templates/page/services.html.twig', name: 'app_services')]
    public function services(): Response
    {
        return $this->render('page/services.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }

    #[Route('/laSavane', name: 'app_laSavane')]
    public function laSavane(): Response
    {
        return $this->render('habitats/laSavane.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }

    #[Route('/laJungle', name: 'app_laJungle')]
    public function laJungle(): Response
    {
        return $this->render('habitats/laJungle.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }

    #[Route('/lesMarais', name: 'app_lesMarais')]
    public function lesMarais(): Response
    {
        return $this->render('habitats/lesMarais.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }

    #[Route('/melman', name: 'app_melman')]
    public function melman(): Response
    {
        return $this->render('habitats/animaux/melman.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }

    #[Route('templates/page/habitat/animaux/hathi', name: 'app_hathi')]
    public function hathi(): Response
    {
        return $this->render('page/habitat/animaux/hathi.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }

    #[Route('templates/page/habitat/animaux/baghera', name: 'app_baghera')]
    public function baghera(): Response
    {
        return $this->render('page/habitat/animaux/baghera.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }

    #[Route('templates/page/habitat/animaux/kaa', name: 'app_kaa')]
    public function kaa(): Response
    {
        return $this->render('page/habitat/animaux/kaa.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }

    #[Route('templates/page/habitat/animaux/ali', name: 'app_ali')]
    public function ali(): Response
    {
        return $this->render('page/habitat/animaux/ali.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }

    #[Route('templates/page/habitat/animaux/rosie', name: 'app_rosie')]
    public function rosie(): Response
    {
        return $this->render('page/habitat/animaux/rosie.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }
}
