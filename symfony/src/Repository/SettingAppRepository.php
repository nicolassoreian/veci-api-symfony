<?php

namespace App\Repository;

use App\Entity\SettingApp;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SettingApp>
 *
 * @method SettingApp|null find($id, $lockMode = null, $lockVersion = null)
 * @method SettingApp|null findOneBy(array $criteria, array $orderBy = null)
 * @method SettingApp[]    findAll()
 * @method SettingApp[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SettingAppRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SettingApp::class);
    }

    public function getSetting(): ?SettingApp
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.id = :id')
            ->setParameter('id', 1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function save(SettingApp $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    //    /**
    //     * @return SettingApp[] Returns an array of SettingApp objects
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

    //    public function findOneBySomeField($value): ?SettingApp
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
