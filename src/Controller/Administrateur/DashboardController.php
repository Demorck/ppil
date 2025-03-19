<?php

declare(strict_types=1);

namespace App\Controller\Administrateur;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractController
{
    #[Route('/admin/dashboard', name: 'app_admin_dashboard')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        if (!$this->getUser())
        {
            throw new NotFoundHttpException();
        }


        return $this->render('administrateur/dashboard.html.twig');
    }
}
