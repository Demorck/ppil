<?php

namespace App\Controller\Offres;

use App\Entity\Offres;
use App\Entity\Vehicules;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ListeOffresUtilisateursController extends AbstractController
{
    #[Route('/mes_offres', name: 'app_liste_offres_utilisateur')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($this->getUser() === null) {
            return $this->redirectToRoute('app_login');
        }
        $user = $this->getUser();

        $vehicules = $entityManager->getRepository(Vehicules::class)->findBy(['proprietaire' => $user]);
        $offres = $entityManager->getRepository(Offres::class)->findBy(['vehicule' => $vehicules]);

        return $this->render('offres/liste_offres_utilisateur.html.twig', [
            'controller_name' => 'ListeOffresController',
            'offres' => $offres,   
        ]);
    }
}
