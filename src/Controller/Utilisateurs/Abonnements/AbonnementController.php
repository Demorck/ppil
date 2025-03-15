<?php

namespace App\Controller\Utilisateurs\Abonnements;

use App\Repository\AbonnementsRepository;
use App\Repository\OffresRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AbonnementController extends AbstractController
{
    #[Route('/abonnement', name: 'app_abonnement')]
    public function index(AbonnementsRepository $abonnementRepo): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $abonnements = $abonnementRepo->findBy(['utilisateur' => $user]);

        return $this->render('abonnement/index.html.twig', [
            'abonnements' => $abonnements,
        ]);
    }
}
