<?php

namespace App\Controller\Offres\Formulaires;

use App\Entity\Offres;
use App\Form\FormulaireCreerOffreType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ModifierOffreController extends AbstractController
{
    #[Route('/offre/modifier/{id}', name: 'app_modifier_offre')]
    public function index(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        if ($this->getUser() === null) {
            return $this->redirectToRoute('app_login');
        }

        $offre = $entityManager->getRepository(Offres::class)->find($id);

        if (!$offre) {
            throw $this->createNotFoundException('Offre non trouvée.');
        }

        $user = $this->getUser();

        $form = $this->createForm(FormulaireCreerOffreType::class, $offre, [
            'user' => $user,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_liste_offres');
        }

        return $this->render('offres/formulaires/modifier_offre.html.twig', [
            'form'  => $form->createView(),
            'title' => "Gérer une offre",
            'offre' => $offre,
        ]);
    }

    #[Route('/offre/activer/{id}', name: 'activer_offre', methods: ['GET'])]
    public function activer(EntityManagerInterface $entityManager, int $id): Response
    {
        $offre = $entityManager->getRepository(Offres::class)->find($id);
        $offre->setStatut(1);
        $entityManager->persist($offre);
        $entityManager->flush();
        return $this->redirectToRoute('app_liste_offres');
    }

    #[Route('/offre/suspendre/{id}', name: 'suspendre_offre', methods: ['GET'])]
    public function suspendre(EntityManagerInterface $entityManager, int $id): Response
    {
        $offre = $entityManager->getRepository(Offres::class)->find($id);
        $offre->setStatut(0);
        $entityManager->persist($offre);
        $entityManager->flush();
        return $this->redirectToRoute('app_liste_offres');
    }
}
