<?php

namespace App\Controller\Permis;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;

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

            return $this->redirectToRoute('app_permis_validation');
        }

        return $this->render('permis/envoie_permis.html.twig', [
            'controller_name' => 'PageEnvoiePermisController',
        ]);
    }
}
