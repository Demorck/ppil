<?php

declare(strict_types=1);

namespace App\Controller\Administrateur;

use App\Entity\Offres;
use App\Entity\Utilisateurs;
use App\Enum\OffreStatus;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Enum\UserStatus;

class SuspendreOffreController extends AbstractController
{
    #[Route('/admin/suspendre', name: 'app_admin_suspendre')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $offres = $entityManager->getRepository(Offres::class)->findAll();

        return $this->render('administrateur/suspendre_offre.html.twig', [
            'offres' => $offres
        ]);
    }

    #[Route('/admin/suspendre/{id}', name: 'app_admin_suspendre_offre')]
    public function ban_user(EntityManagerInterface $entityManager, $id): Response
    {
        $offre = $entityManager->getRepository(Offres::class)->findOneBy([
            'id' => $id
        ]);

        $response = [];
        if ($offre->getStatut() == OffreStatus::Actif->value)
        {
            $offre->setStatut(OffreStatus::Suspendue->value);
        } else
        {
            $offre->setStatut(OffreStatus::Actif->value);
        }


        $entityManager->flush();

        switch ($offre->getStatut())
        {
            case 0:
                $response['status'] = "Inactif";
                $response['parent_class'] = 'bg-green-400';
                $response['text'] = "Rendre actif";
                break;
            case 1:
            default:
                $response['status'] = "Actif";
                $response['parent_class'] = 'bg-red-400';
                $response['text'] = "Suspendre";
                break;
            case 3:
                $response['status'] = "Suspendue";
                $response['parent_class'] = 'bg-green-400';
                $response['text'] = "Rendre actif";
                break;

        }

        return new Response(json_encode($response));
    }
}
