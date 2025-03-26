<?php

namespace App\Service;

use App\Entity\Commission;
use Doctrine\ORM\EntityManagerInterface;

class CommissionService
{
    public function __construct(private EntityManagerInterface $entityManager) {}

    public function getCommission(): float
    {
        $commission = $this->entityManager->getRepository(Commission::class)->find(1);
        return $commission ? $commission->getPourcentage() : 10.0;
    }

    public function updateCommission(float $newPercentage): void
    {
        $commission = $this->entityManager->getRepository(Commission::class)->find(1);
        if (!$commission) {
            $commission = new Commission();
        }
        $commission->setPourcentage($newPercentage);
        $this->entityManager->persist($commission);
        $this->entityManager->flush();
    }
}