<?php

namespace App\Repository;

use App\Entity\OperationFinanciere;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OperationFinanciere|null find($id, $lockMode = null, $lockVersion = null)
 * @method OperationFinanciere|null findOneBy(array $criteria, array $orderBy = null)
 * @method OperationFinanciere[]    findAll()
 * @method OperationFinanciere[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OperationFinanciereRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OperationFinanciere::class);
    }

   /**
     * @return OperationFinanciere[] Returns an array of OperationFinanciere objects
     */
    public function findCaisseByDate($datedebut , $datefin , $caisse, $typeOperation = null) {
        $queryBuilder = $this->createQueryBuilder('o')
        ->join('o.caisse', 'c')
        ->where('o.date >= :datedebut')
        ->setParameter('datedebut', $datedebut)
        ->andwhere('o.date <= :datefin')
        ->setParameter('datefin', $datefin)
        ->andwhere('c.id = :caisse')
        ->setParameter('caisse', $caisse);
        
        if ($typeOperation) {
            $queryBuilder->andwhere('o.typeoperation = :typeOperation')
            ->setParameter('typeOperation', $typeOperation);
        }

        return $queryBuilder
        ->getQuery()
        ->getResult();
    }

    /*
    public function findOneBySomeField($value): ?OperationFinanciere
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
