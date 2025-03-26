<?php

namespace App\Controller\Paiements;

use App\Entity\Locations;
use App\Entity\Paiements;
use App\Form\Paiements\FormulairePaiementType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class   PaiementController extends AbstractController
{
    #[Route('/location/{id}/paiement', name: 'app_paiement')]
    public function payer(Request $request, EntityManagerInterface $entMan, $id): Response
    {

        if ($this->getUser() === null) {
            return $this->redirectToRoute('app_login');
        }
        $currentUser = $this->getUser();
        $userid = $currentUser->getId();


        $location = $entMan->getRepository(Locations::class)->findOneBy(['id' => $id, 'locataire' => $userid]);

        if ($location === null) {
            return $this->redirectToRoute('app_home_page');
        }

        $testPaiement = $entMan->getRepository(Paiements::class)->findBy(['location' => $id]);
        if ($testPaiement != null) {
            return $this->redirectToRoute('app_home_page');
        }

        $paiement = new Paiements();

        $form = $this->createForm(FormulairePaiementType::class, $paiement);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $paiement->setLocation($location);
            $paiement->setDate(new \DateTimeImmutable());
            $paiement->setStatut(0);
            $paiement->setMontant($location->getPrix());

            $entMan->persist($paiement);
            $entMan->flush();
            return $this->redirectToRoute('app_page_validation_paiement');
        }

        $montant = $location->getPrix();

        $vehicule = $location->getOffre()->getVehicule();


        return $this->render('paiements/paiement.html.twig', [
            'controller_name' => 'PaiementController',
            'title' => 'Paiement',
            'paiementForm' => $form->createView(),
            'montant' => $montant,
            'vehicule' => $vehicule,
        ]);
    }

    #[Route('/location/{id}/voirPaiement', name: 'app_voir_paiement')]
    public function voirPaiement(Request $request, EntityManagerInterface $entMan, $id): Response
    {
        if ($this->getUser() === null) {
            return $this->redirectToRoute('app_login');
        }
        $currentUser = $this->getUser();
        $userid = $currentUser->getId();

        $location = $entMan->getRepository(Locations::class)->findOneBy(['id' => $id, 'locataire' => $userid]);

        if ($location === null) {
            return $this->redirectToRoute('app_home_page');
        }

        $paiement = $entMan->getRepository(Paiements::class)->findOneBy(['location' => $id]);

        if ($paiement === null) {
            return $this->redirectToRoute('app_home_page');
        }

        return $this->render('paiements/voir_paiement.html.twig', [
            'controller_name' => 'PaiementController',
            'title' => 'Voir Paiement',
            'paiement' => $paiement,
            'location' => $location,
        ]);
    }
}
