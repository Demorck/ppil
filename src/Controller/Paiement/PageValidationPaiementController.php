<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PageValidationPaiementController extends AbstractController
{
    #[Route('/validationPaiement', name: 'app_page_validation_paiement')]
    public function index(): Response
    {
        return $this->render('page_validation_paiement/index.html.twig', [
            'controller_name' => 'PageValidationPaiementController',
        ]);
    }
}
