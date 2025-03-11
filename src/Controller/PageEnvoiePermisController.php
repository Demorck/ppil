<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PageEnvoiePermisController extends AbstractController
{
    #[Route('/envoie-permis', name: 'app_page_envoie_permis')]
    public function index(): Response
    {
        //if ($this->getUser() === null) {
        //    return $this->redirectToRoute('app_login');
        //}
        return $this->render('page_envoie_permis/index.html.twig', [
            'controller_name' => 'PageEnvoiePermisController',
        ]);
    }
}
