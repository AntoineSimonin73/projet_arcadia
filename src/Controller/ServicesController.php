<?php

namespace App\Controller;

use App\Repository\HorairesRepository;
use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServicesController extends AbstractController
{
    #[Route('/services', name: 'app_services')]
    public function index(ServiceRepository $serviceRepository, HorairesRepository $horairesRepository): Response
    {
        $services = $serviceRepository->findAll();
        $horaires = $horairesRepository->findAll();

        return $this->render('services/services.html.twig', [
            'services' => $services,
            'horaires' => $horaires,
        ]);
    }
}
