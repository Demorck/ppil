<?php

namespace App\Controller\Offres;

use App\Entity\Offres;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ListeOffresController extends AbstractController
{
    #[Route('/offres', name: 'app_liste_offres')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $nbPlace = $request->query->get('nbPlace');
        $typeCarburant= $request->query->get('typeCarburant');
        $dateDebut = $request->query->get('dateDebut');
        $dateFin = $request->query->get('dateFin');     
        
        $offres = $entityManager->getRepository(Offres::class)->findByFilters($nbPlace, $dateDebut, $dateFin, $typeCarburant);

        if (!$offres) {
            $this->addFlash('error', 'Aucune offre ne correspond à vos critères de recherche.');
            return $this->redirectToRoute('app_home_page');
        }

        return $this->render('offres/liste_offres.html.twig', [
            'controller_name' => 'OffresController',
            'offres' => $offres,
        ]);
    }
} 