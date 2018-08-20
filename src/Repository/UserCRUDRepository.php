<?php

namespace App\Repository;

use App\Entity\UserCRUD;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UserCRUD|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserCRUD|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserCRUD[]    findAll()
 * @method UserCRUD[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserCRUDRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserCRUD::class);
    }

//    /**
//     * @return UserCRUD[] Returns an array of UserCRUD objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserCRUD
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
