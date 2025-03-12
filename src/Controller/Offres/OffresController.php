<?php

namespace App\Controller\Offres;

use App\Entity\Offres;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class OffresController extends AbstractController
{
    #[Route('/offres', name: 'app_offres')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $offres = $entityManager->getRepository(Offres::class)->findBy(['statut' => 1]);

        return $this->render('offres/index.html.twig', [
            'controller_name' => 'OffresController',
            'offres' => $offres,
        ]);
    }
} 