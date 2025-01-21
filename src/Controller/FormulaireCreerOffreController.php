<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use App\Form\FormulaireCreerOffreType;
use App\Entity\Offres;

final class FormulaireCreerOffreController extends AbstractController
{
    #[Route('/formulaire/creer/offre', name: 'app_formulaire_creer_offre')]
    public function index(Request $request): Response
    {
        $offre = new Offres();
        $user = $this->getUser();
        
        $form = $this->createForm(FormulaireCreerOffreType::class, $offre, [
            'user' => $user,
        ]);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($offre);
            $entityManager->flush();
        
            return $this->redirectToRoute('some_route_name');
        }

        return $this->render('formulaire_creer_offre/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
