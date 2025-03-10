<?php

namespace App\Controller\Paiements;

use App\Entity\Locations;
use App\Entity\Paiements;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ListePaiementController extends AbstractController
{
    #[Route('/paiement/liste', name: 'app_liste_paiement')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($this->getUser() === null) {
            return $this->redirectToRoute('app_login');
        }
        $user = $this->getUser();

        $locations = $entityManager->getRepository(Locations::class)->findBy(['locataire' => $user]);
        $paiements = $entityManager->getRepository(Paiements::class)->findBy(['location' => $locations]);

        dump($paiements);

        return $this->render('liste_paiement/index.html.twig', [
            'controller_name' => 'ListePaiementController',
            'paiements' => $paiements,   
        ]);
    }
}