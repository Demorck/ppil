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

        public function findByFilters(?int $nbPlace, ?string $dateDebut, ?string $dateFin)
    {
        $qb = $this->createQueryBuilder('o')
            ->join('o.vehicule', 'v')
            ->where('o.statut = 1'); // On ne récupère que les offres actives
    
        if ($nbPlace) {
            $qb->andWhere('v.nombrePlace = :nbPlace')
               ->setParameter('nbPlace', $nbPlace);
        }
    
        if ($dateDebut && $dateFin) {
            // Vérifier que l'offre chevauche la période demandée
            $qb->andWhere('o.dateDebut <= :dateDebut')
               ->andWhere('o.dateFin >= :dateFin')
               ->setParameter('dateDebut', new \DateTime($dateDebut))
               ->setParameter('dateFin', new \DateTime($dateFin));
        }
    
        return $qb->getQuery()->getResult();
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
}
