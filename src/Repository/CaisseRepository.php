<?php

namespace App\Repository;

use App\Entity\Caisse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Caisse|null find($id, $lockMode = null, $lockVersion = null)
 * @method Caisse|null findOneBy(array $criteria, array $orderBy = null)
 * @method Caisse[]    findAll()
 * @method Caisse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CaisseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Caisse::class);
    }

    public function updateMontant($caisse)
    {
        $montant = 0;
            foreach ($caisse->getOperationFinancieres() as $operationFinanciere) {
                if ($operationFinanciere->getEtat() == 'valide') {
                    $montant =
                    $operationFinanciere->getTypeoperation() == 'aide' ?
                    $montant - $operationFinanciere->getMontant() :
                    $montant + $operationFinanciere->getMontant();
                }
            }
      
        $caisse->setMontant($montant);
        $this->_em->persist($caisse);
        $this->_em->flush();
    }
}
