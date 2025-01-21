<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class FormulaireGererOffreController extends AbstractController
{
    #[Route('/formulaire/gerer/offre', name: 'app_formulaire_gerer_offre')]
    public function index(): Response
    {
        return $this->render('formulaire_gerer_offre/index.html.twig', [
            'controller_name' => 'FormulaireGererOffreController',
        ]);
    }
}
