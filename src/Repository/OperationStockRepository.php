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

    public function updateQuantite($operationStock) {
        $quantite = 0;
        foreach ($operationStock->getStocks() as $stock) {
            if ($stock->getOperationStock()->getEtat() == 'valide') {
                $quantite = 
                $stock->getOperationStock()->getTypeoperation() == 'aide' ?
                $quantite - $stock->getProduit()->getQuantite() : 
                $quantite + $stock->getProduit()->getQuantite(); 
            }
        }
        $res=$stock->getProduit()->setQuantite($quantite);
        $this->_em->persist($res);
        $this->_em->flush();
    }
}
