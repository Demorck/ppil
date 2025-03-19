<?php

declare(strict_types=1);

namespace App\Controller\Administrateur;

use App\Entity\Utilisateurs;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Enum\UserStatus;

class ExclusionUtilisateurController extends AbstractController
{
    #[Route('/admin/ban', name: 'app_admin_ban')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $users = $entityManager->getRepository(Utilisateurs::class)->findAll();

        return $this->render('administrateur/ban_user.html.twig', [
            'users' => $users
        ]);
    }

    #[Route('/admin/ban/{id}', name: 'app_admin_ban_user')]
    public function ban_user(EntityManagerInterface $entityManager, $id): Response
    {
        $user = $entityManager->getRepository(Utilisateurs::class)->findOneBy([
            'id' => $id
        ]);

        $response = [];
        if ($user->getStatut() == UserStatus::Exclu->value)
        {
            $user->setStatut(UserStatus::Actif->value);
            $response['text'] = "L'utilisateur a été débanni.";
        } else
        {
            $user->setStatut(UserStatus::Exclu->value);
            $response['text'] = "L'utilisateur a été banni.";
        }


        $entityManager->flush();

        $response['statut'] = $user->getStatut();

        return new Response(json_encode($response));
    }
}
