<?php

namespace App\Controller\Utilisateurs;

use App\Entity\Abonnements;
use App\Entity\Offres;
use App\Repository\AbonnementsRepository;
use App\Repository\OffresRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AbonnementController extends AbstractController
{
    #[Route('/abonnement', name: 'app_abonnement')]
public function index(AbonnementsRepository $abonnementRepo, OffresRepository $offreRepo): Response
{
    $user = $this->getUser();

    if (!$user) {
        // Rediriger vers la page de connexion ou afficher une erreur si l'utilisateur n'est pas connecté
        return $this->redirectToRoute('app_login');
    }

    $locations = $user->getLocations();

    $offres = [];
    foreach ($locations as $location) {
        foreach($location->getOffre() as $offre) {
            $offres[] = $offre;
        }
    }

    $abonnements = $abonnementRepo->findBy(['utilisateur' => $user]);

    return $this->render('abonnement/index.html.twig', [
        'abonnements' => $abonnements,
        'offres' => $offres,
    ]);
}

}
