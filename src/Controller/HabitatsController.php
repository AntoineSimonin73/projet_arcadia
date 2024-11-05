<?php

namespace App\Controller;

use App\Entity\Habitat;
use App\Repository\HabitatRepository;
use App\Repository\HorairesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HabitatsController extends AbstractController
{
    #[Route('/habitats', name: 'app_habitats')]
    public function index(HabitatRepository $habitatRepository, HorairesRepository $horairesRepository): Response
    {
        $habitats = $habitatRepository->findBy([], ['id' => 'ASC']);
        $horaires = $horairesRepository->findAll();

        return $this->render('habitats/habitats.html.twig', [
            'habitats' => $habitats,
            'horaires' => $horaires,
        ]);
    }

    #[Route('/habitats/{id}', name: 'app_habitat_show', requirements: ['id' => '\d+'])]
    public function show(Habitat $habitat,  HorairesRepository $horairesRepository): Response
    {
        $horaires = $horairesRepository->findAll();

        return $this->render('habitats/habitatDetails.html.twig', [
            'habitat' => $habitat,
            'horaires' => $horaires,
        ]);
    }
}