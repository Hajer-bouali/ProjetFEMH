<?php

namespace App\Repository;

use App\Entity\PiecesJointes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PiecesJointes|null find($id, $lockMode = null, $lockVersion = null)
 * @method PiecesJointes|null findOneBy(array $criteria, array $orderBy = null)
 * @method PiecesJointes[]    findAll()
 * @method PiecesJointes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PiecesJointesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PiecesJointes::class);
    }

    // /**
    //  * @return PiecesJointes[] Returns an array of PiecesJointes objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PiecesJointes
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
