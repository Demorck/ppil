<?php

namespace App\Controller\Paiement;

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

        return $this->render('page_validation_paiement/index.html.twig', [
            'controller_name' => 'PageValidationPaiementController',
        ]);
    }
}
