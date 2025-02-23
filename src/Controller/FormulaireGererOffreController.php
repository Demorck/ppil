<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use App\Form\FormulaireCreerOffreType;
use App\Entity\Offres;

final class FormulaireGererOffreController extends AbstractController
{
    #[Route('/offre/gerer', name: 'app_formulaire_gerer_offre')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($this->getUser() === null) {
            return $this->redirectToRoute('app_login');
        }

        $offre = new Offres();
        $user = $this->getUser();
        
        $form = $this->createForm(FormulaireCreerOffreType::class, $offre, [
            'user' => $user,
        ]);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $offre->setStatut(0);
            $entityManager->persist($offre);
            $entityManager->flush();
        
            return $this->redirectToRoute('app_formulaire_vehicules');
        }

        return $this->render('formulaire_gerer_offre/index.html.twig', [
            'form' => $form->createView(),
            'title' => "Gerer une offre",
        ]);
    }
}
