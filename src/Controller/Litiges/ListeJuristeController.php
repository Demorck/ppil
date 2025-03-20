<?php

namespace App\Controller\Litiges;

use App\Entity\Litiges;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ListeJuristeController extends AbstractController
{
    #[Route('/juriste/litiges', name: 'app_juriste_litiges')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($this->getUser() === null) {
            return $this->redirectToRoute('app_login');
        }
        $user = $this->getUser();

        $litiges = $entityManager->getRepository(Litiges::class)->findBy(['juriste' => $user]);

        dump($litiges);

        return $this->render('litiges/juriste_litiges.html.twig', [
            'controller_name' => 'litigesController',
            'litiges' => $litiges,   
        ]);
    }
}
