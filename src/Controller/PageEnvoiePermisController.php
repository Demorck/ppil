<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Request;

class PageEnvoiePermisController extends AbstractController
{
    #[Route('/envoiePermis', name: 'app_page_envoie_permis')]
    public function index(Request $request, EntityManagerInterface $entityManager, UserInterface $user): Response
    {
        if ($this->getUser() === null) {
            return $this->redirectToRoute('app_login');
        }

        if ($request->isMethod('POST')) {
            $user->setStatut(1);
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('/verificationPermis');
        }

        return $this->render('page_envoie_permis/index.html.twig', [
            'controller_name' => 'PageEnvoiePermisController',
        ]);
    }
}
