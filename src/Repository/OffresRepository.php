<?php

namespace App\Repository;

use App\Entity\Offres;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Offres>
 */
class OffresRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Offres::class);
    }

    //    /**
    //     * @return Offres[] Returns an array of Offres objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('o.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Offres
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function getStatsByMonth(): array
    {
        return $this->createQueryBuilder('o') // Correct ici
            ->select("o.statut as status, COUNT(o.id) as count")
            ->groupBy('o.statut')
            ->getQuery()
            ->getResult();
    }

    public function getPrix(): array
    {
        return $this->createQueryBuilder('o')
            ->select("AVG(o.prix) as prix, o.dateFin - o.dateDebut as duree")
            ->groupBy("duree")
            ->getQuery()
            ->getResult();
    }
}
