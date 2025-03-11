<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use DateTime;

class HomePageController extends AbstractController
{
    #[Route('', name: 'app_home_page')]
    #[Route('/abonnement', name: 'routeAbonnement')]
    public function index(): Response
    {
        $dateAujourdhui = (new DateTime())->format('Y-m-d');

        return $this->render('home_page/index.html.twig', [
            'title' => "Accueil",
            'controller_name' => 'HomePageController',
            'dateAujourdhui' => $dateAujourdhui
        ]);
    }

    public function abonnement(): Response
    {
        return $this->render('abonnement.html.twig');
    }
} 
