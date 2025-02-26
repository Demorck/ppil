<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Vehicules; 
use App\Entity\Offres; 

final class ListeOffresController extends AbstractController
{
    #[Route('/offre/liste', name: 'app_liste_offres')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($this->getUser() === null) {
            return $this->redirectToRoute('app_login');
        }
        $user = $this->getUser();

        $offres = $entityManager->createQuery(
            'SELECT offres, vehicule FROM App\\Entity\\Offres offres
            JOIN offres.vehicule vehicule
            WHERE vehicule.proprietaire = :userId'
        )->setParameter('userId', $user->getId())->getResult();
        
        dump($offres);

        return $this->render('liste_offres/index.html.twig', [
            'controller_name' => 'ListeOffresController',
            'offres' => $offres,   
        ]);
    }
}
