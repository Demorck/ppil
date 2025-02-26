<?php

namespace App\Controller\Offres;

use App\Entity\Offres;
use App\Form\FormulaireCreerOffreType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class FormulaireCreerOffreController extends AbstractController
{
    #[Route('/offre/creer', name: 'app_formulaire_creer_offre')]
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
        
            return $this->redirectToRoute('app_liste_offres');
        }

        return $this->render('formulaire_creer_offre/index.html.twig', [
            'form'  => $form->createView(),
            'title' => "Créer une offre",
        ]);
    }
}
