<?php

namespace App\Controller\Litiges;

use App\Entity\Litiges;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

class GererLitigeController extends AbstractController
{
    #[Route('/juriste/litige/gerer/{id}', name: 'app_gerer_litige')]
    public function index(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $litige = $entityManager->getRepository(Litiges::class)->findBy(['id' => $id]);

        dump($litige);

        return $this->render('gerer_litige/index.html.twig', [
            'controller_name' => 'GererLitigeController',
            'litige' => $litige[0],
        ]);
    }

    #[Route('/juriste/litige/valider/{id}', name: 'valider_litige', methods: ['GET'])]
    public function activer(EntityManagerInterface $entityManager, int $id): Response
    {
        $litige = $entityManager->getRepository(Litiges::class)->find($id);
        $litige->setStatut(1);
        $entityManager->persist($litige);
        $entityManager->flush();
        return $this->redirectToRoute('app_juriste_litiges');
    }

    #[Route('/offre/suspendre/valider/refuser/{id}', name: 'refuser_litige', methods: ['GET'])]
    public function suspendre(EntityManagerInterface $entityManager, int $id): Response
    {
        $litige = $entityManager->getRepository(Litiges::class)->find($id);
        $litige->setStatut(1);
        $entityManager->persist($litige);
        $entityManager->flush();
        return $this->redirectToRoute('app_juriste_litiges');
    }
}
