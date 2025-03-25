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
        $statsMonth = $statsService->getStatsByMonth();
        $prix = $statsService->getPrix();

        if (!$this->getUser())
        {
            throw $this->createNotFoundException();
        }


        return $this->render('administrateur/dashboard.html.twig', [
            'stats' => $stats,
            'stats_month' => json_encode($statsMonth),
            'prix' => $prix
        ]);
    }
}
