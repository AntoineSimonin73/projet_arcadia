<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Form\AvisType;
use App\Repository\AvisRepository;
use App\Repository\HabitatRepository;
use App\Repository\ServiceRepository;
use App\Repository\HorairesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    #[Route('/', name: 'app_accueil')]
    public function index(
        ServiceRepository $serviceRepository,
        HabitatRepository $habitatRepository,
        AvisRepository $avisRepository,
        HorairesRepository $horairesRepository,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $services = $serviceRepository->findBy([], ['id' => 'ASC']);
        $habitats = $habitatRepository->findBy([], ['id' => 'ASC']);
        $avis = $avisRepository->findLastTwoVisible();
        $horaires = $horairesRepository->findAll();

        $newAvis = new Avis();
        $form = $this->createForm(AvisType::class, $newAvis);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($newAvis);
            $entityManager->flush();

            $this->addFlash('success', 'Votre avis a été envoyé avec succès.');

            return $this->redirectToRoute('app_accueil');
        }

        return $this->render('accueil/accueil.html.twig', [
            'services' => $services,
            'habitats' => $habitats,
            'avis' => $avis,
            'form' => $form->createView(),
            'horaires' => $horaires,
        ]);
    }
}