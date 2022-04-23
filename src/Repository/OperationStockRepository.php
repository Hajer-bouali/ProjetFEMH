<?php

namespace App\Repository;

use App\Entity\OperationStock;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OperationStock|null find($id, $lockMode = null, $lockVersion = null)
 * @method OperationStock|null findOneBy(array $criteria, array $orderBy = null)
 * @method OperationStock[]    findAll()
 * @method OperationStock[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OperationStockRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OperationStock::class);
    }

    // /**
    //  * @return OperationStock[] Returns an array of OperationStock objects
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
    public function findOneBySomeField($value): ?OperationStock
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
