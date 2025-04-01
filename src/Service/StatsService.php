<?php

namespace App\Service;

use App\Entity\Locations;
use App\Entity\Offres;
use App\Entity\Paiements;
use App\Entity\Utilisateurs;
use Doctrine\ORM\EntityManagerInterface;

class StatsService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getStats(): array
    {
        return [
            'total_offres' => $this->entityManager->getRepository(Offres::class)->findAll(),
            'total_locations' => $this->entityManager->getRepository(Locations::class)->count(),
            'total_paiements' => $this->entityManager->getRepository(Paiements::class)->count(),
        ];
    }

    public function getStatsByMonth(): array
    {
        return $this->entityManager->getRepository(Offres::class)->getStatsByMonth();
    }

    public function getPrix(): array
    {
        return $this->entityManager->getRepository(Offres::class)->getPrix();
    }

    public function get_users_roles(): array
    {
        return $this->entityManager->getRepository(Utilisateurs::class)->getRoles();
    }

    public function get_new_users_by_month(): array
    {
        return $this->entityManager->getRepository(Utilisateurs::class)->get_new_users_by_month();
    }
}