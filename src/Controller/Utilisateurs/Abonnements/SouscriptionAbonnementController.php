<?php

declare(strict_types=1);

namespace App\Controller\Utilisateurs\Abonnements;

use App\Entity\Abonnements;
use App\Form\Utilisateurs\AbonnementType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SouscriptionAbonnementController extends AbstractController
{
    #[Route('/abonnement/nouveau', name: 'app_souscription_abonnement')]
    public function index(EntityManagerInterface $entityManager, Request $request): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $abonnementsDispo = [
            [
                'type' => 'Journalier',
                'prix' => 10,
                'description' => 'Abonnement quotidien, valide 24 heures.',
                'temps' => 'jour',
            ],
            [
                'type' => 'Mensuel',
                'prix' => 40,
                'description' => 'Abonnement mensuel, valide pour 30 jours.',
                'temps' => 'mois',
            ],
            [
                'type' => 'Annuel',
                'prix' => 400,
                'description' => 'Abonnement annuel, valide pendant 365 jours.',
                'temps' => 'an',
            ],
        ];

        $actifs = $entityManager->getRepository(Abonnements::class)->findBy([
            'utilisateur' => $this->getUser(),
            'statut' => 1
        ]);

        $now = new \DateTime();
        $actifs = array_filter($actifs, function (Abonnements $abonnement) use ($now) {
            return $abonnement->getDateFin() >= $now;
        });

        $dureeRestante = 0;
        foreach ($actifs as $abonnement) {
            $diff = (new \DateTime())->diff($abonnement->getDateFin());
            $dureeRestante += max(0, $diff->days);
        }

        $abonnements = new Abonnements();
        $form = $this->createForm(AbonnementType::class, $abonnements);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $choixAbonnement = $form->get('type')->getData();
            $abonnements->setUtilisateur($this->getUser());
            $abonnements->setStatut(1);
            $abonnements->setDateDebut(new \DateTime());
            $abonnements->setDateFin($this->getDateFin($abonnements->getDateDebut(), $choixAbonnement));
            $abonnements->setPrix($abonnementsDispo[$choixAbonnement]['prix']);

            $entityManager->persist($abonnements);
            $entityManager->flush();

            return $this->redirectToRoute('app_abonnement');
        }

        $dateFin = null;
        if ($dureeRestante > 0) {
            $dateFin = (new \DateTime())->add(new \DateInterval('P' . $dureeRestante . 'D'));
        }
        return $this->render('abonnement/souscription.html.twig', [
            'abonnements' => $abonnements,
            'form' => $form->createView(),
            'duree' => $dureeRestante,
            'dateFin' => $dateFin,
            'abonnementDispo' => $abonnementsDispo,
        ]);
    }

    private function getDateFin(\DateTime $dateDebut, int $typeAbonnement): \DateTime
    {
        $dateFin = clone $dateDebut;
        switch ($typeAbonnement) {
            case 0:
                $dateFin->add(new \DateInterval('P1D'));
                break;
            case 1:
                $dateFin->add(new \DateInterval('P1M'));
                break;
            case 2:
                $dateFin->add(new \DateInterval('P1Y'));
                break;
        }

        return $dateFin;
    }
}
