<?php

namespace App\Repository;

use App\Entity\TypeCaisse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TypeCaisse|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeCaisse|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeCaisse[]    findAll()
 * @method TypeCaisse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeCaisseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeCaisse::class);
    }

    // /**
    //  * @return TypeCaisse[] Returns an array of TypeCaisse objects
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
    public function findOneBySomeField($value): ?TypeCaisse
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
