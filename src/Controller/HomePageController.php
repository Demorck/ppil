<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use DateTime;

class HomePageController extends AbstractController
{
    #[Route('', name: 'app_home_page')]
    public function index(): Response
    {
        $dateDeb = (new DateTime())->format('Y-m-d');
        $dateFin = (new DateTime())->format('Y-m-d');

        if ($dateFin < $dateDeb) {
            $dateFin = clone $dateDeb;
        }

        return $this->render('home_page/index.html.twig', [
            'title' => "Accueil",
            'controller_name' => 'HomePageController',
            'dateDeb' => $dateDeb,
            'dateFin' => $dateFin
        ]);
    }
} 
