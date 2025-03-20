<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\OffresRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ListeOffresController extends AbstractController
{
    #[Route('/liste/offres', name: 'app_liste_offres_utilisateur_souscrit')]
    public function index(OffresRepository $offreRepo): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $locations = $user->getLocations();

        return $this->render('liste_offres/index.html.twig', [
            'controller_name' => 'ListeOffresController',
            'locations' => $locations,
        ]);
    }
}
