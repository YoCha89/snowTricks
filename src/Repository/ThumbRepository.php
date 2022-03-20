<?php

namespace App\Repository;

use App\Entity\Thumb;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Thumb|null find($id, $lockMode = null, $lockVersion = null)
 * @method Thumb|null findOneBy(array $criteria, array $orderBy = null)
 * @method Thumb[]    findAll()
 * @method Thumb[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ThumbRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Thumb::class);
    }

    // /**
    //  * @return Thumb[] Returns an array of Thumb objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Thumb
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
