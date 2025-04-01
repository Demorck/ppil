<?php

declare(strict_types=1);

namespace App\Controller\Administrateur;

use App\Service\StatsService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractController
{
    #[Route('/admin/dashboard', name: 'app_admin_dashboard')]
    public function index(StatsService $statsService): Response
    {
        $stats = $statsService->getStats();
        $offre_status = $statsService->getStatsByMonth();
        $prix = $statsService->getPrix();

        if (!$this->getUser())
        {
            throw $this->createNotFoundException();
        }


        return $this->render('administrateur/dashboard.html.twig', [
            'stats' => $stats,
            'offre_status' => json_encode($offre_status),
            'prix' => $prix,

            'users_roles' => $statsService->get_users_roles(),
//            'users_months' => $statsService->get_new_users_by_month(),
        ]);
    }
}
