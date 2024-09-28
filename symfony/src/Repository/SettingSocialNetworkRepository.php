<?php

namespace App\Repository;

use App\Entity\SettingSocialNetwork;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SettingSocialNetwork>
 *
 * @method SettingSocialNetwork|null find($id, $lockMode = null, $lockVersion = null)
 * @method SettingSocialNetwork|null findOneBy(array $criteria, array $orderBy = null)
 * @method SettingSocialNetwork[]    findAll()
 * @method SettingSocialNetwork[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SettingSocialNetworkRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SettingSocialNetwork::class);
    }

    public function getAllSocialNetworks(): array
    {
        return $this->createQueryBuilder('sn')
            ->orderBy('sn.position', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function getEnabledSocialNetworks(): array
    {
        return $this->createQueryBuilder('sn')
            ->andWhere('sn.enabled = :enabled')
            ->setParameter('enabled', true)
            ->orderBy('sn.position', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function save(SettingSocialNetwork $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function flush(): void
    {
        $this->getEntityManager()->flush();
    }

    //    /**
    //     * @return SettingSocialNetwork[] Returns an array of SettingSocialNetwork objects
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

    //    public function findOneBySomeField($value): ?SettingSocialNetwork
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
