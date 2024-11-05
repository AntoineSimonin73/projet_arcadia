<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Document\ConsultationAnimaux;
use App\Repository\HorairesRepository;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnimalController extends AbstractController
{
    #[Route('/animal/{id}', name: 'app_animal_show', requirements: ['id' => '\d+'])]
    public function show(
        Animal $animal,
        HorairesRepository $horairesRepository,
        DocumentManager $documentManager
    ): Response {
        // Create a new ConsultationAnimaux document
        $consultation = new ConsultationAnimaux();
        $consultation->setAnimalId((string) $animal->getId());
        $consultation->setConsultedAt(new \DateTime());

        $viewCount = $documentManager->getRepository(ConsultationAnimaux::class)
            ->createQueryBuilder()
            ->field('animalId')->equals((string) $animal->getId())
            ->count()
            ->getQuery()
            ->execute();

        // Save the document to MongoDB
        $documentManager->persist($consultation);
        $documentManager->flush();

        $rapportVeterinaire = $animal->getRapportsVeterinaires()->last();
        $horaires = $horairesRepository->findAll();

        return $this->render('animal/animalShow.html.twig', [
            'animal' => $animal,
            'rapportVeterinaire' => $rapportVeterinaire ?: null,
            'horaires' => $horaires,
            'viewCount' => $viewCount,
        ]);
    }
}