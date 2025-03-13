<?php

namespace App\Controller\Permis;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PermisValidationController extends AbstractController
{
    #[Route('/permis/validation', name: 'app_permis_validation')]
    public function index(): Response
    {
        $user = $this->getUser();

        if(!$user){
            return $this->redirectToRoute('app_login');
        }

        return $this->render('permis/validation_permis.html.twig', [
            'controller_name' => 'PermisValidationController',
        ]);
    }
}
