<?php

namespace App\Controller\Offres;

use App\Entity\Locations;
use App\Entity\Offres;
use App\Form\FormulaireCreerOffreType;


use App\Form\Offres\OffreType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class OffreController extends AbstractController
{
    #[Route('/offre/{id}', name: 'app_offre')]
    public function index(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        if (!$this->getUser())
            $this->redirectToRoute('app_login');


        $offre = $entityManager->getRepository(Offres::class)->find($id);
        if (!$offre) {
            throw $this->createNotFoundException('Offre non trouvée.');
        }

        $user = $this->getUser();
        $range = [];
        foreach ($offre->getLocations() as $location) {
            $range[] = [
                'dateDebut' => $location->getDateDebut(),
                'dateFin' => $location->getDateFin(),
            ];
        }

        $location = new Locations();
        $form = $this->createForm(OffreType::class, $location, [
            'offre' => $offre,
            'existing_ranges' => $range,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $location->setPrix(0);
            $location->setStatut(0);
            $location->setOffre($offre);
            $location->setLocataire($user);
            $entityManager->persist($location);
            $entityManager->flush();
            return $this->redirectToRoute('app_paiement', ['id' => $location->getId()]);
        }

        return $this->render('offres/page_offre.html.twig', [
            'form'  => $form->createView(),
            'controller_name' => 'OffreController',
            'offre' => $offre,
        ]);
    }
}
