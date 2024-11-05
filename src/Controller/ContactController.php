<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\HorairesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function contact(Request $request, MailerInterface $mailer, LoggerInterface $logger, HorairesRepository $horairesRepository): Response
    {
        $horaires = $horairesRepository->findAll();
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact, [
            'csrf_protection' => true,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $logger->info('Form is submitted and valid.');

            // Récupère les données du formulaire
            $contactData = $form->getData();

            // Création de l'email
            $email = (new Email())
                ->from($contactData->getEmail())  // L'email saisi par l'utilisateur
                ->to('asimonin73@gmail.com')   // Remplace par ton email ou un autre
                ->subject($contactData->getSujet())  // Sujet du message
                ->text($contactData->getMessage())  // Contenu du message
            ;

            // Envoi de l'email
            try {
                $mailer->send($email);
                $logger->info('Email sent successfully.');
                $this->addFlash('success', 'Votre message a bien été envoyé !');
            } catch (\Exception $e) {
                $logger->error('Failed to send email: ' . $e->getMessage());
                $this->addFlash('error', 'Il y a eu une erreur lors de l\'envoi de votre message.');
                return $this->redirectToRoute('app_contact');
            }

            // Redirection après envoi
            return $this->redirectToRoute('app_contact');
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $logger->info('Form is submitted but not valid.');
            $this->addFlash('error', 'Il y a eu une erreur lors de l\'envoi de votre message.');
        }

        return $this->render('contact/contact.html.twig', [
            'form' => $form->createView(),
            'horaires' => $horaires,
        ]);
    }
}