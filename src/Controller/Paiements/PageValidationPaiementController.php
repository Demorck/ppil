<?php

namespace App\Controller\Paiements;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PageValidationPaiementController extends AbstractController
{
    #[Route('/validationPaiement', name: 'app_page_validation_paiement')]
    public function index(): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('paiements/validation_paiement.html.twig', [
            'controller_name' => 'PageValidationPaiementController',
        ]);
    }
}
