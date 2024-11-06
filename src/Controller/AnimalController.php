<?php

namespace App\Controller;

use App\Document\ConsultationAnimaux;
use App\Entity\Animal;
use App\Repository\HorairesRepository;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnimalController extends AbstractController
{
    #[Route('/animal/{id}', name: 'app_animal_show', requirements: ['id' => '\d+'])]
    public function show(Animal $animal, HorairesRepository $horairesRepository, DocumentManager $dm): Response
    {
        $click = new ConsultationAnimaux($animal->getId());
        $dm->persist($click);
        $dm->flush();

        $rapportVeterinaire = $animal->getRapportsVeterinaires()->last();
        $horaires = $horairesRepository->findAll();

        return $this->render('animal/animalShow.html.twig', [
            'animal' => $animal,
            'rapportVeterinaire' => $rapportVeterinaire ?: null,
            'horaires' => $horaires,
        ]);
    }
}