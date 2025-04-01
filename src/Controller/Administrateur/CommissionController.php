<?php

declare(strict_types=1);

namespace App\Controller\Administrateur;

use App\Form\CommissionType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\CommissionService;

class CommissionController extends AbstractController
{
    #[Route('/admin/commission', name: 'app_admin_commission')]
    public function index(Request $request, CommissionService $commissionService): Response
    {
        $form = $this->createForm(CommissionType::class, ['pourcentage' => $commissionService->getCommission()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $commissionService->updateCommission($data['pourcentage']);
            $this->addFlash('success', 'Commission mise à jour avec succès.');
            return $this->redirectToRoute('app_admin_commission');
        }

        return $this->render('administrateur/commission.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
