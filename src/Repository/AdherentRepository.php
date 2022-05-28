<?php

namespace App\Repository;

use App\Entity\Adherent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Adherent|null find($id, $lockMode = null, $lockVersion = null)
 * @method Adherent|null findOneBy(array $criteria, array $orderBy = null)
 * @method Adherent[]    findAll()
 * @method Adherent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdherentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Adherent::class);
    }

    /**
     * @return Adherent[] Returns an array of Adherent objects
     */
    public function findByCriteres($criteres)
    {
        $queryBuilder = $this->createQueryBuilder('a')
        ->where('a.etatreunion = :etatreunion')
        ->setParameter('etatreunion', 'validé');

        if ($criteres['nombrefamille_min'] != '') {
            $queryBuilder->andWhere('a.nombrefamille >= :nombrefamille_min')
            ->setParameter('nombrefamille_min', $criteres['nombrefamille_min']);
        }

        if ($criteres['nombrefamille_max'] != '') {
            $queryBuilder->andWhere('a.nombrefamille <= :nombrefamille_max')
            ->setParameter('nombrefamille_max', $criteres['nombrefamille_max']);
        }
        
        if ($criteres['montantrevenu_min'] != '') {
            $queryBuilder->andWhere('a.montantrevenu >= :montantrevenu_min')
            ->setParameter('montantrevenu_min', $criteres['montantrevenu_min']);
        }

        if ($criteres['montantrevenu_max'] != '') {
            $queryBuilder->andWhere('a.montantrevenu <= :montantrevenu_max')
            ->setParameter('montantrevenu_max', $criteres['montantrevenu_max']);
        }
       
        return $queryBuilder
            ->getQuery()
            ->getResult()
        ;
    }
}