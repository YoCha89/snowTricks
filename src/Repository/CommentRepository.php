<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }
   
/*    public function prepareComment($trick) {

        $query = $this->_em->createQuery('
            SELECT cChild, cPar
            FROM App\Entity\Comment cPar
            FROM App\Entity\Comment cChild
            WHERE cChild.commentParent BETWEEN cPar.commentParent AND cPar.comments
            AND cPar.trick = ?1
            ORDER BY cChild.commentParent
         ');

        $query->setParameters(array('1' => $trick->getId()));

        return $query->getResult();
    }*/

    public function findByOrder($id)
        {
            return $this->createQueryBuilder('c')
                ->andWhere('c.trick = :trick')
                ->andWhere('c.lvl = :lvl')
                ->setParameter('trick', $id)
                ->setParameter('lvl', 1)
                ->orderBy('c.createdAt', 'DESC')
                ->getQuery()
                ->getResult()
            ;
        }
    // /**
    //  * @return Comment[] Returns an array of Comment objects
    //  */
    /*

    */

    /*
    public function findOneBySomeField($value): ?Comment
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
