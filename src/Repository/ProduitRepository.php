<?php

namespace App\Repository;

use App\Entity\Produit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Produit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produit[]    findAll()
 * @method Produit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }

    public function updateQuantiteProduit($produit) {
        $quantite = 0;
        foreach ($produit->getOperationStocks() as $operationstock) {
            if ($operationstock->getEtat() == 'valide') {
                $quantite = 
                $operationstock->getTypeoperation() == 'aide' ?
                $quantite - $operationstock->getQuantite() :
                $quantite + $operationstock->getQuantite(); 
            }
        }
        $produit->setQuantite($quantite);
        $this->_em->persist($produit);
        $this->_em->flush();
    }
}
