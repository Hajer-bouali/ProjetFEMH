<?php

namespace App\Repository;

use App\Entity\OperationFinanciereAide;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OperationFinanciereAide|null find($id, $lockMode = null, $lockVersion = null)
 * @method OperationFinanciereAide|null findOneBy(array $criteria, array $orderBy = null)
 * @method OperationFinanciereAide[]    findAll()
 * @method OperationFinanciereAide[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OperationFinanciereAideRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OperationFinanciereAide::class);
    }

    // /**
    //  * @return OperationFinanciereAide[] Returns an array of OperationFinanciereAide objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OperationFinanciereAide
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
