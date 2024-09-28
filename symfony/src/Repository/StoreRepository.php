<?php

namespace App\Repository;

use App\Entity\Store;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Store>
 */
class StoreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Store::class);
    }

    //    /**
    //     * @return Store[] Returns an array of Store objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Store
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    // public function findAreaBetween(float $min, float $max): array
    // {
    //     //The query builder is normally retrieved
    //     $queryBuilder = $this->createQueryBuilder('b');

    //     //We assume that the ST_AREA has been declared in configuration
    //     return $queryBuilder->where('ST_AREA(b.plan) BETWEEN :min AND :max')
    //         ->setParameter('min', $min, 'float')
    //         ->setParameter('max', $max, 'float')
    //         ->getQuery()
    //         ->getResult()
    //     ;
    // }
}
