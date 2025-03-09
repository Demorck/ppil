<?php

declare(strict_types=1);

namespace App\Controller\Utilisateurs;

use App\Entity\Abonnements;
use App\Form\Utilisateurs\AbonnementType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SouscriptionAbonnementController extends AbstractController
{
    #[Route('/abonnement/new', name: 'app_souscription_abonnement')]
    public function index(EntityManagerInterface $entityManager, Request $request): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $abonnements = [
            [
                'type' => 'Journalier',
                'prix' => 5,
                'description' => 'Abonnement quotidien, valide 24 heures.',
            ],
            [
                'type' => 'Hebdomadaire',
                'prix' => 15,
                'description' => 'Abonnement quotidien, valide 24 heures.',
            ],
            [
                'type' => 'Mensuel',
                'prix' => 40,
                'description' => 'Abonnement mensuel, valide pour 30 jours.',
            ],
            [
                'type' => 'Annuel',
                'prix' => 400,
                'description' => 'Abonnement annuel, valide pendant 365 jours.',
            ],
        ];

        $abonnements = new Abonnements();
        $form = $this->createForm(AbonnementType::class, $abonnements);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $choixAbonnement = $form->get('type')->getData();
            $abonnements->setUtilisateur($this->getUser());
            $abonnements->setStatut(1);
            $abonnements->setDateDebut(new \DateTime());
            $abonnements->setDateFin($this->getDateFin($abonnements->getDateDebut(), $choixAbonnement));
//            $abonnements->

            $entityManager->persist($abonnements);
            $entityManager->flush();

            return $this->redirectToRoute('app_abonnement');
        }


        return $this->render('abonnement/souscription.html.twig', [
            'abonnements' => $abonnements,
            'form' => $form->createView(),
        ]);
    }

    private function getDateFin(DateTime $dateDebut, int $typeAbonnement): DateTime
    {
        $dateFin = clone $dateDebut;
        switch ($typeAbonnement) {
            case 0:
                $dateFin->add(new \DateInterval('P1D'));
                break;
            case 1:
                $dateFin->add(new \DateInterval('P7D'));
                break;
            case 2:
                $dateFin->add(new \DateInterval('P1M'));
                break;
            case 3:
                $dateFin->add(new \DateInterval('P1Y'));
                break;
        }

        return $dateFin;
    }
}
