<?php

namespace App\Controller\Litiges;

use App\Entity\Litiges;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class GererLitigeController extends AbstractController
{
    #[Route('/juriste/litige/gerer/{id}', name: 'app_gerer_litige')]
    public function index(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $litige = $entityManager->getRepository(Offres::class)->findBy(['id' => $id]);

        return $this->render('gerer_litige/index.html.twig', [
            'controller_name' => 'GererLitigeController',
            'litige' => $litige,
        ]);
    }
}
