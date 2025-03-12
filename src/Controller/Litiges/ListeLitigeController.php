<?php

namespace App\Controller\Litiges;

use App\Entity\Locations;
use App\Entity\Litiges;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ListeLitigeController extends AbstractController
{
    #[Route('/litiges', name: 'app_litiges')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($this->getUser() === null) {
            return $this->redirectToRoute('app_login');
        }
        $user = $this->getUser();

        $locations = $entityManager->getRepository(Locations::class)->findBy(['locataire' => $user]);
        $litiges = $entityManager->getRepository(Litiges::class)->findBy(['location' => $locations]);

        dump($litiges);

        return $this->render('liste_litige/index.html.twig', [
            'controller_name' => 'litigesController',
            'litiges' => $litiges,   
        ]);
    }
}
