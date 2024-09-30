<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Merchant;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<Merchant>
 *
 * @method Merchant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Merchant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Merchant[]    findAll()
 * @method Merchant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MerchantRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Merchant::class);
    }

    public function findByString(?string $string): QueryBuilder
    {
        $qb = $this->createQueryBuilder('m');

        if ($string) {
            $qb
                ->orWhere('m.email LIKE :email')
                ->setParameter('email', '%'.$string.'%')
            ;
        }

        $qb
            ->addOrderBy('m.enabled', 'DESC');

        return $qb;
    }

    public function save(Merchant $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Merchant $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Used to upgrade (rehash) the merchant's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $merchant, string $newHashedPassword): void
    {
        if (!$merchant instanceof Merchant) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($merchant)));
        }

        $merchant->setPassword($newHashedPassword);

        $this->save($merchant, true);
    }
}