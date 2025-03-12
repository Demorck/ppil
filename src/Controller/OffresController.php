<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Offres;

final class OffresController extends AbstractController
{
    #[Route('/offres', name: 'app_offres')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($this->getUser() === null) {
            return $this->redirectToRoute('app_login');
        }

        $offres = $entityManager->getRepository(Offres::class)->findAll();

        return $this->render('offres/index.html.twig', [
            'controller_name' => 'OffresController',
            'offres' => $offres,
        ]);
    }
} 